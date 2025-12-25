# Environment Configuration Guide

This guide explains how to configure the `.env` file for both local and server environments.

## Solution: Use `.env` File (Recommended)

**You do NOT need to write separate `.htaccess` files.** Simply set the `APP_URL` in your `.env` file for each environment.

## Configuration

### For Local Development

In your `.env` file, set:

```env
APP_URL=http://localhost/stc/stc_payroll/public
```

### For Production Server

In your `.env` file on the server, set:

```env
APP_URL=https://stcassociate.com/stc_payroll/public
```

## How It Works

1. **AppServiceProvider** (`app/Providers/AppServiceProvider.php`) reads the `APP_URL` from `.env`
2. If `APP_URL` is not set, it auto-detects from the current request
3. All URLs are generated dynamically using Laravel's `url()` helper or `window.appBaseUrl` in JavaScript
4. The application automatically works in both environments without code changes

## Important Notes

1. **Always include `/public` at the end of `APP_URL`** - This is required by Laravel
2. **Use `https://` for production** - Use `http://` only for local development
3. **No trailing slash** - Don't add a trailing slash after `/public`
4. **Clear cache after changing `.env`**:
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan cache:clear
   ```

## Example `.env` Files

### Local `.env`
```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=true
APP_URL=http://localhost/stc/stc_payroll/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stc_associate_go
DB_USERNAME=root
DB_PASSWORD=
```

### Server `.env`
```env
APP_NAME=Laravel
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://stcassociate.com/stc_payroll/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Testing

After setting up your `.env`:

1. **Clear all caches** (on server via SSH):
   ```bash
   cd ~/public_html/stc_payroll
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan cache:clear
   ```

2. **Test URLs**:
   - Local: `http://localhost/stc/stc_payroll/transaction/attendance`
   - Server: `https://stcassociate.com/stc_payroll/transaction/attendance`

3. **Check JavaScript Console**: Open browser DevTools and verify `window.appBaseUrl` shows the correct URL

## Troubleshooting

### URLs still showing wrong path?
- Clear all caches (see above)
- Verify `.env` file has correct `APP_URL`
- Check that `.env` file is in the root directory (`stc_payroll/.env`)
- Restart your local server if using `php artisan serve`

### Getting 404 errors?
- Ensure `APP_URL` ends with `/public`
- Check that your web server is pointing to the `public` directory
- Verify routes are registered correctly

### JavaScript URLs not working?
- Check browser console for `window.appBaseUrl` value
- Ensure `layouts/head.blade.php` includes the JavaScript base URL script
- Verify all AJAX calls use `window.appBaseUrl + "/path"` instead of hardcoded paths

