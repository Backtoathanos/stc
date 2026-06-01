<?php
/**
 * Ensure school portal session keys exist (PHP 8+ avoids undefined array key warnings).
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$stcSchoolSessionDefaults = [
    'stc_school_user_id' => '',
    'stc_school_user_name' => '',
    'stc_school_user_for' => 0,
    'stc_school_teacher_id' => '',
];

foreach ($stcSchoolSessionDefaults as $sessionKey => $defaultValue) {
    if (!array_key_exists($sessionKey, $_SESSION) || $_SESSION[$sessionKey] === null) {
        $_SESSION[$sessionKey] = $defaultValue;
    }
}

if (!isset($agent_name)) {
    $agent_name = [];
}
if (!isset($sales)) {
    $sales = [];
}
