<?php
/**
 * Local AI data assistant API — Ollama + auto SQL execute.
 */
declare(strict_types=1);

ob_start();

require_once __DIR__ . '/auth_helper.php';

header('Content-Type: application/json; charset=UTF-8');

if (!STCAuthHelper::resumeSessionForApi()) {
	ai_json_fail('Session expired. Please sign in again.');
}

$config = require __DIR__ . '/ai_local_config.php';

require_once __DIR__ . '/../../MCU/db.php';

if (!isset($con) || !$con instanceof mysqli) {
	ai_json_fail('Database unavailable.');
}

mysqli_set_charset($con, 'utf8mb4');

function ai_send_json(array $payload): void {
	while (ob_get_level() > 0) {
		ob_end_clean();
	}
	echo json_encode($payload, JSON_UNESCAPED_UNICODE);
	exit;
}

function ai_json_fail(string $message, array $extra = []): void {
	ai_send_json(array_merge(['success' => false, 'message' => $message], $extra));
}

function ai_json_ok(array $data = []): void {
	ai_send_json(array_merge(['success' => true], $data));
}

function ai_resolve_allowed_tables(mysqli $con, array $config): array {
	if (!empty($config['allow_all_stc_tables'])) {
		$list = [];
		if ($rs = mysqli_query($con, "SHOW TABLES LIKE 'stc\\_%'")) {
			while ($row = mysqli_fetch_array($rs)) {
				$list[] = (string) $row[0];
			}
			mysqli_free_result($rs);
		}
		sort($list);
		return $list;
	}
	return array_values(array_filter(array_map('strval', $config['allowed_tables'] ?? [])));
}

function ai_fetch_schema(mysqli $con, array $allowed): array {
	if ($allowed === []) {
		return [];
	}

	$escaped = array_map(function (string $t) use ($con): string {
		return "'" . mysqli_real_escape_string($con, $t) . "'";
	}, $allowed);

	$sql = '
		SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_KEY, EXTRA
		FROM INFORMATION_SCHEMA.COLUMNS
		WHERE TABLE_SCHEMA = DATABASE()
		  AND TABLE_NAME IN (' . implode(',', $escaped) . ')
		ORDER BY TABLE_NAME, ORDINAL_POSITION
	';

	$schema = [];
	if ($rs = mysqli_query($con, $sql)) {
		while ($row = mysqli_fetch_assoc($rs)) {
			$table = (string) $row['TABLE_NAME'];
			if (!isset($schema[$table])) {
				$schema[$table] = [];
			}
			$schema[$table][] = [
				'column'   => (string) $row['COLUMN_NAME'],
				'type'     => (string) $row['DATA_TYPE'],
				'nullable' => (string) $row['IS_NULLABLE'],
				'key'      => (string) $row['COLUMN_KEY'],
				'extra'    => (string) $row['EXTRA'],
			];
		}
		mysqli_free_result($rs);
	}

	return $schema;
}

function ai_schema_text(array $schema): string {
	if ($schema === []) {
		return 'No schema available.';
	}

	$lines = [];
	foreach ($schema as $table => $cols) {
		$parts = [];
		foreach ($cols as $c) {
			$bit = $c['column'] . ' ' . $c['type'];
			if ($c['key'] === 'PRI') {
				$bit .= ' PK';
			}
			$parts[] = $bit;
		}
		$lines[] = $table . ': ' . implode(', ', $parts);
	}
	return implode("\n", $lines);
}

/**
 * HTTP helper — works without curl (XAMPP Apache often has curl DLL broken).
 *
 * @return array{ok:bool, body?:string, code?:int, error?:string}
 */
