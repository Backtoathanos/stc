# Apache Configuration Check

If you're still seeing a directory listing, check these Apache settings:

## 1. Check DirectoryIndex

In `C:\xampp\apache\conf\httpd.conf`, find the line:
```apache
DirectoryIndex index.html index.php
```

Make sure `index.php` is listed. If not, add it:
```apache
DirectoryIndex index.html index.php index.htm
```

## 2. Enable .htaccess Processing

In `httpd.conf`, find the `<Directory>` section for htdocs:
```apache
<Directory "C:/xampp/htdocs">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Require all granted
</Directory>
```

**Important:** `AllowOverride All` must be set (not `None`).

## 3. Enable mod_rewrite

In `httpd.conf`, find and uncomment:
```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

## 4. Restart Apache

After making changes:
1. Stop Apache in XAMPP Control Panel
2. Start Apache again
3. Clear browser cache
4. Try: `http://localhost/stc/stc_payroll/`

## Quick Test

To verify PHP is working, create a test file `test.php` in the root:
```php
<?php phpinfo(); ?>
```

Access: `http://localhost/stc/stc_payroll/test.php`

If this shows PHP info, PHP is working. If you see the code, PHP is not enabled.

## Alternative Solution

If .htaccess still doesn't work, you can always access:
```
http://localhost/stc/stc_payroll/public/
```

This will always work regardless of Apache configuration.

