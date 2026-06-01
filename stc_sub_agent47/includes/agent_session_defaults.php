<?php
/**
 * Ensure agent session keys exist (PHP 8+ avoids undefined array key warnings).
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$stcAgentSessionDefaults = [
    'stc_agent_sub_id' => '',
    'stc_agent_sub_name' => '',
    'stc_agent_sub_category' => '',
    'stc_agent_sub_cont' => '',
    'stc_agent_sub_image' => '',
];

foreach ($stcAgentSessionDefaults as $sessionKey => $defaultValue) {
    if (!array_key_exists($sessionKey, $_SESSION) || $_SESSION[$sessionKey] === null) {
        $_SESSION[$sessionKey] = $defaultValue;
    }
}