function ai_http_request(string $url, string $method = 'GET', ?string $body = null, int $timeout = 30): array {
	if (function_exists('curl_init')) {
		$ch = curl_init($url);
		$opts = [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => $timeout,
			CURLOPT_CONNECTTIMEOUT => min(10, $timeout),
		];
		if (strtoupper($method) === 'POST') {
			$opts[CURLOPT_POST] = true;
			$opts[CURLOPT_POSTFIELDS] = $body ?? '';
			$opts[CURLOPT_HTTPHEADER] = ['Content-Type: application/json'];
		}
		curl_setopt_array($ch, $opts);
		$response = curl_exec($ch);
		$errno = curl_errno($ch);
		$err = curl_error($ch);
		$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($errno !== 0) {
			return ['ok' => false, 'error' => $err];
		}
		return ['ok' => true, 'body' => (string) $response, 'code' => $code];
	}

	$headers = "Accept: application/json\r\n";
	if (strtoupper($method) === 'POST') {
		$headers .= "Content-Type: application/json\r\n";
	}
	$ctx = stream_context_create([
		'http' => [
			'method'        => strtoupper($method),
			'header'        => $headers,
			'content'       => $body ?? '',
			'timeout'       => $timeout,
			'ignore_errors' => true,
		],
	]);
	$response = @file_get_contents($url, false, $ctx);
	if ($response === false) {
		$err = error_get_last();
		return ['ok' => false, 'error' => $err['message'] ?? 'HTTP request failed'];
	}
	$code = 200;
	if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $m)) {
		$code = (int) $m[1];
	}
	return ['ok' => true, 'body' => $response, 'code' => $code];
}

function ai_ollama_request(array $config, array $messages): array {
	$url = rtrim((string) $config['ollama_url'], '/') . '/api/chat';
	$payload = json_encode([
		'model'    => (string) $config['model'],
		'messages' => $messages,
		'stream'   => false,
		'options'  => ['temperature' => 0.1],
	]);

	$res = ai_http_request($url, 'POST', $payload, (int) ($config['timeout_sec'] ?? 120));
	if (!$res['ok']) {
		return ['ok' => false, 'error' => 'Ollama not reachable: ' . ($res['error'] ?? 'unknown') . '. Start Ollama app, then run: ollama pull ' . $config['model']];
	}
	$code = (int) ($res['code'] ?? 0);
	$body = (string) ($res['body'] ?? '');
	if ($code < 200 || $code >= 300) {
		return ['ok' => false, 'error' => 'Ollama HTTP ' . $code . ': ' . $body];
	}

	$data = json_decode($body, true);
	if (!is_array($data) || !isset($data['message']['content'])) {
		return ['ok' => false, 'error' => 'Invalid response from Ollama.'];
	}

	return ['ok' => true, 'content' => (string) $data['message']['content']];
}

function ai_extract_sql_blocks(string $text): array {
	$blocks = [];
	if (preg_match_all('/```(?:sql)?\s*([\s\S]*?)```/i', $text, $m)) {
		foreach ($m[1] as $block) {
			$block = trim($block);
			if ($block !== '') {
				$blocks[] = $block;
			}
		}
	}
	return $blocks;
}

function ai_sql_type(string $sql): string {
	$s = ltrim($sql);
	if (preg_match('/^SELECT\b/i', $s)) {
		return 'SELECT';
	}
	if (preg_match('/^INSERT\b/i', $s)) {
		return 'INSERT';
	}
	if (preg_match('/^UPDATE\b/i', $s)) {
		return 'UPDATE';
	}
	if (preg_match('/^ALTER\b/i', $s)) {
		return 'ALTER';
	}
	if (preg_match('/^DELETE\b/i', $s)) {
		return 'DELETE';
	}
	return 'OTHER';
}

function ai_referenced_tables(string $sql, array $allowed): array {
	$found = [];
	foreach ($allowed as $table) {
		if (preg_match('/\b`?' . preg_quote($table, '/') . '`?\b/i', $sql)) {
			$found[] = $table;
		}
	}
	return array_values(array_unique($found));
}

function ai_single_statement(string $sql): array {
	$trim = trim($sql);
	if (preg_match('/;\s*\S/', $trim)) {
		return [false, 'Multiple SQL statements in one block are not allowed.'];
	}
	return [true, rtrim($trim, ';')];
}

function ai_validate_select(string $sql, array $allowed): array {
	[$one, $reason] = ai_single_statement($sql);
	if (!$one) {
		return [false, $reason];
	}
	if (!preg_match('/^SELECT\b/i', $sql)) {
		return [false, 'Not a SELECT query.'];
	}
	if (preg_match('/\b(INSERT|UPDATE|DELETE|DROP|ALTER|TRUNCATE|CREATE)\b/i', $sql)) {
		return [false, 'SELECT query contains forbidden keywords.'];
	}
	if (ai_referenced_tables($sql, $allowed) === []) {
		return [false, 'SELECT must use an allowed stc_* table.'];
	}
	return [true, ''];
}

