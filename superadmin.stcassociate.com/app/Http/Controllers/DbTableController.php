<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDO;

class DbTableController extends Controller
{
    const MAX_ROWS = 500;
    const LOG_SQL_LEN = 8000;

    private $forbidden = [
        'INSERT', 'UPDATE', 'DELETE', 'DROP', 'ALTER', 'CREATE', 'TRUNCATE',
        'REPLACE', 'RENAME', 'GRANT', 'REVOKE', 'CALL', 'HANDLER',
        'LOCK', 'UNLOCK', 'PREPARE', 'EXECUTE', 'DEALLOCATE',
        'COMMIT', 'ROLLBACK', 'SAVEPOINT', 'RELEASE', 'PURGE', 'RESET',
        'FLUSH', 'KILL', 'SHUTDOWN', 'OPTIMIZE', 'REPAIR', 'ANALYZE',
        'INSTALL', 'UNINSTALL', 'BINLOG', 'CHANGE', 'CHECKSUM',
        'INTO OUTFILE', 'INTO DUMPFILE', 'LOAD_FILE', 'LOAD DATA', 'LOAD XML',
        'FOR UPDATE', 'LOCK IN SHARE MODE', 'PROCEDURE ANALYSE',
    ];

    public function index()
    {
        $this->ensureLogTable();

        return view('pages.dbtable', [
            'page_title' => 'DB Table',
            'db_name' => DB::connection()->getDatabaseName(),
        ]);
    }

    public function tables()
    {
        $this->ensureLogTable();
        $db = DB::connection()->getDatabaseName();
        $rows = DB::select(
            'SELECT table_name AS t FROM information_schema.tables WHERE table_schema = ? AND table_type = "BASE TABLE" ORDER BY table_name',
            [$db]
        );
        $tables = [];
        foreach ($rows as $row) {
            $name = (string) $row->t;
            if ($this->validIdent($name)) {
                $tables[] = $name;
            }
        }

        return $this->ok([
            'db' => $db,
            'tables' => $tables,
            'can_create' => true,
            'can_edit' => true,
            'can_delete' => true,
        ]);
    }

    public function run(Request $request)
    {
        $sql = (string) $request->input('sql', '');
        $check = $this->assertReadonly($sql);
        if ($check !== true) {
            return $this->fail($check);
        }

        $sql = rtrim($this->normalizeSql($sql), "; \t\n\r");
        $pdo = DB::connection()->getPdo();
        $started = microtime(true);

        try {
            $stmt = $pdo->query($sql);
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage(), [
                'elapsed_ms' => (int) round((microtime(true) - $started) * 1000),
            ]);
        }

        $elapsed = (int) round((microtime(true) - $started) * 1000);
        $columns = [];
        $blobCols = [];
        $colCount = $stmt->columnCount();
        for ($i = 0; $i < $colCount; $i++) {
            $meta = $stmt->getColumnMeta($i);
            $name = isset($meta['name']) ? $meta['name'] : ('col_'.$i);
            $columns[] = $name;
            $native = isset($meta['native_type']) ? strtolower((string) $meta['native_type']) : '';
            if (strpos($native, 'blob') !== false || strpos($native, 'binary') !== false) {
                $blobCols[$name] = true;
            }
        }

