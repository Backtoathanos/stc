<?php
session_start();
require_once __DIR__ . '/../includes/agent_session_defaults.php';
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/db.php";
require_once "../../MCU/stc_req_item_communicate.php";
stc_req_comm_handle_request($con, 'subagent');