function ai_validate_write(string $sql, array $allowed): array {
	[$one, $reason] = ai_single_statement($sql);
	if (!$one) {
		return [false, $reason];
	}
	$type = ai_sql_type($sql);
	if (!in_array($type, ['INSERT', 'UPDATE'], true)) {
		return [false, 'Only INSERT or UPDATE allowed.'];
	}
	if (ai_referenced_tables($sql, $allowed) === []) {
		return [false, 'Query must reference an allowed stc_* table.'];
	}
	if (preg_match('/\b(DELETE|DROP|TRUNCATE)\b/i', $sql)) {
		return [false, 'DELETE/DROP not allowed.'];
	}
	return [true, ''];
}

function ai_validate_alter(string $sql, array $allowed): array {
	[$one, $reason] = ai_single_statement($sql);
	if (!$one) {
		return [false, $reason];
	}
	if (!preg_match('/^ALTER\s+TABLE\b/i', $sql)) {
		return [false, 'Only ALTER TABLE allowed.'];
	}
	if (!preg_match('/\b(ADD\s+COLUMN|DROP\s+COLUMN|MODIFY\s+COLUMN|CHANGE\s+COLUMN)\b/i', $sql)) {
		return [false, 'Only ADD COLUMN, DROP COLUMN, MODIFY COLUMN, CHANGE COLUMN allowed.'];
	}
	if (preg_match('/\b(DROP\s+TABLE|TRUNCATE|DELETE)\b/i', $sql)) {
		return [false, 'Dangerous operation blocked.'];
	}
	if (ai_referenced_tables($sql, $allowed) === []) {
		return [false, 'ALTER must target an allowed stc_* table.'];
	}
	return [true, ''];
}

function ai_run_select(mysqli $con, string $sql, int $maxRows): array {
	$sql = rtrim(trim($sql), ';');
	$limited = $sql;
	if (!preg_match('/\bLIMIT\s+\d+/i', $limited)) {
		$limited .= ' LIMIT ' . $maxRows;
	}

	$rows = [];
	$cols = [];
	if (!$rs = mysqli_query($con, $limited)) {
		return ['ok' => false, 'error' => mysqli_error($con)];
	}
	while ($row = mysqli_fetch_assoc($rs)) {
		if ($cols === []) {
			$cols = array_keys($row);
		}
		$rows[] = $row;
		if (count($rows) >= $maxRows) {
			break;
		}
	}
	mysqli_free_result($rs);

	return ['ok' => true, 'columns' => $cols, 'rows' => $rows, 'sql' => $limited, 'row_count' => count($rows)];
}

function ai_run_write(mysqli $con, string $sql): array {
	if (!mysqli_query($con, $sql)) {
		return ['ok' => false, 'error' => mysqli_error($con)];
	}
	return ['ok' => true, 'affected' => mysqli_affected_rows($con), 'sql' => $sql];
}

