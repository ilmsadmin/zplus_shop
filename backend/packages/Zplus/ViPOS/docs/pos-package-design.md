# POS Package Design Documentation

## Overview
Package POS là một hệ thống bán hàng tại điểm (Point of Sale) được thiết kế để tích hợp với hệ thống hiện tại, tương tự như package Sale về mặt dữ liệu.

## Database Schema

### Tables

#### pos_sessions
```sql
CREATE TABLE pos_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    status ENUM('open', 'closed') DEFAULT 'open',
    opening_balance DECIMAL(10,2) DEFAULT 0,
    closing_balance DECIMAL(10,2) DEFAULT 0,
    opened_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    closed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

#### pos_transactions
```sql
CREATE TABLE pos_transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    session_id INT NOT NULL,
    sale_id INT NOT NULL,
    payment_method ENUM('cash', 'card', 'transfer', 'other') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    reference_number VARCHAR(255),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES pos_sessions(id),
    FOREIGN KEY (sale_id) REFERENCES sales(id)
);
```

## UI/UX Design

### Layout Structure
- **Full-screen mode**: Không hiển thị sidebar menu của admin
- **Return button**: Nút quay về màn hình admin ở góc trên bên phải
- **Split screen**: Chia màn hình thành 2 phần chính

### Left Panel (Cart Section)
1. **Header**
   - Search box tìm kiếm sản phẩm (autocomplete)
   - Placeholder: "Tìm sản phẩm theo tên, SKU, barcode..."

2. **Cart Items**
   - Danh sách sản phẩm trong giỏ
   - Mỗi item hiển thị:
     - Tên sản phẩm
     - Đơn giá
     - Số lượng (có thể chỉnh sửa)
     - Thành tiền
     - Nút xóa

3. **Cart Summary**
   - Subtotal
   - Giảm giá:
     - Input cho giảm theo %
     - Input cho giảm theo số tiền
   - Thuế (VAT)
   - Total (tổng cộng)

4. **Actions**
   - Nút "Checkout" (primary button)
   - Nút "Tạo sản phẩm" (hiển thị khi giỏ hàng trống)

### Right Panel (Products & Customer)
1. **Header**
   - Customer search box với autocomplete
   - Nút "Tạo khách hàng" (quick create)
   - Hiển thị thông tin khách hàng đã chọn

2. **Category Filter**
   - Tabs hoặc dropdown để lọc theo category
   - Option "Tất cả" mặc định

3. **Product Grid**
   - Grid layout 5 columns
   - Product card bao gồm:
     - Hình ảnh sản phẩm
     - Tên sản phẩm
     - Giá bán
     - Tồn kho
   - Click để thêm vào giỏ
   - Sắp xếp: Sản phẩm mới nhất trước

## API Endpoints

### POS Sessions
- `GET /api/pos/sessions/current` - Lấy session hiện tại
- `POST /api/pos/sessions/open` - Mở session mới
- `POST /api/pos/sessions/close` - Đóng session

### POS Operations
- `GET /api/pos/products` - Lấy danh sách sản phẩm cho POS
- `GET /api/pos/categories` - Lấy danh sách categories
- `POST /api/pos/checkout` - Xử lý thanh toán
- `GET /api/pos/customers/search` - Tìm kiếm khách hàng
- `POST /api/pos/customers/quick-create` - Tạo nhanh khách hàng

## Controllers

### PosController
```php
class PosController extends Controller {
    public function index() // Hiển thị giao diện POS
    public function openSession() // Mở ca làm việc
    public function closeSession() // Đóng ca làm việc
    public function getCurrentSession() // Lấy thông tin ca hiện tại
}
```

### PosTransactionController
```php
class PosTransactionController extends Controller {
    public function checkout() // Xử lý thanh toán
    public function getProducts() // Lấy sản phẩm cho POS
    public function searchCustomers() // Tìm kiếm khách hàng
    public function quickCreateCustomer() // Tạo nhanh khách hàng
}
```

## Models

### PosSession
```php
class PosSession extends Model {
    protected $fillable = ['user_id', 'status', 'opening_balance', 'closing_balance', 'opened_at', 'closed_at'];
    
