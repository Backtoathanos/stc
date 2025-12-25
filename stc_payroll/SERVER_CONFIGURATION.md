# Server Configuration Guide

## Problem: URLs showing `/public/` in the path

If you're seeing URLs like `https://stcassociate.com/stc_payroll/public/transaction/attendance`, it means the web server document root is not pointing to the `public` directory.

## Solution Options

### Option 1: Point Document Root to `public` Directory (Recommended)

**On cPanel/Shared Hosting:**

1. Go to **cPanel** → **File Manager**
2. Navigate to your domain's document root (usually `public_html/stc_payroll`)
3. In cPanel, you can set the document root to point to `public_html/stc_payroll/public` instead
4. Or use **Subdomain** or **Addon Domain** settings to point directly to the `public` folder

**Result:** URLs will be `https://stcassociate.com/stc_payroll/transaction/attendance` (without `/public/`)

### Option 2: Use Root `.htaccess` (Current Setup)

The root `.htaccess` file has been configured to redirect all requests to the `public` directory. This should work if:

1. The document root is set to `stc_payroll` (not `stc_payroll/public`)
2. The `.htaccess` file is in the root `stc_payroll` directory
3. `mod_rewrite` is enabled on your server

**Test:** Access `https://stcassociate.com/stc_payroll/transaction/attendance` (without `/public/`)

### Option 3: Create Symbolic Link (Advanced)

If you have SSH access:

```bash
cd ~/public_html
ln -s stc_payroll/public stc_payroll_public
```

Then point your domain/subdomain to `stc_payroll_public`

## Verification

After configuration:

1. **Clear Laravel caches:**
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan cache:clear
   ```

2. **Test URLs:**
   - ✅ Correct: `https://stcassociate.com/stc_payroll/transaction/attendance`
   - ❌ Wrong: `https://stcassociate.com/stc_payroll/public/transaction/attendance`

3. **Check `.env` file:**
   ```env
   APP_URL=https://stcassociate.com/stc_payroll/public
   ```
   Note: `APP_URL` should still include `/public` even if URLs don't show it.

## Troubleshooting

### Still seeing `/public/` in URLs?

1. **Check document root:** Verify in cPanel where the document root is pointing
2. **Check `.htaccess`:** Ensure the root `.htaccess` file exists and has correct permissions (644)
3. **Check `mod_rewrite`:** Contact hosting support to ensure `mod_rewrite` is enabled
4. **Clear browser cache:** Hard refresh (Ctrl+F5) or clear browser cache

### Getting 404 errors?

1. **Verify routes:** Run `php artisan route:list` to see registered routes
2. **Check permissions:** Ensure user has `transaction.attendance.view` permission
3. **Check logs:** Check `storage/logs/laravel.log` for errors

### Redirecting to `/home` instead of dashboard?

This has been fixed in `RedirectIfAuthenticated` middleware to use `route('home')` instead of hardcoded `/home`.