function ai_auto_execute_blocks(mysqli $con, array $config, array $allowed, array $sqlBlocks): array {
	$results = [];
	$auto = !empty($config['auto_execute_sql']);

	foreach ($sqlBlocks as $sql) {
		$type = ai_sql_type($sql);
		$item = ['sql' => $sql, 'type' => $type, 'executed' => false];

		if (!$auto) {
			$item['message'] = 'Auto-execute off — SQL suggested only.';
			$results[] = $item;
			continue;
		}

		if ($type === 'SELECT') {
			[$ok, $reason] = ai_validate_select($sql, $allowed);
			if (!$ok) {
				$item['error'] = $reason;
				$results[] = $item;
				continue;
			}
			$max = (int) ($config['max_select_rows'] ?? 200);
			$run = ai_run_select($con, $sql, $max);
			if (!$run['ok']) {
				$item['error'] = $run['error'];
			} else {
				$item['executed'] = true;
				$item['columns'] = $run['columns'];
				$item['rows'] = $run['rows'];
				$item['row_count'] = $run['row_count'];
				$item['message'] = $run['row_count'] . ' record(s) found.';
			}
			$results[] = $item;
			continue;
		}

		if (in_array($type, ['INSERT', 'UPDATE'], true)) {
			if (empty($config['allow_write_sql'])) {
				$item['message'] = 'Write disabled in config.';
				$results[] = $item;
				continue;
			}
			[$ok, $reason] = ai_validate_write($sql, $allowed);
			if (!$ok) {
				$item['error'] = $reason;
				$results[] = $item;
				continue;
			}
			$run = ai_run_write($con, $sql);
			if (!$run['ok']) {
				$item['error'] = $run['error'];
			} else {
				$item['executed'] = true;
				$item['affected'] = $run['affected'];
				$item['message'] = 'Done. ' . $run['affected'] . ' row(s) affected.';
			}
			$results[] = $item;
			continue;
		}

		if ($type === 'ALTER') {
			if (empty($config['allow_alter_table'])) {
				$item['message'] = 'ALTER disabled in config.';
				$results[] = $item;
				continue;
			}
			[$ok, $reason] = ai_validate_alter($sql, $allowed);
			if (!$ok) {
				$item['error'] = $reason;
				$results[] = $item;
				continue;
			}
			$run = ai_run_write($con, $sql);
			if (!$run['ok']) {
				$item['error'] = $run['error'];
			} else {
				$item['executed'] = true;
				$item['message'] = 'Table structure updated successfully.';
			}
			$results[] = $item;
			continue;
		}

		$item['message'] = 'This SQL type is not auto-executed.';
		$results[] = $item;
	}

	return $results;
}

