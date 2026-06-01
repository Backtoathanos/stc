<?php
require_once __DIR__ . '/agent_session_defaults.php';

if (empty($_SESSION['stc_agent_sub_id'])) {
    header('Location: index.html');
    exit;
}
