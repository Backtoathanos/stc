# Cookie Debugging Guide for STC GLD

## Issue Description
The application was experiencing cookie expiration issues where cookies set for 7 days were being cleared after 2 hours in production environment.

## Root Causes Identified

1. **Security Settings**: Cookies were set with `Secure` and `SameSite=Strict` attributes which can cause issues in certain deployment scenarios
2. **Missing Domain Attribute**: Cookies didn't have a `domain` attribute specified, causing issues with subdomains
3. **Browser Security Policies**: Modern browsers have strict cookie policies
4. **No Cookie Validation**: Application didn't validate if cookies were being set correctly

## Solutions Implemented

### 1. Enhanced Cookie Management (`src/utils/cookieUtils.js`)
- **Environment-aware cookie settings**: Automatically detects production vs development
- **Proper domain handling**: Sets domain for production deployment
- **Security flags**: Only adds `Secure` flag in HTTPS environments
- **Better SameSite policy**: Uses `SameSite=Lax` for better compatibility
- **Cookie validation**: Includes verification and debugging functions

### 2. Updated Login Component (`src/components/Login.js`)
- Uses centralized cookie utilities
- Includes debug logging for cookie setting
- Verifies cookies after setting them

### 3. Enhanced Authentication Guard (`src/Protected.js`)
- Validates both localStorage and cookies
- Provides detailed logging for debugging
- Uses centralized validation functions

### 4. Improved Logout Functionality (`src/components/layouts/Navbar.js`)
- Properly clears all authentication cookies
- Uses centralized cookie clearing functions

### 5. Debug Component (`src/components/CookieDebugger.js`)
- Real-time cookie monitoring
- Shows authentication status
- Displays all cookies and environment info
- Updates every 5 seconds

## How to Debug Cookie Issues

### 1. Enable Debug Mode
The `CookieDebugger` component is now included in the app. Look for the "Debug Cookies" button in the bottom-right corner.

### 2. Check Browser Console
All cookie operations now include console logging:
- Cookie setting attempts
- Cookie verification results
- Authentication validation
- Logout operations

### 3. Monitor Cookie Behavior
The debug component shows:
- Authentication status (Valid/Invalid)
- User ID and Location cookies
- All cookies present
- Environment information (protocol, hostname, port)
- Last update timestamp

### 4. Common Issues to Check

#### Production Environment Issues
- **HTTPS Required**: Ensure the production site uses HTTPS
- **Domain Mismatch**: Check if the domain in cookies matches the actual domain
- **Subdomain Issues**: Verify if cookies are set for the correct domain scope

#### Browser Issues
- **Third-party Cookies**: Some browsers block third-party cookies
- **Privacy Settings**: Check browser privacy/security settings
- **Cookie Storage**: Verify if cookies are being stored in browser

#### Server Issues
- **CORS Headers**: Ensure proper CORS configuration
- **Session Management**: Check if server-side sessions are interfering

## Testing the Fix

### 1. Development Testing
```bash
npm start
```
- Check console logs for cookie operations
- Use the debug component to monitor cookies
- Test login/logout functionality

### 2. Production Testing
```bash
npm run build
```
- Deploy the build to production
- Check browser developer tools for cookie behavior
- Monitor the debug component in production
- Verify cookies persist across browser sessions

### 3. Cookie Verification Steps
1. Login to the application
2. Check browser developer tools → Application → Cookies
3. Verify cookies are set with correct expiration
4. Check the debug component for validation status
5. Test navigation between protected routes
6. Verify cookies persist after browser restart

## Environment-Specific Settings

### Development (HTTP)
- No `Secure` flag
- No domain restriction
- `SameSite=Lax`

### Production (HTTPS)
- `Secure` flag enabled
- Domain set to `.stcassociate.com`
- `SameSite=Lax`

## Troubleshooting

### If Cookies Still Don't Persist

1. **Check Browser Console**: Look for cookie-related errors
2. **Verify HTTPS**: Ensure production uses HTTPS
3. **Check Domain**: Verify the domain setting matches your deployment
4. **Browser Settings**: Check if browser is blocking cookies
5. **Server Configuration**: Verify server CORS and security headers

### Debug Commands
```javascript
// Check all cookies
console.log(document.cookie);

// Check specific cookie
console.log(getCookie('user_id'));

// Validate authentication
console.log(validateAuthCookies());
```

## Files Modified

1. `src/utils/cookieUtils.js` - New utility file
2. `src/components/Login.js` - Updated cookie handling
3. `src/components/layouts/Navbar.js` - Enhanced logout
4. `src/Protected.js` - Improved authentication guard
5. `src/components/CookieDebugger.js` - New debug component
6. `src/App.js` - Added debug component

## Next Steps

1. Deploy the updated code to production
2. Monitor cookie behavior using the debug component
3. Check browser console for any errors
4. Verify cookies persist for the full 7-day period
5. Remove the debug component once issues are resolved (optional)

## Support

If issues persist after implementing these fixes, check:
- Browser developer tools for specific error messages
- Server logs for any cookie-related errors
- Network tab for failed cookie operations
- The debug component for real-time cookie status
