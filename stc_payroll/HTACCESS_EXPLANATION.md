# .htaccess Configuration Explanation

## What This Does

The root `.htaccess` file removes `/public/` from URLs while still routing all requests through Laravel's `public/index.php` entry point.

## How It Works

### 1. Redirect `/public/` URLs (301 Redirect)
```apache
RewriteCond %{THE_REQUEST} \s/+public/([^\s?]*) [NC]
RewriteRule ^ /%1 [R=301,L]
```
- If someone accesses `https://stcassociate.com/stc_payroll/public/transaction/attendance`
- They get redirected to `https://stcassociate.com/stc_payroll/transaction/attendance`
- This is a permanent redirect (301) so search engines update their indexes

### 2. Handle Static Files
```apache
RewriteCond %{DOCUMENT_ROOT}/public%{REQUEST_URI} -f [OR]
RewriteCond %{DOCUMENT_ROOT}/public%{REQUEST_URI} -d
RewriteRule ^(.*)$ public/$1 [L]
```
- If a file exists in the `public` directory (CSS, JS, images)
- Serve it directly from `public/` without showing `/public/` in the URL
- Example: `/css/style.css` serves from `public/css/style.css`

### 3. Route to Laravel
```apache
RewriteRule ^(.*)$ public/index.php [L]
```
- All other requests go to `public/index.php`
- Laravel handles routing from there
- URL stays clean without `/public/`

## Result

✅ **Clean URLs:**
- `https://stcassociate.com/stc_payroll/transaction/attendance`
- `https://stcassociate.com/stc_payroll/master/sites`
- `https://stcassociate.com/stc_payroll/reports/payroll`

❌ **No more:**
- `https://stcassociate.com/stc_payroll/public/transaction/attendance`

## Important Notes

1. **APP_URL in .env** should still include `/public`:
   ```env
   APP_URL=https://stcassociate.com/stc_payroll/public
   ```
   This is for Laravel's internal URL generation, but users won't see `/public/` in their browser.

2. **Document Root:** The document root should point to `stc_payroll` directory (not `stc_payroll/public`)

3. **Static Files:** CSS, JS, and images will work correctly because they're served from `public/` directory internally

4. **Cache Clearing:** After deploying, clear Laravel caches:
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan cache:clear
   ```

## Testing

1. Access: `https://stcassociate.com/stc_payroll/transaction/attendance`
   - Should work without `/public/` in URL

2. Access: `https://stcassociate.com/stc_payroll/public/transaction/attendance`
   - Should redirect (301) to the URL without `/public/`

3. Check browser DevTools → Network tab
   - All requests should show clean URLs
   - No `/public/` visible in the address bar