function ai_system_prompt(array $schema, array $allowed): string {
	$schemaText = ai_schema_text($schema);
	$tableList = implode(', ', array_slice($allowed, 0, 80));
	if (count($allowed) > 80) {
		$tableList .= ' ... (' . count($allowed) . ' tables total)';
	}

	return <<<PROMPT
You are a MySQL database assistant for STC Associate ERP (local dev). Database: stc_associate_go.

Available tables (stc_*): {$tableList}

Current schema (columns):
{$schemaText}

IMPORTANT — SQL auto-execution:
- Every action MUST include exactly one SQL statement inside a ```sql code block.
- SELECT → records will be fetched and shown to the user automatically.
- UPDATE / INSERT → will run automatically on local DB.
- ALTER TABLE ADD COLUMN / DROP COLUMN / MODIFY COLUMN → will run automatically.
- User speaks Hindi or English — reply in same language.

When user asks:
- "records dikhao" / "show data" → write SELECT with LIMIT 50
- "column add karo" → ALTER TABLE ... ADD COLUMN ...
- "column remove/hatao" → ALTER TABLE ... DROP COLUMN ...
- "update karo" → UPDATE with clear WHERE clause
- "naya record add" → INSERT

Rules:
1. Use exact table and column names from schema.
2. Always use LIMIT on SELECT (max 50 unless user asks more).
3. Never DROP TABLE, TRUNCATE, or DELETE rows.
4. Before UPDATE/ALTER, briefly explain what will change (1-2 lines), then SQL block.
5. One SQL block per response unless user asks for multiple unrelated actions.
PROMPT;
}

/* ------------------------------------------------------------------ */
/* PING                                                               */
/* ------------------------------------------------------------------ */
if (isset($_POST['ai_ping'])) {
	$url = rtrim((string) $config['ollama_url'], '/') . '/api/tags';
	$res = ai_http_request($url, 'GET', null, 5);

	if (!$res['ok']) {
		ai_json_fail('Ollama is not running. Start Ollama app, then run: ollama pull ' . $config['model']);
	}

	$body = (string) ($res['body'] ?? '');
	$tags = json_decode($body, true);
	$models = [];
	if (is_array($tags['models'] ?? null)) {
		foreach ($tags['models'] as $m) {
			if (!empty($m['name'])) {
				$models[] = (string) $m['name'];
			}
		}
	}

	$wanted = (string) $config['model'];
	$hasModel = false;
	foreach ($models as $name) {
		if ($name === $wanted || strpos($name, $wanted . ':') === 0) {
			$hasModel = true;
			break;
		}
	}

	$allowed = ai_resolve_allowed_tables($con, $config);

	ai_json_ok([
		'ollama_url'       => $config['ollama_url'],
		'model'            => $wanted,
		'models'           => $models,
		'model_ready'      => $hasModel,
		'auto_execute'     => (bool) ($config['auto_execute_sql'] ?? false),
		'table_count'      => count($allowed),
	]);
}

/* ------------------------------------------------------------------ */
/* CHAT + AUTO EXECUTE                                                */
/* ------------------------------------------------------------------ */
if (isset($_POST['ai_chat'])) {
	$message = trim((string) ($_POST['message'] ?? ''));
	if ($message === '') {
		ai_json_fail('Message is required.');
	}

	$historyRaw = (string) ($_POST['history'] ?? '[]');
	$history = json_decode($historyRaw, true);
	if (!is_array($history)) {
		$history = [];
	}

	$allowed = ai_resolve_allowed_tables($con, $config);
	$schema = ai_fetch_schema($con, $allowed);

	$messages = [
		['role' => 'system', 'content' => ai_system_prompt($schema, $allowed)],
	];

	foreach ($history as $item) {
		if (!is_array($item)) {
			continue;
		}
		$role = (string) ($item['role'] ?? '');
		$content = trim((string) ($item['content'] ?? ''));
		if ($content === '' || !in_array($role, ['user', 'assistant'], true)) {
			continue;
		}
		$messages[] = ['role' => $role, 'content' => $content];
	}

	$messages[] = ['role' => 'user', 'content' => $message];

	$result = ai_ollama_request($config, $messages);
	if (!$result['ok']) {
		ai_json_fail($result['error']);
	}

	$content = $result['content'];
	$sqlBlocks = ai_extract_sql_blocks($content);
	$execResults = ai_auto_execute_blocks($con, $config, $allowed, $sqlBlocks);

	// Refresh schema after ALTER so next message has updated columns
	$schemaAfter = ai_fetch_schema($con, $allowed);

	ai_json_ok([
		'reply'         => $content,
		'sql_blocks'    => $sqlBlocks,
		'exec_results'  => $execResults,
		'schema_updated'=> $schemaAfter !== $schema,
	]);
}

/* ------------------------------------------------------------------ */
/* MANUAL RUN (optional)                                              */
/* ------------------------------------------------------------------ */
if (isset($_POST['ai_run_select'])) {
	$sql = trim((string) ($_POST['sql'] ?? ''));
	if ($sql === '') {
		ai_json_fail('SQL is required.');
	}
	$allowed = ai_resolve_allowed_tables($con, $config);
	[$ok, $reason] = ai_validate_select($sql, $allowed);
	if (!$ok) {
		ai_json_fail($reason);
	}
	$max = (int) ($config['max_select_rows'] ?? 200);
	$run = ai_run_select($con, $sql, $max);
	if (!$run['ok']) {
		ai_json_fail($run['error']);
	}
	ai_json_ok(['columns' => $run['columns'], 'rows' => $run['rows'], 'sql' => $run['sql']]);
}

if (isset($_POST['ai_export_sql'])) {
	$sql = trim((string) ($_POST['sql'] ?? ''));
	$label = preg_replace('/[^a-zA-Z0-9_-]+/', '_', (string) ($_POST['label'] ?? 'export'));
	$label = substr($label, 0, 40) ?: 'export';
	if ($sql === '') {
		ai_json_fail('SQL is required.');
	}
	$dir = (string) ($config['export_dir'] ?? '');
	if ($dir !== '' && !is_dir($dir)) {
		@mkdir($dir, 0755, true);
	}
	$filename = date('Y-m-d_His') . '_' . $label . '.sql';
	$path = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $filename;
	$header = "-- STC local AI export\n-- " . date('Y-m-d H:i:s') . "\n\n";
	if (@file_put_contents($path, $header . $sql . "\n") === false) {
		ai_json_fail('Could not write export file.');
	}
	ai_json_ok(['path' => 'ai_exports/' . $filename]);
}

ai_json_fail('Unknown action.');
