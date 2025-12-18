# .htaccess Setup for http://localhost/stc/stc_payroll/

The `.htaccess` file has been configured to redirect requests from the root to the `public` folder.

## Important: Apache Configuration Required

For the `.htaccess` to work, you need to ensure Apache is configured correctly:

### 1. Enable mod_rewrite

Open `C:\xampp\apache\conf\httpd.conf` and make sure this line is **uncommented** (remove the `#` if present):

```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

### 2. Allow .htaccess Overrides

In the same `httpd.conf` file, find the `<Directory>` section for your DocumentRoot (usually around line 240-250):

```apache
<Directory "C:/xampp/htdocs">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Require all granted
</Directory>
```

Make sure `AllowOverride All` is set (not `AllowOverride None`).

### 3. Restart Apache

After making changes:
1. Stop Apache in XAMPP Control Panel
2. Start Apache again
3. Clear your browser cache
4. Try accessing: `http://localhost/stc/stc_payroll/`

## Test if it's Working

1. Try accessing: `http://localhost/stc/stc_payroll/`
2. If you still get 404, check Apache error logs: `C:\xampp\apache\logs\error.log`
3. Verify mod_rewrite is loaded by checking: `http://localhost/stc/stc_payroll/public/` (this should always work)

## Alternative: If .htaccess Still Doesn't Work

If the `.htaccess` approach doesn't work, you can:

1. **Use the public URL** (always works): `http://localhost/stc/stc_payroll/public/`
2. **Create a Virtual Host** (best solution) - see `404_FIX.md` for instructions

