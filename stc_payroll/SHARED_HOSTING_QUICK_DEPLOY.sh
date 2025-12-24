#!/bin/bash

# Quick Deployment Script for Shared Hosting
# Run this from your home directory or stc_payroll directory

echo "=========================================="
echo "STC Payroll - Shared Hosting Deployment"
echo "=========================================="
echo ""

# Get the project directory
if [ -d "~/public_html/stc_payroll" ]; then
    PROJECT_DIR="~/public_html/stc_payroll"
elif [ -d "public_html/stc_payroll" ]; then
    PROJECT_DIR="public_html/stc_payroll"
elif [ -d "stc_payroll" ]; then
    PROJECT_DIR="stc_payroll"
else
    echo "ERROR: stc_payroll directory not found!"
    echo "Please run this script from your home directory or stc_payroll directory"
    exit 1
fi

cd "$PROJECT_DIR"
echo "Project Directory: $(pwd)"
echo ""

# Check for composer.phar in home directory
if [ -f ~/composer.phar ]; then
    COMPOSER_CMD="php ~/composer.phar"
    echo "Using composer.phar from home directory"
elif [ -f composer.phar ]; then
    COMPOSER_CMD="php composer.phar"
    echo "Using composer.phar from current directory"
elif command -v composer &> /dev/null; then
    COMPOSER_CMD="composer"
    echo "Using global composer"
else
    echo "ERROR: composer.phar not found!"
    echo "Please download composer.phar to your home directory:"
    echo "cd ~ && curl -sS https://getcomposer.org/installer | php"
    exit 1
fi
echo ""

# Step 1: Install Dependencies
echo "Step 1: Installing Composer dependencies..."
$COMPOSER_CMD install --no-dev --optimize-autoloader
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
        echo "   - Update APP_URL"
        echo "   - Update database credentials"
        echo "   - Set APP_DEBUG=false"
    else
        echo "⚠️  WARNING: .env.example not found. Please create .env manually."
    fi
else
    echo "Step 2: .env file already exists"
fi
echo ""

# Step 3: Generate Application Key
echo "Step 3: Generating application key..."
php artisan key:generate --force
echo "✓ Application key generated"
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

# Step 8: Set Permissions
echo "Step 8: Setting file permissions..."
find . -type d -exec chmod 755 {} \; 2>/dev/null
find . -type f -exec chmod 644 {} \; 2>/dev/null
chmod -R 775 storage bootstrap/cache 2>/dev/null
echo "✓ Permissions set"
echo ""

echo "=========================================="
echo "Deployment Completed Successfully!"
echo "=========================================="
echo ""
echo "Next Steps:"
echo "1. Edit .env file with your production settings"
echo "2. Verify APP_URL matches your domain"
echo "3. Test the application in browser"
echo ""

