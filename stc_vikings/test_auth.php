<?php
/**
 * Test file for the new authentication system
 * This file demonstrates how the session-cookie hybrid authentication works
 */

// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication
if (STCAuthHelper::checkAuth()) {
    echo "<h2>Authentication Test - SUCCESS</h2>";
    echo "<p>You are successfully authenticated!</p>";
    echo "<p><strong>User ID:</strong> " . STCAuthHelper::getCurrentUserId() . "</p>";
    echo "<p><strong>User Name:</strong> " . STCAuthHelper::getCurrentUserName() . "</p>";
    echo "<p><strong>User Role:</strong> " . STCAuthHelper::getCurrentUserRole() . "</p>";
    
    echo "<h3>Session Data:</h3>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    
    echo "<h3>Cookie Data:</h3>";
    echo "<pre>";
    if (isset($_COOKIE['stc_auth_data'])) {
        echo "Cookie exists: " . substr($_COOKIE['stc_auth_data'], 0, 50) . "...";
    } else {
        echo "No authentication cookie found.";
    }
    echo "</pre>";
    
    echo "<p><a href='kattegat/logout.php'>Logout</a></p>";
} else {
    echo "<h2>Authentication Test - FAILED</h2>";
    echo "<p>You are not authenticated. Please <a href='index.html'>login</a> first.</p>";
}
?>
