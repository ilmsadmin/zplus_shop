# Lin Shop - Bagisto E-commerce

## Yêu cầu hệ thống

- PHP 8.2 hoặc cao hơn
- Composer
- MySQL/MariaDB
- Nginx
- Node.js & NPM (cho frontend assets)

## Cài đặt nhanh

### 1. Chạy script setup tự động

```bash
chmod +x setup.sh
./setup.sh
```

### 2. Cài đặt thủ công

#### Bước 1: Cài đặt dependencies

```bash
cd backend
composer install
```

#### Bước 2: Cấu hình môi trường

```bash
cp .env.example .env
php artisan key:generate
```

#### Bước 3: Cấu hình database trong file .env

```env
DB_DATABASE=zplus_shop
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### Bước 4: Tạo database và chạy migrations

```bash
# Tạo database
mysql -u root -p -e "CREATE DATABASE zplus_shop;"

# Chạy migrations
php artisan migrate:fresh --seed
```

#### Bước 5: Cấu hình permissions

```bash
chmod -R 775 storage bootstrap/cache
php artisan storage:link
```

#### Bước 6: Cấu hình Nginx

Copy file cấu hình Nginx:

```bash
sudo cp nginx/lin.local.conf /opt/homebrew/etc/nginx/servers/
```

#### Bước 7: Thêm domain vào hosts file

```bash
echo "127.0.0.1    lin.local" | sudo tee -a /etc/hosts
```

#### Bước 8: Khởi động services

```bash
# Start PHP-FPM
sudo php-fpm -D

# Test và reload Nginx
sudo nginx -t
sudo nginx -s reload
```

## Truy cập website

- **Frontend**: http://lin.local
- **Admin Panel**: http://lin.local/admin

## Thông tin đăng nhập mặc định

**Admin:**
- Email: admin@example.com
- Password: admin123

## Cấu trúc thư mục

```
zplus_shop/
├── backend/                # Laravel Bagisto application
├── nginx/                  # Nginx configuration files
│   ├── lin.local.conf     # Current domain config
│   └── zplus.local.conf   # Old domain config
├── setup.sh               # Auto setup script
└── README.md              # This file
```

## Commands hữu ích

### Cache management
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Asset compilation
```bash
npm install
npm run build
```

### Queue workers (nếu sử dụng)
```bash
php artisan queue:work
```

## Troubleshooting

### 1. Permission errors
```bash
sudo chown -R $(whoami):staff storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 2. Nginx không start
```bash
# Check configuration
sudo nginx -t

# Check if port 80 is in use
sudo lsof -i :80

# Restart Nginx
sudo nginx -s stop
sudo nginx
```

### 3. PHP-FPM issues
```bash
# Check if PHP-FPM is running
ps aux | grep php-fpm

# Restart PHP-FPM
sudo killall php-fpm
sudo php-fpm -D
```

### 4. Database connection issues
- Đảm bảo MySQL/MariaDB đang chạy
- Kiểm tra thông tin database trong file `.env`
- Tạo database nếu chưa tồn tại

## Support

Nếu gặp vấn đề, vui lòng kiểm tra:
1. Log files tại `storage/logs/`
2. Nginx error logs tại `/var/log/nginx/`
3. PHP-FPM logs

## Development

### Compile assets for development
```bash
npm run dev
```

### Watch for changes
```bash
npm run watch
```
