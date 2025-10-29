# STC Vikings Authentication System

## Overview
This document describes the new session-cookie hybrid authentication system implemented for the STC Vikings project. The system extends session lifetime from 24 minutes to 30 days by using encrypted cookies as a backup when sessions expire.

## Problem Solved
- **Issue**: PHP sessions expire after 24 minutes, requiring users to re-login frequently
- **Solution**: Implemented a hybrid system that uses cookies to restore sessions automatically

## How It Works

### 1. Authentication Flow
```
User visits protected page
    ↓
Check if session exists
    ↓
Session exists? → Store in cookie (30 days) → Allow access
    ↓
Session doesn't exist? → Check cookie
    ↓
Cookie exists? → Restore session from cookie → Allow access
    ↓
No cookie? → Redirect to login
```

### 2. Key Components

#### `kattegat/auth_helper.php`
The main authentication helper class that handles:
- Session validation
- Cookie management
- Data encryption/decryption
- Automatic session restoration

#### Updated Files
All PHP files in `stc_vikings/` have been updated to use the new system:
- Replaced old session checks with `STCAuthHelper::checkAuth()`
- Automatic cookie storage on successful login
- Seamless session restoration from cookies

## Usage

### For Protected Pages
```php
<?php
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth();
?>
```

### For Login Process
```php
// After successful login, store in cookie
$_SESSION['stc_empl_id'] = $user_id;
$_SESSION['stc_empl_name'] = $user_name;
$_SESSION['stc_empl_role'] = $user_role;

// Store authentication data in cookie for 30 days
STCAuthHelper::storeAuthInCookie();
```

### For Logout
```php
// Use the logout method that clears both session and cookies
STCAuthHelper::logout();
```

## Security Features

### 1. Data Encryption
- Cookie data is encrypted using AES-256-CBC
- Unique encryption key for each installation
- Base64 encoding for safe transmission

### 2. Cookie Security
- HttpOnly flag prevents JavaScript access
- Secure flag for HTTPS environments
- 30-day expiration period
- Automatic cleanup on logout

### 3. Session Management
- Automatic session restoration
- Fresh cookie updates on each access
- Proper cleanup on logout

## API Reference

### STCAuthHelper Class Methods

#### `checkAuth()`
Main authentication method. Call this at the beginning of every protected page.
- Returns: `true` if authenticated, `false` otherwise
- Automatically redirects to login if not authenticated

#### `storeAuthInCookie()`
Stores current session data in encrypted cookie.
- Call after successful login
- Updates cookie with fresh expiry

#### `logout()`
Clears both session and cookie data.
- Destroys current session
- Removes authentication cookie
- Redirects to login page

#### `getCurrentUserId()`
Returns current user ID from session.

#### `getCurrentUserName()`
Returns current user name from session.

#### `getCurrentUserRole()`
Returns current user role from session.

#### `hasRole($min_role)`
Checks if user has specific role or higher.
- Parameter: `$min_role` - minimum required role level
- Returns: `true` if user has sufficient role

## Configuration

### Cookie Settings
- **Name**: `stc_auth_data`
- **Expiry**: 30 days (2,592,000 seconds)
- **Path**: `/` (entire domain)
- **Security**: HttpOnly, Secure (for HTTPS)

### Encryption Settings
- **Algorithm**: AES-256-CBC
- **Key**: `stc_vikings_auth_key_2024` (change in production)
- **IV**: Random 16-byte initialization vector

## Testing

### Test File
Use `test_auth.php` to verify the authentication system:
- Shows current authentication status
- Displays session and cookie data
- Provides logout functionality

### Manual Testing
1. Login to the system
2. Close browser completely
3. Reopen browser and visit any protected page
4. Should automatically log in using cookie data

## Migration Notes

### What Changed
1. **Old Pattern** (replaced in all files):
   ```php
   session_start(); 
   if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
   }else{ 
       header("Location:index.html"); 
   }
   ```

2. **New Pattern**:
   ```php
   require_once 'kattegat/auth_helper.php';
   STCAuthHelper::checkAuth();
   ```

### Files Updated
- 29 PHP files updated with new authentication system
- `kattegat/useroath.php` - Updated login process
- `kattegat/logout.php` - Updated logout process
- All protected pages now use the hybrid system

## Troubleshooting

### Common Issues

1. **Cookie not working**
   - Check if cookies are enabled in browser
   - Verify HTTPS settings if using secure cookies
   - Check browser developer tools for cookie errors

2. **Session not restoring**
   - Verify encryption key is consistent
   - Check if cookie data is corrupted
   - Clear browser cookies and try again

3. **Redirect loops**
   - Ensure `index.html` exists and is accessible
   - Check for conflicting authentication checks
   - Verify file paths are correct

### Debug Information
The `test_auth.php` file provides detailed information about:
- Current session status
- Cookie existence and content
- User authentication details

## Security Recommendations

1. **Change Encryption Key**
   - Update the encryption key in `auth_helper.php`
   - Use a strong, random key for production

2. **HTTPS Only**
   - Enable secure cookies in production
   - Use HTTPS for all authentication

3. **Regular Security Audits**
   - Monitor cookie usage
   - Check for unauthorized access attempts
   - Review session management logs

## Support

For issues or questions regarding the authentication system:
1. Check the test file for debugging information
2. Review browser developer tools for cookie/session issues
3. Verify all file paths and includes are correct
4. Test with a fresh browser session

---

**Note**: This system maintains backward compatibility with existing session-based features while extending the authentication lifetime through secure cookie storage.
