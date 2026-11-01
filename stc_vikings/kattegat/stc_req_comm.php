<?php
require_once __DIR__ . '/auth_helper.php';
STCAuthHelper::checkAuth();
include "../../MCU/db.php";
require_once "../../MCU/stc_req_item_communicate.php";
stc_req_comm_handle_request($con, 'vikings');
