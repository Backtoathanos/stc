<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DbSyncController extends Controller
{
    private string $localConnection = 'mysql';
    private string $serverConnection = 'server';
    private string $tokenHeader = 'X-DBSYNC-TOKEN';
    private string $sharedToken = 'ILoveSanaYasinPussy';

    public function index(Request $request)
    {
        return view('pages.dbsync', [
            'page_title' => 'DB Sync',
            'report' => session('dbsync_last_report'),
            'queryResult' => session('dbsync_query_result'),
        ]);
    }

    public function sync(Request $request)
    {
        $startedAt = microtime(true);

        $local = DB::connection($this->localConnection);

        $report = [
            'ok' => false,
            'started_at' => now()->toDateTimeString(),
            'ended_at' => null,
            'duration_ms' => null,
            'tables' => [],
            'missing_columns_on_local' => [],
            'missing_columns_on_server' => [],
            'errors' => [],
        ];

        try {
            $isLocalHost = (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost')
                || $request->getHost() === 'localhost'
                || app()->environment('local');

            if ($isLocalHost) {
                $this->syncViaServerApi($local, $report);
            } else {
                // On server environment, we can sync from configured DB connection directly.
                $server = DB::connection($this->serverConnection);

                // Make sure the server connection works before doing anything destructive.
                $server->select('SELECT 1');

                $tables = $this->listTables($server);

                $local->statement('SET FOREIGN_KEY_CHECKS=0');

                foreach ($tables as $table) {
                    $tableStarted = microtime(true);

                    try {
                        // Only allow safe identifier characters.
                        if (!preg_match('/^[A-Za-z0-9_]+$/', $table)) {
                            throw new \RuntimeException("Invalid table name: {$table}");
                        }

                        $serverCols = $this->listColumns($server, $table);
                        $localCols = $this->listColumns($local, $table);

                        // If local table doesn't exist, record and skip copying data.
                        if (empty($localCols)) {
                            $report['tables'][] = [
                                'table' => $table,
                                'status' => 'skipped',
                                'reason' => 'Local table missing',
                                'rows_copied' => 0,
                                'ms' => (int) round((microtime(true) - $tableStarted) * 1000),
                            ];
                            continue;
                        }

                        // Truncate local table.
                        $local->statement("TRUNCATE TABLE `{$table}`");

                        // Copy data from server to local.
                        $rowsCopied = $this->copyTableData($server, $local, $table, $serverCols);

                        $report['tables'][] = [
                            'table' => $table,
                            'status' => 'synced',
                            'rows_copied' => $rowsCopied,
                            'ms' => (int) round((microtime(true) - $tableStarted) * 1000),
                        ];
                    } catch (\Throwable $e) {
                        $report['tables'][] = [
                            'table' => $table,
                            'status' => 'error',
                            'reason' => $e->getMessage(),
                            'rows_copied' => 0,
                            'ms' => (int) round((microtime(true) - $tableStarted) * 1000),
                        ];
                        $report['errors'][] = "{$table}: {$e->getMessage()}";
                    }
                }

                $local->statement('SET FOREIGN_KEY_CHECKS=1');

                // Column diff after sync.
                $this->fillColumnDiffs($server, $local, $report);
            }

            $report['ok'] = count($report['errors']) === 0;
        } catch (\Throwable $e) {
            $report['errors'][] = $e->getMessage();
        } finally {
            $report['ended_at'] = now()->toDateTimeString();
            $report['duration_ms'] = (int) round((microtime(true) - $startedAt) * 1000);
        }

        return redirect('/db-sync')->with('dbsync_last_report', $report);
    }

    public function runServerQuery(Request $request)
    {
        $sql = (string) $request->input('sql', '');
        $sql = trim($sql);

        $result = [
            'ok' => false,
            'message' => null,
            'sql' => $sql,
        ];

        if ($sql === '') {
            $result['message'] = 'SQL is empty.';
            return redirect('/db-sync')->with('dbsync_query_result', $result);
        }

        // Guardrail: only allow ALTER TABLE / CREATE TABLE statements (you can expand later).
        $upper = Str::upper(ltrim($sql));
        $allowed = Str::startsWith($upper, 'ALTER TABLE')
            || Str::startsWith($upper, 'CREATE TABLE')
            || Str::startsWith($upper, 'CREATE INDEX')
            || Str::startsWith($upper, 'DROP INDEX');

        if (!$allowed) {
            $result['message'] = 'Only ALTER TABLE / CREATE TABLE / CREATE INDEX / DROP INDEX are allowed here.';
            return redirect('/db-sync')->with('dbsync_query_result', $result);
        }

        try {
            $isLocalHost = (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost')
                || $request->getHost() === 'localhost'
                || app()->environment('local');

            if ($isLocalHost) {
                $base = rtrim((string) env('DBSYNC_SERVER_URL', 'https://stcassociate.com/superadmin.stcassociate.com'), '/');
                $j = $this->apiRequest('POST', "{$base}/api/db-sync/run-sql", ['sql' => $sql], [
                    $this->tokenHeader => $this->sharedToken,
                    'Accept' => 'application/json',
                ], 60);

                if (empty($j['ok'])) {
                    throw new \RuntimeException($j['message'] ?? 'Server API failed.');
                }

                $result['ok'] = true;
                $result['message'] = $j['message'] ?? 'Query executed on server successfully.';
            } else {
                $server = DB::connection($this->serverConnection);
                $server->unprepared($sql);
                $result['ok'] = true;
                $result['message'] = 'Query executed on server successfully.';
            }
        } catch (\Throwable $e) {
            $result['message'] = $e->getMessage();
        }

        return redirect('/db-sync')->with('dbsync_query_result', $result);
    }

    // =========================
    // Local mode: sync via API
    // =========================

    private function syncViaServerApi($local, array &$report): void
    {
        $base = rtrim((string) env('DBSYNC_SERVER_URL', 'https://stcassociate.com/superadmin.stcassociate.com'), '/');

        $headers = [
            $this->tokenHeader => $this->sharedToken,
            'Accept' => 'application/json',
        ];

        $ping = $this->apiRequest('GET', "{$base}/api/db-sync/ping", [], $headers, 30);
        if (empty($ping['ok'])) {
            $status = $ping['_status'] ?? 0;
            throw new \RuntimeException("Server API not reachable ({$status}).");
        }

        $tablesJson = $this->apiRequest('GET', "{$base}/api/db-sync/tables", [], $headers, 60);
        if (empty($tablesJson['ok'])) {
            $status = $tablesJson['_status'] ?? 0;
            $body = $tablesJson['_raw'] ?? '';
            throw new \RuntimeException("Failed to get tables ({$status}): {$body}");
        }
        $tables = isset($tablesJson['tables']) && is_array($tablesJson['tables']) ? $tablesJson['tables'] : [];

        $local->statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tables as $table) {
            $tableStarted = microtime(true);

            try {
                if (!is_string($table) || !preg_match('/^[A-Za-z0-9_]+$/', $table)) {
                    throw new \RuntimeException("Invalid table name: {$table}");
                }

                $localCols = $this->listColumns($local, $table);
                if (empty($localCols)) {
                    $report['tables'][] = [
                        'table' => $table,
                        'status' => 'skipped',
                        'reason' => 'Local table missing',
                        'rows_copied' => 0,
                        'ms' => (int) round((microtime(true) - $tableStarted) * 1000),
                    ];
                    continue;
                }

                // Ask server for column list for this table.
                $colsJson = $this->apiRequest('GET', "{$base}/api/db-sync/columns", ['table' => $table], $headers, 60);
                if (empty($colsJson['ok'])) {
                    $status = $colsJson['_status'] ?? 0;
                    $body = $colsJson['_raw'] ?? '';
                    throw new \RuntimeException("Failed to get columns ({$status}): {$body}");
                }
                $serverCols = isset($colsJson['columns']) && is_array($colsJson['columns']) ? $colsJson['columns'] : [];

                $commonCols = array_values(array_intersect($serverCols, $localCols));
                if (empty($commonCols)) {
                    $report['tables'][] = [
                        'table' => $table,
                        'status' => 'skipped',
                        'reason' => 'No common columns',
                        'rows_copied' => 0,
                        'ms' => (int) round((microtime(true) - $tableStarted) * 1000),
                    ];
                    continue;
                }

                // Truncate local table.
                $local->statement("TRUNCATE TABLE `{$table}`");

                $offset = 0;
                $limit = 1000;
                $total = 0;

                while (true) {
                    $chunkJson = $this->apiRequest('POST', "{$base}/api/db-sync/chunk", [
                        'table' => $table,
                        'offset' => $offset,
                        'limit' => $limit,
                        'columns' => $commonCols,
                    ], $headers, 120);
                    if (empty($chunkJson['ok'])) {
                        $status = $chunkJson['_status'] ?? 0;
                        $body = $chunkJson['_raw'] ?? '';
                        throw new \RuntimeException("Chunk fetch failed ({$status}): {$body}");
                    }
                    $rows = isset($chunkJson['rows']) && is_array($chunkJson['rows']) ? $chunkJson['rows'] : [];
                    if (empty($rows)) {
                        break;
                    }

                    foreach (array_chunk($rows, 500) as $ins) {
                        $local->table($table)->insert($ins);
                        $total += count($ins);
                    }

                    $offset += $limit;
                }

                $report['tables'][] = [
                    'table' => $table,
                    'status' => 'synced',
                    'rows_copied' => $total,
                    'ms' => (int) round((microtime(true) - $tableStarted) * 1000),
                ];
            } catch (\Throwable $e) {
                $report['tables'][] = [
                    'table' => is_string($table) ? $table : '(invalid)',
                    'status' => 'error',
                    'reason' => $e->getMessage(),
                    'rows_copied' => 0,
                    'ms' => (int) round((microtime(true) - $tableStarted) * 1000),
                ];
                $report['errors'][] = (is_string($table) ? $table : 'table') . ': ' . $e->getMessage();
            }
        }

        $local->statement('SET FOREIGN_KEY_CHECKS=1');

        // Column diff: compare local columns vs server columns (via API).
        $this->fillColumnDiffsViaApi($base, $headers, $local, $tables, $report);
    }

    private function fillColumnDiffsViaApi(string $base, array $headers, $local, array $serverTables, array &$report): void
    {
        $localTables = $this->listTables($local);
        $allTables = array_values(array_unique(array_merge($serverTables, $localTables)));
        sort($allTables);

        foreach ($allTables as $table) {
            if (!is_string($table) || !preg_match('/^[A-Za-z0-9_]+$/', $table)) {
                continue;
            }

            $localCols = $this->listColumns($local, $table);

            $serverCols = [];
            $j = $this->apiRequest('GET', "{$base}/api/db-sync/columns", ['table' => $table], $headers, 60);
            if (!empty($j['ok']) && isset($j['columns']) && is_array($j['columns'])) {
                $serverCols = $j['columns'];
            }

            $missingOnLocal = array_values(array_diff($serverCols, $localCols));
            $missingOnServer = array_values(array_diff($localCols, $serverCols));

            foreach ($missingOnLocal as $col) {
                $report['missing_columns_on_local'][] = ['table' => $table, 'column' => $col];
            }
            foreach ($missingOnServer as $col) {
                $report['missing_columns_on_server'][] = ['table' => $table, 'column' => $col];
            }
        }
    }

    // =========================
    // Server API endpoints
    // =========================

    public function apiPing(Request $request)
    {
        if (!$this->apiTokenOk($request)) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }
        return response()->json(['ok' => true]);
    }

    public function apiTables(Request $request)
    {
        if (!$this->apiTokenOk($request)) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        $conn = DB::connection($this->localConnection);
        $tables = $this->listTables($conn);
        return response()->json(['ok' => true, 'tables' => $tables]);
    }

    public function apiColumns(Request $request)
    {
        if (!$this->apiTokenOk($request)) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        $table = (string) $request->query('table', '');
        if ($table === '' || !preg_match('/^[A-Za-z0-9_]+$/', $table)) {
            return response()->json(['ok' => false, 'message' => 'Invalid table'], 422);
        }

        $conn = DB::connection($this->localConnection);
        $cols = $this->listColumns($conn, $table);
        return response()->json(['ok' => true, 'columns' => $cols]);
    }

    public function apiChunk(Request $request)
    {
        if (!$this->apiTokenOk($request)) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        $table = (string) $request->input('table', '');
        $offset = (int) $request->input('offset', 0);
        $limit = (int) $request->input('limit', 1000);
        $columns = $request->input('columns', []);

        if ($table === '' || !preg_match('/^[A-Za-z0-9_]+$/', $table)) {
            return response()->json(['ok' => false, 'message' => 'Invalid table'], 422);
        }
        if ($limit < 1 || $limit > 5000) {
            return response()->json(['ok' => false, 'message' => 'Invalid limit'], 422);
        }
        if ($offset < 0) {
            return response()->json(['ok' => false, 'message' => 'Invalid offset'], 422);
        }
        if (!is_array($columns) || empty($columns)) {
            return response()->json(['ok' => false, 'message' => 'Columns required'], 422);
        }

        $safeCols = [];
        foreach ($columns as $c) {
            if (is_string($c) && preg_match('/^[A-Za-z0-9_]+$/', $c)) {
                $safeCols[] = $c;
            }
        }
        if (empty($safeCols)) {
            return response()->json(['ok' => false, 'message' => 'Invalid columns'], 422);
        }

        $colSql = implode(', ', array_map(fn ($c) => "`{$c}`", $safeCols));
        $conn = DB::connection($this->localConnection);
        $rows = $conn->select("SELECT {$colSql} FROM `{$table}` LIMIT {$limit} OFFSET {$offset}");

        $out = [];
        foreach ($rows as $r) {
            $out[] = (array) $r;
        }

        return response()->json(['ok' => true, 'rows' => $out]);
    }

    public function apiRunSql(Request $request)
    {
        if (!$this->apiTokenOk($request)) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        $sql = trim((string) $request->input('sql', ''));
        if ($sql === '') {
            return response()->json(['ok' => false, 'message' => 'SQL is empty'], 422);
        }

        $upper = Str::upper(ltrim($sql));
        $allowed = Str::startsWith($upper, 'ALTER TABLE')
            || Str::startsWith($upper, 'CREATE TABLE')
            || Str::startsWith($upper, 'CREATE INDEX')
            || Str::startsWith($upper, 'DROP INDEX');

        if (!$allowed) {
            return response()->json(['ok' => false, 'message' => 'Only ALTER TABLE / CREATE TABLE / CREATE INDEX / DROP INDEX are allowed'], 422);
        }

        try {
            $conn = DB::connection($this->localConnection);
            $conn->unprepared($sql);
            return response()->json(['ok' => true, 'message' => 'Query executed']);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function apiTokenOk(Request $request): bool
    {
        $token = (string) $request->header($this->tokenHeader, '');
        return hash_equals($this->sharedToken, $token);
    }

    /**
     * Minimal HTTP client for Laravel 6 (no Http facade).
     * Returns decoded JSON array, plus debug keys: _status, _raw.
     */
    private function apiRequest(string $method, string $url, array $data, array $headers, int $timeoutSeconds): array
    {
        $method = strtoupper($method);

        $ch = curl_init();
        if ($ch === false) {
            throw new \RuntimeException('cURL not available.');
        }

        $finalUrl = $url;
        if ($method === 'GET' && !empty($data)) {
            $qs = http_build_query($data);
            $finalUrl .= (strpos($finalUrl, '?') === false ? '?' : '&') . $qs;
        }

        $hdrs = [];
        foreach ($headers as $k => $v) {
            $hdrs[] = $k . ': ' . $v;
        }

        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeoutSeconds);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, min(10, $timeoutSeconds));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $hdrs);

        if ($method === 'POST') {
            $payload = json_encode($data);
            $hdrs[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $hdrs);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload === false ? '{}' : $payload);
        }

        $raw = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($raw === false) {
            return ['ok' => false, 'message' => $err ?: 'Request failed', '_status' => $status, '_raw' => ''];
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return ['ok' => false, 'message' => 'Invalid JSON response', '_status' => $status, '_raw' => (string) $raw];
        }

        $decoded['_status'] = $status;
        $decoded['_raw'] = (string) $raw;
        return $decoded;
    }

    private function listTables($conn): array
    {
        $dbName = (string) $conn->getDatabaseName();
        $rows = $conn->select(
            'SELECT table_name AS t FROM information_schema.tables WHERE table_schema = ? AND table_type = "BASE TABLE" ORDER BY table_name',
            [$dbName]
        );

        return array_values(array_map(fn ($r) => (string) $r->t, $rows));
    }

    private function listColumns($conn, string $table): array
    {
        $dbName = (string) $conn->getDatabaseName();
        $rows = $conn->select(
            'SELECT column_name AS c FROM information_schema.columns WHERE table_schema = ? AND table_name = ? ORDER BY ordinal_position',
            [$dbName, $table]
        );

        return array_values(array_map(fn ($r) => (string) $r->c, $rows));
    }

    private function copyTableData($server, $local, string $table, array $serverCols): int
    {
        if (empty($serverCols)) {
            return 0;
        }

        $offset = 0;
        $limit = 1000;
        $total = 0;

        // Only insert columns that also exist on local table (avoid insert errors).
        $localCols = $this->listColumns($local, $table);
        $commonCols = array_values(array_intersect($serverCols, $localCols));
        if (empty($commonCols)) {
            return 0;
        }

        $colSql = implode(', ', array_map(fn ($c) => "`{$c}`", $commonCols));

        while (true) {
            $rows = $server->select("SELECT {$colSql} FROM `{$table}` LIMIT {$limit} OFFSET {$offset}");
            if (!$rows) {
                break;
            }

            $batch = [];
            foreach ($rows as $r) {
                $arr = (array) $r;
                // Ensure only common columns are passed in correct keys.
                $batch[] = array_intersect_key($arr, array_flip($commonCols));
            }

            // Insert in smaller chunks.
            foreach (array_chunk($batch, 500) as $ins) {
                $local->table($table)->insert($ins);
                $total += count($ins);
            }

            $offset += $limit;
        }

        return $total;
    }

    private function fillColumnDiffs($server, $local, array &$report): void
    {
        $serverTables = $this->listTables($server);
        $localTables = $this->listTables($local);
        $allTables = array_values(array_unique(array_merge($serverTables, $localTables)));
        sort($allTables);

        foreach ($allTables as $table) {
            if (!preg_match('/^[A-Za-z0-9_]+$/', $table)) {
                continue;
            }

            $serverCols = $this->listColumns($server, $table);
            $localCols = $this->listColumns($local, $table);

            $missingOnLocal = array_values(array_diff($serverCols, $localCols));
            $missingOnServer = array_values(array_diff($localCols, $serverCols));

            foreach ($missingOnLocal as $col) {
                $report['missing_columns_on_local'][] = ['table' => $table, 'column' => $col];
            }
            foreach ($missingOnServer as $col) {
                $report['missing_columns_on_server'][] = ['table' => $table, 'column' => $col];
            }
        }
    }
}

