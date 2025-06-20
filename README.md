<div align="center">
  <h1>🛒 ZPlus Shop</h1>
  <p><strong>Nền tảng E-commerce mạnh mẽ được xây dựng trên Bagisto Laravel Framework</strong></p>
  
  <p>
    <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white" alt="PHP Version">
    <img src="https://img.shields.io/badge/Laravel-11.0+-FF2D20?style=flat&logo=laravel&logoColor=white" alt="Laravel Version">
    <img src="https://img.shields.io/badge/Bagisto-2.2+-00D4AA?style=flat&logo=bagisto&logoColor=white" alt="Bagisto Version">
    <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License">
  </p>
</div>

---

## 📋 Mục lục

- [Giới thiệu](#-giới-thiệu)
- [Tính năng nổi bật](#-tính-năng-nổi-bật)
- [Yêu cầu hệ thống](#-yêu cầu-hệ-thống)
- [Cài đặt](#-cài-đặt)
- [Cấu hình](#-cấu-hình)
- [Sử dụng](#-sử-dụng)
- [API Documentation](#-api-documentation)
- [Cấu trúc dự án](#-cấu-trúc-dự-án)
- [Development](#-development)
- [Deployment](#-deployment)
- [Troubleshooting](#-troubleshooting)
- [Đóng góp](#-đóng-góp)
- [Giấy phép](#-giấy-phép)

## 🚀 Giới thiệu

**ZPlus Shop** là một nền tảng thương mại điện tử hiện đại và mạnh mẽ được xây dựng trên framework Bagisto Laravel. Được thiết kế để đáp ứng nhu cầu của các doanh nghiệp từ nhỏ đến lớn với khả năng mở rộng cao và giao diện người dùng thân thiện.

## ✨ Tính năng nổi bật

### 🏪 Quản lý cửa hàng
- **Multi-store**: Hỗ trợ quản lý nhiều cửa hàng
- **Multi-channel**: Bán hàng trên nhiều kênh khác nhau
- **Multi-currency**: Hỗ trợ nhiều loại tiền tệ
- **Multi-language**: Giao diện đa ngôn ngữ (20+ ngôn ngữ)

### 📦 Quản lý sản phẩm
- Catalog management với phân loại chi tiết
- Inventory tracking và stock management
- Product variants (size, color, etc.)
- Product reviews và ratings
- Advanced search và filtering

### 🛒 Trải nghiệm mua sắm
- Responsive design trên mọi thiết bị
- Shopping cart và wishlist
- Multiple payment gateways
- Flexible shipping methods
- Guest checkout option

### 📊 Analytics & Reports
- Sales analytics và reports
- Customer behavior tracking
- Inventory reports
- Revenue analytics

### 🔧 Quản trị
- Intuitive admin panel
- Role-based access control
- Customer management
- Order management
- Marketing tools (coupons, promotions)

## 💻 Yêu cầu hệ thống

### Server Requirements
- **PHP**: 8.2 hoặc cao hơn
- **Web Server**: Nginx hoặc Apache
- **Database**: MySQL 8.0+ hoặc MariaDB 10.6+
- **Memory**: Tối thiểu 2GB RAM (khuyến nghị 4GB+)
- **Storage**: Tối thiểu 5GB dung lượng trống

### PHP Extensions
```
- BCMath
- Calendar  
- Ctype
- cURL
- DOM
- Fileinfo
- Filter
- Hash
- Intl
- JSON
- Mbstring
- OpenSSL
- PCRE
- PDO
- PDO MySQL
- Session
- Tokenizer
- XML
```

### Development Tools
- **Node.js**: 18.0+ và NPM
- **Composer**: 2.0+
- **Git**: Để quản lý version control

## 🚀 Cài đặt

### Phương pháp 1: Cài đặt tự động (Khuyến nghị)

```bash
# Clone repository
git clone https://github.com/your-username/zplus_shop.git
cd zplus_shop

# Chạy script setup tự động
chmod +x setup.sh
./setup.sh
```

### Phương pháp 2: Cài đặt thủ công

#### Bước 1: Cài đặt dependencies

```bash
cd backend
composer install --optimize-autoloader --no-dev
npm install
```

#### Bước 2: Cấu hình môi trường

```bash
cp .env.example .env
php artisan key:generate
```

#### Bước 3: Cấu hình database

Chỉnh sửa file `.env`:

```env
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zplus_shop
DB_USERNAME=root
DB_PASSWORD=your_password

# Application Configuration
APP_NAME="ZPlus Shop"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://zplus.local

# Cache Configuration
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

#### Bước 4: Tạo database và migration

```bash
# Tạo database
mysql -u root -p -e "CREATE DATABASE zplus_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Chạy migrations và seeders
php artisan migrate:fresh --seed
# Compile frontend assets
npm run build
```

#### Bước 5: Cấu hình permissions

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
php artisan storage:link
```

#### Bước 6: Cấu hình web server

**Nginx Configuration:**

```bash
# Copy cấu hình Nginx
sudo cp nginx/zplus.local.conf /etc/nginx/sites-available/zplus.local
sudo ln -s /etc/nginx/sites-available/zplus.local /etc/nginx/sites-enabled/

# Hoặc trên macOS với Homebrew:
sudo cp nginx/zplus.local.conf /opt/homebrew/etc/nginx/servers/
```

#### Bước 7: Cấu hình hosts file

```bash
echo "127.0.0.1    zplus.local" | sudo tee -a /etc/hosts
```

#### Bước 8: Khởi động services

```bash
# Khởi động PHP-FPM
sudo php-fpm -D

# Test và reload Nginx
sudo nginx -t
sudo nginx -s reload

# Hoặc restart Nginx
sudo systemctl restart nginx
```

## ⚙️ Cấu hình

### Cấu hình nâng cao

#### Email Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@zplus.local
MAIL_FROM_NAME="ZPlus Shop"
```

#### Queue Configuration (Production)
```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

#### Payment Gateway Configuration
```env
# PayPal
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_CLIENT_SECRET=your_paypal_client_secret
PAYPAL_MODE=sandbox # hoặc live

# Stripe
STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret
```

## 🎯 Sử dụng

### Truy cập ứng dụng

- **🏠 Trang chủ**: http://zplus.local
- **👨‍💼 Admin Panel**: http://zplus.local/admin
- **📊 GraphQL Playground**: http://zplus.local/graphiql

### Thông tin đăng nhập mặc định

**Super Admin:**
- 📧 Email: `admin@example.com`
- 🔑 Password: `admin123`

**Demo Customer:**
- 📧 Email: `customer@example.com`  
- 🔑 Password: `password`

### Các tính năng chính

#### 🛍️ Frontend Features
- Product browsing và search
- Shopping cart management
- User registration và login
- Order tracking
- Wishlist management
- Product reviews

#### ⚡ Admin Features
- Dashboard với analytics
- Product management
- Order management  
- Customer management
- Marketing tools
- System configuration

## 📚 API Documentation

ZPlus Shop cung cấp GraphQL API mạnh mẽ cho việc tích hợp và phát triển mobile app.

### GraphQL Endpoints
- **Main API**: `http://zplus.local/graphql`
- **Admin API**: `http://zplus.local/admin/graphql`

### Authentication
```graphql
mutation {
  customerLogin(input: {
    email: "customer@example.com"
    password: "password"
  }) {
    success
    customer {
      id
      firstName
      lastName
      email
    }
  }
}
```

### Sample Queries
```graphql
# Lấy danh sách sản phẩm
query {
  products {
    data {
      id
      name
      price
      images {
        url
      }
    }
  }
}
```

## 🏗️ Cấu trúc dự án

```
zplus_shop/
├── 📁 backend/                 # Laravel Bagisto application
│   ├── 📁 app/                # Application logic
│   │   ├── 📁 Http/           # Controllers, Middleware, Requests
│   │   ├── 📁 Models/         # Eloquent models  
│   │   └── 📁 Providers/      # Service providers
│   ├── 📁 config/             # Configuration files
│   ├── 📁 database/           # Migrations, seeders, factories
│   ├── 📁 packages/           # Custom Bagisto packages
│   │   └── 📁 Webkul/         # Webkul packages
│   ├── 📁 public/             # Public assets
│   ├── 📁 resources/          # Views, assets, lang files
│   ├── 📁 routes/             # Route definitions
│   ├── 📁 storage/            # Storage files, logs, cache
│   └── 📁 tests/              # Test files
├── 📁 nginx/                   # Nginx configuration
│   ├── 📄 zplus.local.conf    # Main domain config
│   └── 📄 lin.local.conf      # Alternative domain config  
├── 📄 setup.sh                # Auto setup script
└── 📄 README.md               # Project documentation
```

## 🛠️ Development

### Local Development Setup

```bash
# Khởi động development server
php artisan serve --host=0.0.0.0 --port=8000

# Compile assets cho development
npm run dev

# Watch for file changes
npm run watch

# Hot module replacement
npm run hot
```

### Useful Artisan Commands

```bash
# Cache management
php artisan cache:clear
php artisan config:clear  
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# Database operations
php artisan migrate:status
php artisan migrate:rollback
php artisan db:seed

# Queue management
php artisan queue:work
php artisan queue:restart
php artisan horizon

# Search indexing
php artisan scout:import "App\Models\Product"
```

### Code Quality Tools

```bash
# PHP code formatting
./vendor/bin/pint

# Run tests
php artisan test
./vendor/bin/pest

# Static analysis
./vendor/bin/phpstan analyse
```

## 🚀 Deployment

### Production Deployment

#### 1. Server Setup
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install nginx mysql-server php8.2-fpm php8.2-mysql php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-mbstring redis-server
```

#### 2. Deploy Application
```bash
# Clone project
git clone https://github.com/your-username/zplus_shop.git /var/www/zplus_shop
cd /var/www/zplus_shop/backend

# Install dependencies
composer install --optimize-autoloader --no-dev
npm ci && npm run build

# Set permissions
sudo chown -R www-data:www-data /var/www/zplus_shop
sudo chmod -R 755 /var/www/zplus_shop
sudo chmod -R 775 storage bootstrap/cache
```

#### 3. Production Configuration
```bash
# Environment
cp .env.example .env
# Edit .env with production values

# Generate key và cache
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 4. Process Management (PM2/Supervisor)
```bash
# Install PM2 for queue workers
npm install -g pm2

# Start queue workers
pm2 start --name "zplus-queue" --interpreter="php" -- artisan queue:work --sleep=3 --tries=3
pm2 startup
pm2 save
```

### Docker Deployment

```dockerfile
# Dockerfile example
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application
COPY . /var/www/html
WORKDIR /var/www/html

# Install dependencies
RUN composer install --optimize-autoloader --no-dev
```

## 🔧 Troubleshooting

### Common Issues và Solutions

#### 1. Permission Errors
```bash
# Fix storage permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# SELinux context (if applicable)  
sudo setsebool -P httpd_can_network_connect 1
sudo chcon -R -t httpd_exec_t storage/
```

#### 2. Database Connection Issues
```bash
# Check MySQL service
sudo systemctl status mysql

# Test connection
mysql -u root -p -e "SHOW DATABASES;"

# Reset MySQL password
sudo mysql_secure_installation
```

#### 3. Nginx Configuration Issues
```bash
# Test configuration
sudo nginx -t

# Check error logs
sudo tail -f /var/log/nginx/error.log

# Restart services
sudo systemctl restart nginx php8.2-fpm
```

#### 4. Performance Issues
```bash
# Enable OPcache
echo "opcache.enable=1" >> /etc/php/8.2/fpm/php.ini

# Optimize database
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Redis cache
php artisan cache:clear
php artisan config:cache
```

#### 5. SSL/HTTPS Setup
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Generate SSL certificate
sudo certbot --nginx -d zplus.yourdomain.com

# Auto renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

### Debug Mode

```bash
# Enable debug mode (chỉ dùng trong development)
APP_DEBUG=true

# View logs
tail -f storage/logs/laravel.log

# Database query logging
DB_LOG_QUERIES=true
```
## 📈 Monitoring & Analytics

### System Monitoring
```bash
# Monitor system resources
htop
df -h
free -m

# Monitor MySQL
mysql -u root -p -e "SHOW PROCESSLIST;"
mysql -u root -p -e "SHOW ENGINE INNODB STATUS\G"

# Monitor Nginx
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/nginx/error.log
```

### Application Performance
```bash
# Laravel Telescope (Development)
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate

# Query optimization
php artisan optimize
php artisan config:cache
php artisan route:cache
```

## 🧪 Testing

### Unit Tests
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

### Browser Testing
```bash
# Install Laravel Dusk
composer require --dev laravel/dusk
php artisan dusk:install

# Run browser tests
php artisan dusk
```

## 🤝 Đóng góp

Chúng tôi hoan nghênh mọi đóng góp để cải thiện ZPlus Shop!

### Quy trình đóng góp

1. **Fork** repository này
2. **Tạo** feature branch (`git checkout -b feature/AmazingFeature`)
3. **Commit** thay đổi (`git commit -m 'Add some AmazingFeature'`)
4. **Push** lên branch (`git push origin feature/AmazingFeature`)
5. **Tạo** Pull Request

### Coding Standards

```bash
# Format code với Laravel Pint
./vendor/bin/pint

# Check code style
./vendor/bin/pint --test

# Static analysis
./vendor/bin/phpstan analyse
```

### Bug Reports

Khi báo cáo bug, vui lòng cung cấp:
- **Mô tả** chi tiết về vấn đề
- **Các bước** để tái tạo lỗi
- **Kết quả mong đợi** vs **kết quả thực tế**
- **Screenshots** (nếu có)
- **Environment details** (PHP version, OS, etc.)

## 📞 Hỗ trợ

### Community Support
- 💬 **GitHub Discussions**: [Thảo luận](https://github.com/your-username/zplus_shop/discussions)
- 🐛 **Issues**: [Báo lỗi](https://github.com/your-username/zplus_shop/issues)
- 📧 **Email**: support@zplus.com

### Documentation
- 📖 **Wiki**: [Tài liệu chi tiết](https://github.com/your-username/zplus_shop/wiki)
- 🎥 **Video Tutorials**: [YouTube Channel](https://youtube.com/zplus-shop)
- 📚 **API Docs**: [GraphQL Documentation](https://zplus.local/graphiql)

### Enterprise Support
Để được hỗ trợ enterprise và custom development, vui lòng liên hệ: enterprise@zplus.com

## 🌟 Roadmap

### Q2 2025
- [ ] Progressive Web App (PWA) support
- [ ] Advanced analytics dashboard
- [ ] Multi-vendor marketplace features
- [ ] AI-powered product recommendations

### Q3 2025  
- [ ] Mobile app (React Native)
- [ ] Advanced inventory management
- [ ] B2B wholesale features
- [ ] Enhanced SEO tools

### Q4 2025
- [ ] Headless commerce APIs
- [ ] Advanced marketing automation
- [ ] Machine learning integration
- [ ] International expansion tools

## 🏆 Contributors

<a href="https://github.com/your-username/zplus_shop/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=your-username/zplus_shop" />
</a>

## 🙏 Acknowledgments

- [Bagisto](https://bagisto.com/) - The amazing Laravel E-commerce framework
- [Laravel](https://laravel.com/) - The elegant PHP framework
- [Vue.js](https://vuejs.org/) - The progressive JavaScript framework
- All our amazing contributors và community members

## 📄 Giấy phép

Dự án này được phân phối dưới giấy phép MIT. Xem file [LICENSE](LICENSE) để biết thêm chi tiết.

---

<div align="center">
  <p>
    <strong>Được phát triển với ❤️ bởi ZPlus Team</strong>
  </p>
  <p>
    <a href="https://github.com/your-username/zplus_shop">⭐ Star us on GitHub</a> |
    <a href="https://twitter.com/zplus_shop">🐦 Follow on Twitter</a> |
    <a href="https://linkedin.com/company/zplus">💼 LinkedIn</a>
  </p>
</div>