    public function user() { return $this->belongsTo(User::class); }
    public function transactions() { return $this->hasMany(PosTransaction::class); }
}
```

### PosTransaction
```php
class PosTransaction extends Model {
    protected $fillable = ['session_id', 'sale_id', 'payment_method', 'amount', 'reference_number', 'notes'];
    
    public function session() { return $this->belongsTo(PosSession::class); }
    public function sale() { return $this->belongsTo(Sale::class); }
}
```

## Frontend Components

**NOTE: DESIGN CHANGED - Đã chuyển từ Vue.js sang Blade Templates + Vanilla JavaScript**

### ~~Vue Components Structure~~ (DEPRECATED)
**OLD APPROACH - Đã loại bỏ Vue.js**
```
/resources/js/components/pos/
├── PosLayout.vue          // Main layout component
├── PosHeader.vue          // Header với nút back to admin
├── CartPanel.vue          // Panel bên trái
│   ├── ProductSearch.vue  // Search box sản phẩm
│   ├── CartItems.vue      // Danh sách items trong giỏ
│   └── CartSummary.vue    // Tổng kết giỏ hàng
├── ProductPanel.vue       // Panel bên phải
│   ├── CustomerSearch.vue // Search và quick create customer
│   ├── CategoryFilter.vue // Bộ lọc category
│   └── ProductGrid.vue    // Grid hiển thị sản phẩm
└── CheckoutModal.vue      // Modal xử lý thanh toán
```

### NEW APPROACH: Blade Templates + Vanilla JavaScript
```
/resources/views/admin/pos/
├── fullscreen.blade.php    // Main POS interface
├── partials/
│   ├── header.blade.php    // Header với nút back to admin
│   ├── cart-panel.blade.php // Panel bên trái (cart, search)
│   ├── product-panel.blade.php // Panel bên phải (products, categories)
│   ├── modals/
│   │   ├── payment.blade.php    // Modal thanh toán
│   │   ├── receipt.blade.php    // Modal hóa đơn
│   │   ├── customer.blade.php   // Modal tạo/chọn customer
│   │   └── session.blade.php    // Modal quản lý session
│   └── components/
│       ├── product-grid.blade.php
│       ├── cart-items.blade.php
│       └── customer-search.blade.php

/resources/assets/js/
├── pos.js                  // Main POS JavaScript logic
├── cart.js                 // Cart management
├── products.js             // Product search/filter
├── customers.js            // Customer management
└── checkout.js             // Checkout flow
```

## Routes

```php
Route::prefix('pos')->middleware(['auth'])->group(function () {
    Route::get('/', [PosController::class, 'index'])->name('pos.index');
    Route::post('/sessions/open', [PosController::class, 'openSession']);
    Route::post('/sessions/close', [PosController::class, 'closeSession']);
    Route::get('/sessions/current', [PosController::class, 'getCurrentSession']);
    
    Route::post('/checkout', [PosTransactionController::class, 'checkout']);
    Route::get('/products', [PosTransactionController::class, 'getProducts']);
    Route::get('/customers/search', [PosTransactionController::class, 'searchCustomers']);
    Route::post('/customers/quick-create', [PosTransactionController::class, 'quickCreateCustomer']);
});
```

## Security & Permissions

### Permissions
- `pos.access` - Quyền truy cập POS
- `pos.manage_sessions` - Quyền quản lý ca làm việc
- `pos.process_payment` - Quyền xử lý thanh toán

### Middleware
- Kiểm tra session đang mở trước khi cho phép giao dịch
- Validate quyền user cho từng action

## Integration với Sale Package

### Data Flow
1. POS tạo Sale record khi checkout
2. Sale items được tạo từ cart items
3. Payment information được lưu trong pos_transactions
4. Inventory được cập nhật tự động

### Shared Models
- Product
- Customer
- Sale
- SaleItem

## Performance Considerations

1. **Product Loading**
   - Lazy loading với pagination
   - Cache danh sách products và categories
   - Optimize image loading

2. **Real-time Updates**
   - WebSocket cho inventory updates
   - Optimistic UI updates

3. **Offline Support**
   - Local storage cho cart data
   - Queue mechanism cho offline transactions

## Future Enhancements

1. **Barcode Scanner Integration**
2. **Receipt Printer Support**
3. **Multiple Payment Methods per Transaction**
4. **Loyalty Program Integration**
5. **Staff Performance Reports**
6. **Cash Drawer Management**