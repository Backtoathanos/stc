# Server Bootstrap Error Fix

## Error
```
PackageManifest->config() at Collection.php
```

This error occurs when Laravel's bootstrap cache files are corrupted or missing.

## Solution - Run These Commands on Server

### Step 1: Navigate to Project Directory
```bash
cd /home/c2484kn1eg0y/public_html/stc_payroll
```

### Step 2: Delete Bootstrap Cache Files
```bash
# Remove all bootstrap cache files
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes.php
rm -f bootstrap/cache/services.php
rm -f bootstrap/cache/packages.php
```

### Step 3: Regenerate Composer Autoload
```bash
composer dump-autoload
```

### Step 4: Clear All Laravel Caches
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
```

### Step 5: Regenerate Package Discovery
```bash
php artisan package:discover
```

### Step 6: Set Proper Permissions
```bash
# Make bootstrap/cache writable
chmod -R 775 bootstrap/cache
chmod -R 775 storage

# If needed, set ownership (replace www-data with your web server user)
chown -R www-data:www-data bootstrap/cache
chown -R www-data:www-data storage
```

### Step 7: Test
```bash
# Test if artisan works
php artisan --version

# If that works, test a route
php artisan route:list
```

## Alternative: Complete Reset (if above doesn't work)

If the above steps don't work, try a complete reset:

```bash
# 1. Delete all cache files
rm -rf bootstrap/cache/*
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*

# 2. Reinstall composer dependencies
composer install --no-dev --optimize-autoloader

# 3. Regenerate everything
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
php artisan package:discover

# 4. Set permissions
chmod -R 775 bootstrap/cache
chmod -R 775 storage
```

## Common Causes

1. **File Permissions**: Bootstrap cache directory not writable
2. **Corrupted Cache**: Old cache files from previous deployment
3. **Composer Issues**: Autoload files out of sync
4. **Missing Files**: Bootstrap cache files deleted accidentally

## Prevention

Always run these commands after deploying code:
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
```

