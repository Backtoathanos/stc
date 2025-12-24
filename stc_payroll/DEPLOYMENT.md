# Laravel Application Deployment Guide

## Step-by-Step Deployment Commands

### 1. Navigate to Project Directory
```bash
cd /path/to/your/stc_payroll
```

### 2. Install/Update Composer Dependencies
```bash
composer install --no-dev --optimize-autoloader
```
**Note:** Use `--no-dev` for production (removes dev dependencies) and `--optimize-autoloader` for better performance.

### 3. Environment Configuration
```bash
# Copy .env.example to .env (if not exists)
cp .env.example .env

# Or create .env manually and configure it
nano .env
```

**Important .env settings to configure:**
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://yourdomain.com`
- Database credentials
- Mail settings (if needed)

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Run Database Migrations
```bash
# Run migrations
php artisan migrate --force

# If you need to seed initial data (optional)
# php artisan db:seed --force
```

### 6. Create Storage Link
```bash
php artisan storage:link
```

### 7. Clear and Cache Configuration
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache for production (optimize)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 8. Set Proper Permissions (Linux/Unix)
```bash
# Set ownership (replace 'www-data' with your web server user)
sudo chown -R www-data:www-data /path/to/your/stc_payroll

# Set directory permissions
sudo find /path/to/your/stc_payroll -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /path/to/your/stc_payroll -type f -exec chmod 644 {} \;

# Make storage and cache writable
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### 9. Verify Installation
```bash
# Check Laravel version
php artisan --version

# List all routes (optional)
php artisan route:list
```

## Complete Deployment Script

You can create a deployment script `deploy.sh`:

```bash
#!/bin/bash

echo "Starting Laravel Deployment..."

# Navigate to project directory
cd /path/to/your/stc_payroll

# Pull latest code (if using git)
# git pull origin main

# Install dependencies
echo "Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Generate key if needed
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
    php artisan key:generate
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Create storage link
echo "Creating storage link..."
php artisan storage:link

# Clear caches
echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
echo "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions (adjust user/group as needed)
echo "Setting permissions..."
sudo chown -R www-data:www-data /path/to/your/stc_payroll
sudo chmod -R 755 /path/to/your/stc_payroll
sudo chmod -R 775 storage bootstrap/cache

echo "Deployment completed!"
```

## Quick Deployment Commands (Copy-Paste Ready)

```bash
# 1. Install dependencies
composer install --no-dev --optimize-autoloader

# 2. Setup environment (if .env doesn't exist)
cp .env.example .env
php artisan key:generate

# 3. Run migrations
php artisan migrate --force

# 4. Create storage link
php artisan storage:link

# 5. Clear and optimize
php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear
php artisan config:cache && php artisan route:cache && php artisan view:cache

# 6. Set permissions (Linux/Unix only)
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache
```

## Troubleshooting

### If you get permission errors:
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### If you get "Class not found" errors:
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### If database connection fails:
- Check `.env` file database credentials
- Ensure database exists
- Check database user permissions

### If storage link fails:
```bash
php artisan storage:link --force
```

## Post-Deployment Checklist

- [ ] Application key generated
- [ ] Database migrations completed
- [ ] Storage link created
- [ ] Caches cleared and optimized
- [ ] Permissions set correctly
- [ ] `.env` file configured with production settings
- [ ] `APP_DEBUG=false` in production
- [ ] Web server (Apache/Nginx) configured
- [ ] SSL certificate installed (if using HTTPS)

## Notes

- Always backup your database before running migrations in production
- Test in staging environment first
- Keep `APP_DEBUG=false` in production for security
- Regularly update dependencies: `composer update --no-dev`

