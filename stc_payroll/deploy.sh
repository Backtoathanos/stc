#!/bin/bash

# Laravel Deployment Script
# Make this file executable: chmod +x deploy.sh
# Run: ./deploy.sh

echo "=========================================="
echo "Laravel Application Deployment Script"
echo "=========================================="
echo ""

# Get the current directory
PROJECT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$PROJECT_DIR"

echo "Project Directory: $PROJECT_DIR"
echo ""

# Step 1: Install/Update Composer Dependencies
echo "Step 1: Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader
if [ $? -ne 0 ]; then
    echo "ERROR: Composer install failed!"
    exit 1
fi
echo "✓ Dependencies installed"
echo ""

# Step 2: Check/Create .env file
if [ ! -f .env ]; then
    echo "Step 2: Creating .env file..."
    if [ -f .env.example ]; then
        cp .env.example .env
        echo "✓ .env file created from .env.example"
        echo "⚠️  IMPORTANT: Please edit .env file with your production settings!"
    else
        echo "⚠️  WARNING: .env.example not found. Please create .env manually."
    fi
else
    echo "Step 2: .env file already exists"
fi
echo ""

# Step 3: Generate Application Key (if needed)
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "Step 3: Generating application key..."
    php artisan key:generate --force
    echo "✓ Application key generated"
else
    echo "Step 3: Application key already exists"
fi
echo ""

# Step 4: Run Database Migrations
echo "Step 4: Running database migrations..."
php artisan migrate --force
if [ $? -ne 0 ]; then
    echo "⚠️  WARNING: Migrations may have failed. Please check manually."
else
    echo "✓ Migrations completed"
fi
echo ""

# Step 5: Create Storage Link
echo "Step 5: Creating storage symbolic link..."
php artisan storage:link --force
echo "✓ Storage link created"
echo ""

# Step 6: Clear All Caches
echo "Step 6: Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo "✓ Caches cleared"
echo ""

# Step 7: Optimize for Production
echo "Step 7: Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✓ Application optimized"
echo ""

# Step 8: Set Permissions (Linux/Unix)
if [ "$(uname)" != "MINGW"* ] && [ "$(uname)" != "MSYS"* ]; then
    echo "Step 8: Setting file permissions..."
    
    # Detect web server user
    if [ -n "$SUDO_USER" ]; then
        WEB_USER="$SUDO_USER"
    elif [ -n "$USER" ]; then
        WEB_USER="$USER"
    else
        WEB_USER="www-data"
    fi
    
    echo "Using web user: $WEB_USER"
    
    # Set ownership
    if [ "$EUID" -eq 0 ]; then
        chown -R $WEB_USER:$WEB_USER "$PROJECT_DIR"
        echo "✓ Ownership set"
    else
        echo "⚠️  Skipping ownership change (requires sudo)"
    fi
    
    # Set directory permissions
    find "$PROJECT_DIR" -type d -exec chmod 755 {} \; 2>/dev/null
    echo "✓ Directory permissions set"
    
    # Set file permissions
    find "$PROJECT_DIR" -type f -exec chmod 644 {} \; 2>/dev/null
    echo "✓ File permissions set"
    
    # Make storage and cache writable
    chmod -R 775 storage bootstrap/cache 2>/dev/null
    echo "✓ Storage and cache directories made writable"
else
    echo "Step 8: Skipping permissions (Windows detected)"
fi
echo ""

# Step 9: Final Checks
echo "Step 9: Running final checks..."
php artisan --version
echo ""

echo "=========================================="
echo "Deployment Completed Successfully!"
echo "=========================================="
echo ""
echo "Next Steps:"
echo "1. Verify .env file has correct production settings"
echo "2. Ensure APP_DEBUG=false in production"
echo "3. Configure your web server (Apache/Nginx)"
echo "4. Test the application in browser"
echo ""

