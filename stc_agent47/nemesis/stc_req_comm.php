<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
if(empty($_SESSION['stc_agent_id']) && !empty($_COOKIE['stc_agent_remember'])){
    $_SESSION['stc_agent_id'] = $_COOKIE['stc_agent_remember'];
    if(!empty($_COOKIE['stc_agent_name'])) $_SESSION['stc_agent_name'] = $_COOKIE['stc_agent_name'];
    if(!empty($_COOKIE['stc_agent_role'])) $_SESSION['stc_agent_role'] = $_COOKIE['stc_agent_role'];
}
include "../../MCU/db.php";
require_once "../../MCU/stc_req_item_communicate.php";
stc_req_comm_handle_request($con, 'agent');
