<?php
/**
 * STC Vikings Authentication Helper
 * Handles session-cookie hybrid authentication system
 * Extends session lifetime using cookies for 30 days
 */

class STCAuthHelper {
    
    private static $cookie_name = 'stc_auth_data';
    private static $cookie_expiry = 2592000; // 30 days in seconds
    
    /**
     * Initialize authentication check
     * This method should be called at the beginning of every protected page
     */
    public static function checkAuth() {
        session_start();
        
        // Check if session exists
        if (isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"] > 0)) {
            // Session exists, store in cookies for future use
            self::storeAuthInCookie();
            return true;
        }
        // Session doesn't exist, check cookies
        elseif (self::checkAuthFromCookie()) {
            // Cookie exists, restore session from cookie
            self::restoreSessionFromCookie();
            return true;
        }
        else {
            // Neither session nor cookie exists, redirect to login
            self::redirectToLogin();
            return false;
        }
    }
    
    /**
     * Store authentication data in cookie
     */
    public static function storeAuthInCookie() {
        if (isset($_SESSION["stc_empl_id"]) && isset($_SESSION["stc_empl_name"]) && isset($_SESSION["stc_empl_role"])) {
            $auth_data = array(
                'stc_empl_id' => $_SESSION["stc_empl_id"],
                'stc_empl_name' => $_SESSION["stc_empl_name"],
                'stc_empl_role' => $_SESSION["stc_empl_role"]
            );
            
            // Encrypt the data before storing in cookie
            $encrypted_data = self::encryptData(json_encode($auth_data));
            
            // Set cookie with 30 days expiry
            setcookie(self::$cookie_name, $encrypted_data, time() + self::$cookie_expiry, '/', '', false, true);
        }
    }
    
    /**
     * Check if authentication data exists in cookie
     */
    private static function checkAuthFromCookie() {
        return isset($_COOKIE[self::$cookie_name]) && !empty($_COOKIE[self::$cookie_name]);
    }
    
    /**
     * Restore session from cookie data
     */
    private static function restoreSessionFromCookie() {
        if (isset($_COOKIE[self::$cookie_name])) {
            try {
                // Decrypt the cookie data
                $decrypted_data = self::decryptData($_COOKIE[self::$cookie_name]);
                $auth_data = json_decode($decrypted_data, true);
                
                if ($auth_data && isset($auth_data['stc_empl_id']) && isset($auth_data['stc_empl_name']) && isset($auth_data['stc_empl_role'])) {
                    // Restore session variables
                    $_SESSION["stc_empl_id"] = $auth_data['stc_empl_id'];
                    $_SESSION["stc_empl_name"] = $auth_data['stc_empl_name'];
                    $_SESSION["stc_empl_role"] = $auth_data['stc_empl_role'];
                    
                    // Update cookie with fresh expiry
                    self::storeAuthInCookie();
                    return true;
                }
            } catch (Exception $e) {
                // Invalid cookie data, clear it
                self::clearAuthCookie();
            }
        }
        return false;
    }
    
    /**
     * Clear authentication cookie
     */
    public static function clearAuthCookie() {
        if (isset($_COOKIE[self::$cookie_name])) {
            setcookie(self::$cookie_name, '', time() - 3600, '/', '', false, true);
            unset($_COOKIE[self::$cookie_name]);
        }
    }
    
    /**
     * Logout user - clear both session and cookie
     */
    public static function logout() {
        // Clear session
        session_destroy();
        
        // Clear cookie
        self::clearAuthCookie();
        
        // Redirect to login
        self::redirectToLogin();
    }
    
    /**
     * Redirect to login page
     */
    private static function redirectToLogin() {
        header("Location: index.html");
        exit();
    }
    
    /**
     * Simple encryption for cookie data
     */
    private static function encryptData($data) {
        $key = 'stc_vikings_auth_key_2024'; // Change this to a more secure key
        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }
    
    /**
     * Simple decryption for cookie data
     */
    private static function decryptData($data) {
        $key = 'stc_vikings_auth_key_2024'; // Same key as encryption
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
    }
    
    /**
     * Get current user ID
     */
    public static function getCurrentUserId() {
        return isset($_SESSION["stc_empl_id"]) ? $_SESSION["stc_empl_id"] : null;
    }
    
    /**
     * Get current user name
     */
    public static function getCurrentUserName() {
        return isset($_SESSION["stc_empl_name"]) ? $_SESSION["stc_empl_name"] : null;
    }
    
    /**
     * Get current user role
     */
    public static function getCurrentUserRole() {
        return isset($_SESSION["stc_empl_role"]) ? $_SESSION["stc_empl_role"] : null;
    }
    
    /**
     * Check if user has specific role or higher
     */
    public static function hasRole($min_role) {
        $user_role = self::getCurrentUserRole();
        return $user_role !== null && $user_role >= $min_role;
    }
}
?>
