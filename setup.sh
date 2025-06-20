#!/bin/bash

# ZPlus Shop - Bagisto Setup Script
echo "🚀 Setting up ZPlus Shop (Bagisto) with Nginx..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

PROJECT_PATH="/Volumes/DATA/project/zplus_shop"
BACKEND_PATH="$PROJECT_PATH/backend"
NGINX_CONF_PATH="$PROJECT_PATH/nginx/zplus.local.conf"

echo -e "${YELLOW}Step 1: Checking prerequisites...${NC}"

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo -e "${RED}❌ PHP is not installed. Please install PHP 8.2 or higher${NC}"
    exit 1
fi

# Check PHP version
PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
if [[ $(echo "$PHP_VERSION >= 8.2" | bc -l) -eq 0 ]]; then
    echo -e "${RED}❌ PHP version $PHP_VERSION is not supported. Please install PHP 8.2 or higher${NC}"
    exit 1
fi

echo -e "${GREEN}✅ PHP $PHP_VERSION detected${NC}"

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    echo -e "${RED}❌ Composer is not installed. Please install Composer${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Composer detected${NC}"

# Check if MySQL/MariaDB is running
if ! pgrep -x "mysqld" > /dev/null; then
    echo -e "${YELLOW}⚠️  MySQL/MariaDB is not running. Please start your database service${NC}"
fi

echo -e "${YELLOW}Step 2: Installing PHP dependencies...${NC}"
cd "$BACKEND_PATH"
composer install --no-dev --optimize-autoloader

echo -e "${YELLOW}Step 3: Generating application key...${NC}"
php artisan key:generate

echo -e "${YELLOW}Step 4: Setting up storage permissions...${NC}"
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R $(whoami):staff storage bootstrap/cache

echo -e "${YELLOW}Step 5: Creating symbolic link for storage...${NC}"
php artisan storage:link

echo -e "${YELLOW}Step 6: Database setup...${NC}"
echo "Please ensure your database 'zplus_shop' exists before proceeding."
read -p "Do you want to run database migrations? (y/N): " run_migrations

if [[ $run_migrations =~ ^[Yy]$ ]]; then
    php artisan migrate:fresh --seed
fi

echo -e "${YELLOW}Step 7: Configuring Nginx...${NC}"

# Check if Nginx is installed
if ! command -v nginx &> /dev/null; then
    echo -e "${RED}❌ Nginx is not installed. Installing via Homebrew...${NC}"
    if command -v brew &> /dev/null; then
        brew install nginx
    else
        echo -e "${RED}❌ Homebrew is not installed. Please install Nginx manually${NC}"
        exit 1
    fi
fi

# Nginx configuration path for macOS (Homebrew)
NGINX_SITES_PATH="/opt/homebrew/etc/nginx/servers"

# Create servers directory if it doesn't exist
sudo mkdir -p "$NGINX_SITES_PATH"

# Copy Nginx configuration
sudo cp "$NGINX_CONF_PATH" "$NGINX_SITES_PATH/"

echo -e "${YELLOW}Step 8: Adding domain to hosts file...${NC}"

# Add domain to hosts file if not exists
if ! grep -q "zplus.local" /etc/hosts; then
    echo "127.0.0.1    zplus.local" | sudo tee -a /etc/hosts
    echo -e "${GREEN}✅ Added zplus.local to hosts file${NC}"
else
    echo -e "${GREEN}✅ zplus.local already exists in hosts file${NC}"
fi

echo -e "${YELLOW}Step 9: Starting services...${NC}"

# Start PHP-FPM
if ! pgrep -x "php-fpm" > /dev/null; then
    sudo php-fpm -D
    echo -e "${GREEN}✅ PHP-FPM started${NC}"
else
    echo -e "${GREEN}✅ PHP-FPM is already running${NC}"
fi

# Test Nginx configuration
sudo nginx -t

if [ $? -eq 0 ]; then
    # Restart Nginx
    sudo nginx -s reload 2>/dev/null || sudo nginx
    echo -e "${GREEN}✅ Nginx configured and restarted${NC}"
else
    echo -e "${RED}❌ Nginx configuration test failed${NC}"
    exit 1
fi

echo -e "${GREEN}"
echo "🎉 Setup completed successfully!"
echo ""
echo "📝 Next steps:"
echo "1. Make sure MySQL/MariaDB is running"
echo "2. Create database 'zplus_shop' if not exists"
echo "3. Run migrations if you haven't: cd $BACKEND_PATH && php artisan migrate:fresh --seed"
echo "4. Visit: http://zplus.local"
echo "5. Admin panel: http://zplus.local/admin"
echo ""
echo "📋 Default admin credentials (after seeding):"
echo "Email: admin@example.com"
echo "Password: admin123"
echo -e "${NC}"
