# How to Access the Application

## Quick Access (Recommended)

Access the application directly via the public folder:

```
http://localhost/stc/stc_payroll/
```

This will work immediately without any Apache configuration changes.

## Alternative: Access via Root

If you want to access via `http://localhost/stc/stc_payroll/`, you need to:

1. **Enable mod_rewrite in Apache**
   - Open `C:\xampp\apache\conf\httpd.conf`
   - Find and uncomment: `LoadModule rewrite_module modules/mod_rewrite.so`

2. **Allow .htaccess overrides**
   - In `httpd.conf`, find the section for your DocumentRoot
   - Change `AllowOverride None` to `AllowOverride All`
   - Look for something like:
   ```apache
   <Directory "C:/xampp/htdocs">
       Options Indexes FollowSymLinks
       AllowOverride All
       Require all granted
   </Directory>
   ```

3. **Restart Apache** in XAMPP Control Panel

4. Then access: `http://localhost/stc/stc_payroll/`

## Test if .htaccess is Working

Try accessing:
```
http://localhost/stc/stc_payroll/index.php
```

If this works but `http://localhost/stc/stc_payroll/` doesn't, then mod_rewrite is not enabled or .htaccess is not being read.

## Best Solution: Virtual Host

Create a virtual host for cleaner URLs. See `404_FIX.md` for instructions.

