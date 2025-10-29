<?php
// Include authentication helper
require_once 'auth_helper.php';

// Use the new logout method that clears both session and cookies
STCAuthHelper::logout();
?>