# Shared Hosting Deployment Guide for STC Payroll

Based on your working `superadmin.stcassociate.com` configuration, here's how to deploy `stc_payroll` on shared hosting.

## Step 1: Upload Files to Server

Upload all files to your shared hosting (via FTP/SFTP or cPanel File Manager) to:
```
/public_html/stc_payroll/
```
or
```
/home/username/public_html/stc_payroll/
```

## Step 2: Create .env File

Create a `.env` file in the root of `stc_payroll` directory with the following configuration:

```env
APP_NAME="STC Payroll"
APP_ENV=production
APP_KEY=base64:RdrGQ0rz6zQpqvNHrg9nCT9w2rjBJ05+KcD4qhxBQsg=
APP_DEBUG=false
APP_URL=https://yourdomain.com/stc_payroll/public

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stc_payroll
DB_USERNAME=nausher
DB_PASSWORD="ZX5#*,PPq$5;"

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=525600

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### Important Changes to Make:

1. **APP_KEY**: Generate a new one or use the one from your working app
   ```bash
   php artisan key:generate
   ```

2. **APP_URL**: Change to your actual domain
   ```
   APP_URL=https://yourdomain.com/stc_payroll/public
   ```
   OR if using subdomain:
   ```
   APP_URL=https://payroll.yourdomain.com/public
   ```

3. **Database Settings**: Update with your payroll database credentials
   - `DB_DATABASE`: Your payroll database name (e.g., `stc_payroll`)
   - `DB_USERNAME`: Your database username
   - `DB_PASSWORD`: Your database password
   - `DB_HOST`: Usually `127.0.0.1` or `localhost` on shared hosting

## Step 3: Access Terminal/SSH

Most shared hosting providers offer:
- **cPanel Terminal** (if enabled)
- **SSH Access** (if enabled)
- **PHP CLI** via cPanel

## Step 4: Run Deployment Commands

Navigate to your project directory:
```bash
cd ~/public_html/stc_payroll
# OR
cd /home/username/public_html/stc_payroll
```

### Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### Generate Application Key (if not set)
```bash
php artisan key:generate
```

### Run Migrations
```bash
php artisan migrate --force
```

### Create Storage Link
```bash
php artisan storage:link
```

### Clear and Optimize Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 5: Set File Permissions

Via SSH or cPanel File Manager:
```bash
# Set directory permissions
find . -type d -exec chmod 755 {} \;

# Set file permissions
find . -type f -exec chmod 644 {} \;

# Make storage and cache writable
chmod -R 775 storage bootstrap/cache
```

## Step 6: Configure Web Server

### Option A: Using .htaccess (Recommended for Shared Hosting)

Create or update `.htaccess` in the `public` directory:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Option B: Point Domain/Subdomain to public folder

If you have a subdomain (e.g., `payroll.yourdomain.com`), point it to:
```
/home/username/public_html/stc_payroll/public
```

### Option C: Using Subdirectory

If accessing via `yourdomain.com/stc_payroll`, update `public/.htaccess`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /stc_payroll/public/
    
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

## Step 7: Verify Installation

1. Access your application:
   - `https://yourdomain.com/stc_payroll/public`
   - OR `https://payroll.yourdomain.com` (if subdomain configured)

2. Check for errors:
   - If you see a blank page, check `storage/logs/laravel.log`
   - Enable `APP_DEBUG=true` temporarily to see errors
   - Check file permissions

## Common Issues & Solutions

### Issue 1: 500 Internal Server Error
**Solution:**
- Check file permissions (storage and bootstrap/cache must be writable)
- Check `.env` file exists and is configured correctly
- Check `storage/logs/laravel.log` for specific errors

### Issue 2: Database Connection Error
**Solution:**
- Verify database credentials in `.env`
- Ensure database exists in cPanel
- Check if database user has proper permissions

### Issue 3: CSS/JS Not Loading
**Solution:**
- Ensure `APP_URL` in `.env` matches your actual URL
- Check that `public/storage` symlink exists
- Verify file permissions on `public` directory

### Issue 4: Route Not Found (404)
**Solution:**
- Ensure `.htaccess` file exists in `public` directory
- Check if `mod_rewrite` is enabled on server
- Verify `APP_URL` is correct

### Issue 5: Permission Denied
**Solution:**
```bash
chmod -R 775 storage bootstrap/cache
chmod -R 755 .
```

## Quick Deployment Script for Shared Hosting

Create a file `deploy-shared.sh`:

```bash
#!/bin/bash
cd ~/public_html/stc_payroll

echo "Installing dependencies..."
composer install --no-dev --optimize-autoloader

echo "Generating key..."
php artisan key:generate --force

echo "Running migrations..."
php artisan migrate --force

echo "Creating storage link..."
php artisan storage:link --force

echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Setting permissions..."
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
chmod -R 775 storage bootstrap/cache

echo "Deployment complete!"
```

Make it executable and run:
```bash
chmod +x deploy-shared.sh
./deploy-shared.sh
```

## Post-Deployment Checklist

- [ ] `.env` file configured with production settings
- [ ] `APP_DEBUG=false` in production
- [ ] Database credentials correct
- [ ] `APP_URL` matches actual domain
- [ ] Storage link created (`public/storage` → `storage/app/public`)
- [ ] File permissions set correctly
- [ ] Caches cleared and optimized
- [ ] Application accessible via browser
- [ ] No errors in `storage/logs/laravel.log`

## Security Notes

1. **Never commit `.env` file** to version control
2. **Set `APP_DEBUG=false`** in production
3. **Use strong database passwords**
4. **Keep Laravel and dependencies updated**
5. **Restrict file permissions** (755 for directories, 644 for files)

## Support

If you encounter issues:
1. Check `storage/logs/laravel.log` for detailed error messages
2. Temporarily set `APP_DEBUG=true` to see error details (remember to set back to `false`)
3. Verify all file permissions are correct
4. Ensure all required PHP extensions are installed