        $rows = [];
        $truncated = false;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (count($rows) >= self::MAX_ROWS) {
                $truncated = true;
                break;
            }
            $clean = [];
            foreach ($columns as $col) {
                $val = array_key_exists($col, $row) ? $row[$col] : null;
                if ($val !== null && isset($blobCols[$col])) {
                    $val = '[BLOB]';
                }
                $clean[$col] = $val;
            }
            $rows[] = $clean;
        }

        $table = $this->simpleSelectTable($sql);
        $writable = false;
        $pk = [];
        $autoInc = [];
        $colMeta = [];
        if ($table && $this->tableExists($table)) {
            $info = $this->tableInfo($table);
            $pk = $info['pk'];
            $autoInc = $info['auto_inc'];
            $colMeta = $info['columns'];
            if ($pk) {
                $have = [];
                foreach ($columns as $c) {
                    $have[strtolower($c)] = $c;
                }
                $ok = true;
                foreach ($pk as $pkCol) {
                    if (!isset($have[strtolower($pkCol)])) {
                        $ok = false;
                        break;
                    }
                }
                $writable = $ok;
            }
        }

        $this->log('query', $table, $sql, 'rows='.count($rows).($truncated ? '+truncated' : ''));

        return $this->ok([
            'columns' => $columns,
            'rows' => $rows,
            'row_count' => count($rows),
            'displayed' => count($rows),
            'truncated' => $truncated,
            'elapsed_ms' => $elapsed,
            'db' => DB::connection()->getDatabaseName(),
            'table' => $table,
            'writable' => $writable,
            'pk' => $pk,
            'auto_inc' => $autoInc,
            'column_meta' => $colMeta,
            'can_create' => true,
            'can_edit' => true,
            'can_delete' => true,
        ]);
    }

    public function updateRow(Request $request)
    {
        $table = (string) $request->input('table', '');
        $pkVals = $this->jsonInput($request, 'pk');
        $fields = $this->jsonInput($request, 'fields');
        $prep = $this->prepareMutation($table, $pkVals);
        if (isset($prep['success']) && $prep['success'] === false) {
            return $prep;
        }
        if (!$fields) {
            return $this->fail('No fields to update.');
        }

        $sets = [];
        $params = [];
        foreach ($fields as $col => $val) {
            $col = (string) $col;
            if (!$this->validIdent($col) || !isset($prep['cols'][$col]) || in_array($col, $prep['pk'], true)) {
                continue;
            }
            if ($val === null) {
                $sets[] = $this->ident($col).'=NULL';
            } else {
                $sets[] = $this->ident($col).'=?';
                $params[] = (string) $val;
            }
        }
        if (!$sets) {
            return $this->fail('No updatable columns in the request.');
        }

        $sql = 'UPDATE '.$this->ident($table).' SET '.implode(', ', $sets).' WHERE '.$prep['where'].' LIMIT 1';
        try {
            DB::update($sql, array_merge($params, $prep['params']));
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }
        $this->log('update', $table, $sql, json_encode($pkVals));

        return $this->ok(['message' => 'Row updated.']);
    }

    public function copyRow(Request $request)
    {
        $table = (string) $request->input('table', '');
        $fields = $this->jsonInput($request, 'fields');
        if (!$this->validIdent($table) || !$this->tableExists($table)) {
            return $this->fail('Unknown table.');
        }
        if (!$fields) {
            return $this->fail('No row data to copy.');
        }

        $info = $this->tableInfo($table);
        $cols = [];
        $placeholders = [];
        $params = [];
        foreach ($info['columns'] as $col => $meta) {
            if (in_array($col, $info['auto_inc'], true) || !array_key_exists($col, $fields)) {
                continue;
            }
            $cols[] = $this->ident($col);
            if ($fields[$col] === null) {
                $placeholders[] = 'NULL';
            } else {
                $placeholders[] = '?';
                $params[] = (string) $fields[$col];
            }
        }
        if (!$cols) {
            return $this->fail('Nothing to insert after omitting auto-increment keys.');
        }

        $sql = 'INSERT INTO '.$this->ident($table).' ('.implode(', ', $cols).') VALUES ('.implode(', ', $placeholders).')';
        try {
            DB::insert($sql, $params);
            $newId = DB::getPdo()->lastInsertId();
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }
        $this->log('copy', $table, $sql, 'insert_id='.$newId);

        return $this->ok(['message' => 'Row copied.', 'insert_id' => $newId]);
    }

    public function deleteRow(Request $request)
    {
        $table = (string) $request->input('table', '');
        $pkVals = $this->jsonInput($request, 'pk');
        $prep = $this->prepareMutation($table, $pkVals);
        if (isset($prep['success']) && $prep['success'] === false) {
            return $prep;
        }
        $sql = 'DELETE FROM '.$this->ident($table).' WHERE '.$prep['where'].' LIMIT 1';
        try {
            DB::delete($sql, $prep['params']);
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }
        $this->log('delete', $table, $sql, json_encode($pkVals));

        return $this->ok(['message' => 'Row deleted.']);
    }

    private function prepareMutation($table, $pkVals)
    {
        if (!$this->validIdent($table) || !$this->tableExists($table)) {
            return $this->fail('Unknown table.');
        }
        $info = $this->tableInfo($table);
        if (!$info['pk']) {
            return $this->fail('This table has no primary key.');
        }
        if (!is_array($pkVals)) {
            return $this->fail('Primary key values are required.');
        }
        $where = [];
        $params = [];
        foreach ($info['pk'] as $col) {
            if (!array_key_exists($col, $pkVals) || $pkVals[$col] === null || $pkVals[$col] === '') {
                return $this->fail('Missing primary key value for '.$col.'.');
            }
            $where[] = $this->ident($col).'=?';
            $params[] = (string) $pkVals[$col];
        }

        return [
            'pk' => $info['pk'],
            'cols' => $info['columns'],
            'where' => implode(' AND ', $where),
            'params' => $params,
        ];
    }

    private function tableExists($table)
    {
        $db = DB::connection()->getDatabaseName();
        $rows = DB::select(
            'SELECT 1 AS ok FROM information_schema.tables WHERE table_schema = ? AND table_name = ? AND table_type = "BASE TABLE" LIMIT 1',
            [$db, $table]
        );

        return !empty($rows);
    }

    private function tableInfo($table)
    {
        $pk = [];
        $autoInc = [];
        $columns = [];
        $keys = DB::select('SHOW KEYS FROM '.$this->ident($table).' WHERE Key_name = \'PRIMARY\'');
        foreach ($keys as $row) {
            $arr = (array) $row;
            if (!empty($arr['Column_name'])) {
                $pk[] = $arr['Column_name'];
            }
        }
        $cols = DB::select('SHOW COLUMNS FROM '.$this->ident($table));
        foreach ($cols as $row) {
            $arr = (array) $row;
            $field = $arr['Field'];
            $extra = isset($arr['Extra']) ? strtolower((string) $arr['Extra']) : '';
            $columns[$field] = [
                'type' => $arr['Type'],
                'null' => (isset($arr['Null']) && strtoupper($arr['Null']) === 'YES'),
                'extra' => $arr['Extra'],
                'key' => $arr['Key'],
                'default' => $arr['Default'],
            ];
            if (strpos($extra, 'auto_increment') !== false) {
                $autoInc[] = $field;
            }
        }

        return ['pk' => $pk, 'auto_inc' => $autoInc, 'columns' => $columns];
    }

    private function simpleSelectTable($sql)
    {
        $stripped = $this->stripStrings($this->normalizeSql($sql), true);
        $stripped = rtrim($stripped, "; \t\n\r");
        if (!preg_match('/^\s*SELECT\b/i', $stripped)) {
            return null;
        }
        if (preg_match('/\b(JOIN|UNION|INTO\s+OUTFILE|INTO\s+DUMPFILE|FOR\s+UPDATE|LOCK\s+IN\s+SHARE)\b/i', $stripped)) {
            return null;
        }
        if (preg_match_all('/\bFROM\b/i', $stripped) !== 1) {
            return null;
        }
        if (!preg_match('/\bFROM\s+(`?[A-Za-z0-9_]+`?)(\s+(?:AS\s+)?`?[A-Za-z0-9_]+`?)?/i', $stripped, $m)) {
            return null;
        }
        $after = substr($stripped, stripos($stripped, 'FROM') + 4);
        $after = preg_replace('/^\s+/', '', $after);
        $after = preg_replace('/^`?[A-Za-z0-9_]+`?(\s+(?:AS\s+)?`?[A-Za-z0-9_]+`?)?/i', '', $after, 1);
        if (preg_match('/^\s*,/', $after)) {
            return null;
        }
        $table = trim($m[1], '`');

        return $this->validIdent($table) ? $table : null;
    }

    private function assertReadonly($sql)
    {
        $norm = $this->normalizeSql($sql);
        if ($norm === '') {
            return 'Query is empty.';
        }
        $stripped = $this->stripStrings($norm);
        if (preg_match('/;\s*\S/', $stripped)) {
            return 'Multiple statements are not allowed.';
        }
        $stripped = rtrim($stripped, "; \t\n\r");
        if (!preg_match('/^(SELECT|SHOW|DESCRIBE|DESC|EXPLAIN|WITH)\b/i', $stripped)) {
            return 'Only SELECT, SHOW, DESCRIBE, EXPLAIN, and WITH queries are allowed.';
        }
        if (preg_match('/\b(INTO\s+OUTFILE|INTO\s+DUMPFILE|LOAD_FILE\s*\(|LOAD\s+DATA|LOAD\s+XML)\b/i', $stripped)) {
            return 'File I/O SQL is not allowed.';
        }
        if (preg_match('/^(SHOW|DESCRIBE|DESC|EXPLAIN)\b/i', $stripped)) {
            return true;
        }
        $scan = strtoupper($stripped);
        foreach ($this->forbidden as $word) {
            $quoted = preg_quote($word, '/');
            if (preg_match('/\b'.$quoted.'\b/', $scan)) {
                return 'Mutating or unsafe SQL is not allowed in the query box ('.$word.').';
            }
        }

        return true;
    }

    private function normalizeSql($sql)
    {
        $sql = str_replace("\0", '', (string) $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', ' ', $sql);
        $sql = preg_replace('/--[^\n]*/', ' ', $sql);
        $sql = preg_replace('/#[^\n]*/', ' ', $sql);

        return trim($sql);
    }

    private function stripStrings($sql, $keepIdents = false)
    {
        if ($keepIdents) {
            return preg_replace("/('([^'\\\\]|\\\\.)*'|\"([^\"\\\\]|\\\\.)*\")/s", "''", $sql);
        }

        return preg_replace("/('([^'\\\\]|\\\\.)*'|\"([^\"\\\\]|\\\\.)*\"|`([^`\\\\]|\\\\.)*`)/s", "''", $sql);
    }

    private function validIdent($name)
    {
        return is_string($name) && preg_match('/^[A-Za-z0-9_]+$/', $name);
    }

    private function ident($name)
    {
        return '`'.$name.'`';
    }

    private function jsonInput(Request $request, $key)
    {
        $raw = $request->input($key);
        if (is_array($raw)) {
            return $raw;
        }
        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    private function ensureLogTable()
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS `stc_db_table_log` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `user_id` INT NOT NULL DEFAULT 0,
                `user_name` VARCHAR(255) NOT NULL DEFAULT '',
                `action` VARCHAR(32) NOT NULL DEFAULT '',
                `table_name` VARCHAR(128) DEFAULT NULL,
                `sql_text` MEDIUMTEXT,
                `detail` TEXT,
                `created_at` DATETIME NOT NULL,
                PRIMARY KEY (`id`),
                KEY `user_id` (`user_id`),
                KEY `created_at` (`created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ");
    }

    private function log($action, $table, $sql, $detail)
    {
        $user = Auth::user();
        DB::table('stc_db_table_log')->insert([
            'user_id' => $user ? (int) $user->id : 0,
            'user_name' => $user && isset($user->name) ? substr((string) $user->name, 0, 255) : '',
            'action' => substr((string) $action, 0, 32),
            'table_name' => $table ? substr((string) $table, 0, 128) : null,
            'sql_text' => substr((string) $sql, 0, self::LOG_SQL_LEN),
            'detail' => substr((string) $detail, 0, 2000),
            'created_at' => now()->toDateTimeString(),
        ]);
    }

    private function ok(array $extra = [])
    {
        return response()->json(array_merge([
            'status' => 'ok',
            'success' => true,
        ], $extra));
    }

    private function fail($message, array $extra = [])
    {
        return response()->json(array_merge([
            'status' => 'ok',
            'success' => false,
            'message' => $message,
        ], $extra));
    }
}
