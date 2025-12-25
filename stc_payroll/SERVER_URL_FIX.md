# Server URL Fix - Import Preview Issue

## Problem
The import-preview endpoint works locally but fails on the server. The curl command shows the URL includes `/public`:
```
https://stcassociate.com/stc_payroll/public/transaction/attendance/import-preview
```

But the application should generate URLs without `/public` in the path because the `.htaccess` file redirects `/public` URLs.

## Solution Applied

Updated `AppServiceProvider.php` to generate URLs without `/public` in the path, while still maintaining the correct base URL structure.

## Steps to Fix on Server

### 1. Update `.env` File
Ensure your server's `.env` file has the correct `APP_URL`:

```env
APP_URL=https://stcassociate.com/stc_payroll/public
```

**Important:** Keep `/public` at the end for Laravel's internal operations, but the application will now generate URLs without `/public` in the path.

### 2. Clear All Caches
After updating the code, clear all Laravel caches on the server:

```bash
cd /path/to/stc_payroll
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
```

### 3. Verify URL Generation
After clearing caches, check that `window.appBaseUrl` in the browser console shows:
```
https://stcassociate.com/stc_payroll
```

**NOT:**
```
https://stcassociate.com/stc_payroll/public
```

### 4. Test the Endpoint
The correct URL should be:
```
https://stcassociate.com/stc_payroll/transaction/attendance/import-preview
```

**NOT:**
```
https://stcassociate.com/stc_payroll/public/transaction/attendance/import-preview
```

## Additional Checks

### File Upload Limits
If you still get errors, check PHP upload limits on the server:

```php
// Check in phpinfo() or create a test file:
echo ini_get('upload_max_filesize');
echo ini_get('post_max_size');
```

The controller allows up to 10MB (`max:10240`). Ensure server limits are higher:
- `upload_max_filesize = 20M` (or higher)
- `post_max_size = 20M` (or higher)

### CSRF Token
Ensure the CSRF token is being sent correctly. Check browser console for:
- `X-CSRF-TOKEN` header is present
- Token matches the session token

### Error Logs
Check Laravel error logs on the server:
```bash
tail -f storage/logs/laravel.log
```

## Testing

1. Open browser DevTools (F12)
2. Go to Console tab
3. Type: `window.appBaseUrl`
4. Should show: `https://stcassociate.com/stc_payroll` (without `/public`)
5. Try uploading a file through the UI
6. Check Network tab for the actual request URL

## Expected Behavior

- **Local:** `http://localhost/stc/stc_payroll/transaction/attendance/import-preview`
- **Server:** `https://stcassociate.com/stc_payroll/transaction/attendance/import-preview`

Both should work without `/public` in the path.

