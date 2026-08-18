<?php
/**
 * Microsoft Graph defaults for the M365 analytics dashboard.
 * Tenant / client / secret are stored in ms365_data/secrets.json (not in git).
 */
return [
	'tenant_id' => '',
	'client_id' => '',
	'client_secret' => '',
	'graph_base' => 'https://graph.microsoft.com/v1.0',
	'token_url' => 'https://login.microsoftonline.com/{tenant}/oauth2/v2.0/token',
	'cache_ttl_seconds' => 900,
	'token_skew_seconds' => 120,
	'http_timeout' => 60,
];
