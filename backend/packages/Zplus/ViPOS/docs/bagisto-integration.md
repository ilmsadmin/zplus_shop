# Tích hợp POS với Bagisto - Hướng dẫn Sử dụng

## Tổng quan

Tính năng này tự động tạo đơn hàng trong hệ thống Bagisto khi giao dịch POS được hoàn thành thành công. Điều này giúp đồng bộ hóa dữ liệu bán hàng giữa POS và hệ thống quản lý đơn hàng chính.

## Cách thức hoạt động

### 1. Quy trình tự động
Khi một giao dịch POS được hoàn thành:

1. **POS Transaction** được tạo và lưu vào database
2. **Cash Movement** được ghi nhận
3. **BagistoOrderService** được gọi để tạo order tương ứng trong Bagisto
4. **bagisto_order_id** được cập nhật vào POS transaction để liên kết

### 2. Mapping dữ liệu

#### Customer Data
- **Khách hàng có tài khoản**: Sử dụng thông tin customer từ POS transaction
- **Khách lẻ**: Tạo guest order với thông tin mặc định:
  - Email: `guest@pos.local`
  - First Name: `POS`
  - Last Name: `Guest`

#### Address Data
- **Khách hàng có địa chỉ**: Sử dụng default address hoặc address đầu tiên
- **Khách lẻ**: Sử dụng địa chỉ cửa hàng mặc định

#### Payment Methods
Mapping phương thức thanh toán POS sang Bagisto:
- `cash` → `cashondelivery`
- `card` → `moneytransfer`
- `bank_transfer` → `moneytransfer`
- `other` → `moneytransfer`

#### Items Data
- Lấy thông tin sản phẩm từ POS transaction items
- Tính toán giá, số lượng, thuế, discount tương ứng

## Cấu trúc File

### Service Class
```
/packages/Zplus/ViPOS/src/Services/BagistoOrderService.php
```

**Phương thức chính:**
- `createOrderFromPosTransaction()`: Tạo order từ POS transaction
- `prepareCustomerData()`: Chuẩn bị dữ liệu khách hàng
- `prepareItemsData()`: Chuẩn bị dữ liệu sản phẩm
- `prepareAddressData()`: Chuẩn bị dữ liệu địa chỉ
- `preparePaymentData()`: Chuẩn bị dữ liệu thanh toán

### Database Migration
```
/packages/Zplus/ViPOS/database/migrations/2024_01_19_000004_add_bagisto_order_id_to_pos_transactions_table.php
```

Thêm cột `bagisto_order_id` vào bảng `pos_transactions` với foreign key constraint.

### Model Relationship
```php
// PosTransaction Model
public function bagistoOrder(): BelongsTo
{
    return $this->belongsTo(Order::class, 'bagisto_order_id');
}
```

## Sử dụng

### 1. Trong Controller
```php
// Tích hợp trong PosTransactionController->checkout()
try {
    $bagistoOrder = $this->bagistoOrderService->createOrderFromPosTransaction($transaction);
    
    if ($bagistoOrder) {
        Log::info('Bagisto order created successfully', [
            'pos_transaction_id' => $transaction->id,
            'bagisto_order_id' => $bagistoOrder->id
        ]);
    }
} catch (\Exception $e) {
    Log::error('Error creating Bagisto order', [
        'pos_transaction_id' => $transaction->id,
        'error' => $e->getMessage()
    ]);
}
```

### 2. Kiểm tra Tình trạng Tích hợp
```php
// Kiểm tra transaction có order trong Bagisto không
$transaction = PosTransaction::find(1);

if ($transaction->bagisto_order_id) {
    $bagistoOrder = $transaction->bagistoOrder;
    echo "Order ID: " . $bagistoOrder->increment_id;
    echo "Status: " . $bagistoOrder->status;
}
```

### 3. API Response
Khi checkout thành công, API trả về:
```json
{
    "success": true,
    "message": "Giao dịch đã được hoàn thành thành công. Đã tạo đơn hàng trong hệ thống Bagisto.",
    "transaction": {
        "id": 123,
        "transaction_number": "TX-20240119-ABC123",
        "total_amount": 100.00,
        "bagisto_order_id": 456,
        "print_url": "...",
        "download_url": "..."
    }
}
```

## Error Handling

### 1. Logging
Tất cả lỗi được ghi log với chi tiết:
- **Success**: `Log::info()` khi tạo order thành công
- **Warning**: `Log::warning()` khi service trả về null
- **Error**: `Log::error()` khi có exception

### 2. Graceful Degradation
- Nếu việc tạo Bagisto order thất bại, POS transaction vẫn thành công
- Không ảnh hưởng đến workflow POS chính
- User vẫn nhận được thông báo thành công của POS

### 3. Retry Logic
Hiện tại không có retry logic tự động. Có thể implement:
- Queue job cho việc tạo order
- Retry failed jobs
- Manual sync command

## Configuration

### Environment Variables
Không cần cấu hình thêm, sử dụng cấu hình Bagisto hiện có:
- Channel configuration
- Currency configuration  
- Payment method configuration

### Dependencies
Service được inject các repository:
- `OrderRepository`
- `CustomerRepository`
- `ChannelRepository`
- `CurrencyRepository`
- `ProductRepository`

## Testing

### 1. Feature Tests
```bash
php artisan test packages/Zplus/ViPOS/tests/Feature/BagistoOrderIntegrationTest.php
```

### 2. Manual Testing
1. Tạo session POS
2. Thực hiện checkout với sản phẩm
3. Kiểm tra transaction trong POS
4. Kiểm tra order trong Bagisto admin
5. Verify dữ liệu mapping chính xác

## Troubleshooting

### Lỗi thường gặp:

#### 1. Foreign Key Constraint Error
```
SQLSTATE[23000]: Integrity constraint violation
```
**Giải pháp**: Chạy migration để tạo foreign key constraint

#### 2. Missing Product Data
```
Product not found for ID: xxx
```
**Giải pháp**: Đảm bảo product_id trong POS items tồn tại trong products table

#### 3. Invalid Channel/Currency
```
Channel or currency not found
```
**Giải pháp**: Kiểm tra cấu hình channel và currency trong Bagisto

### Debug Commands
```bash
# Kiểm tra logs
tail -f storage/logs/laravel.log | grep "Bagisto order"

# Kiểm tra database
mysql> SELECT * FROM pos_transactions WHERE bagisto_order_id IS NOT NULL;
mysql> SELECT * FROM orders WHERE id IN (SELECT bagisto_order_id FROM pos_transactions);
```

## Future Enhancements

### 1. Order Status Sync
- Đồng bộ status giữa POS và Bagisto
- Update POS khi order status thay đổi trong Bagisto

### 2. Inventory Sync
- Cập nhật inventory khi POS sale
- Prevent overselling

### 3. Queue Processing
- Process order creation trong background
- Improved performance cho POS

### 4. Manual Sync
- Admin command để sync các transaction chưa có order
- Bulk sync functionality

### 5. Advanced Mapping
- Custom address mapping
- Advanced product options mapping
- Tax calculation sync
