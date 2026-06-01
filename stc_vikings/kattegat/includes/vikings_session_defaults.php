<?php
/**
 * Ensure Vikings portal session keys exist (PHP 8+ avoids undefined array key warnings).
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$stcVikingsSessionDefaults = [
    'stc_empl_id' => '',
    'stc_empl_name' => '',
    'stc_empl_role' => '',
    'stc_agent_id' => '',
    'stc_direct_challan_sess' => [],
    'stc_sale_order_sess' => [],
    'stc_merchant_invoice_sort' => [],
    'stc_purchase_order_sess' => [],
    'stc_accepted_ag_require' => [],
    'stc_cust_requist_cart' => [],
];

foreach ($stcVikingsSessionDefaults as $sessionKey => $defaultValue) {
    if (!array_key_exists($sessionKey, $_SESSION) || $_SESSION[$sessionKey] === null) {
        $_SESSION[$sessionKey] = $defaultValue;
    }
}

if ($_SESSION['stc_agent_id'] === '' && $_SESSION['stc_empl_id'] !== '') {
    $_SESSION['stc_agent_id'] = $_SESSION['stc_empl_id'];
}
