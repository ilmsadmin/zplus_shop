# POS API Documentation

## Authentication
All endpoints require authentication via Laravel Sanctum.
Include the authentication token in the header:
```
Authorization: Bearer {token}
```

## Endpoints

### Session Management

#### Get Current Session
```
GET /api/pos/sessions/current
```
**Response:**
```json
{
    "success": true,
    "session": {
        "id": 1,
        "user": {
            "id": 1,
            "name": "John Doe"
        },
        "status": "open",
        "opening_balance": "50000.00",
        "opened_at": "2024-01-01T08:00:00Z",
        "transactions_count": 5,
        "total_sales": "250000.00"
    }
}
```

#### Open Session
```
POST /api/pos/sessions/open
```
**Request:**
```json
{
    "opening_balance": 50000
}
```
**Response:**
```json
{
    "success": true,
    "message": "Ca làm việc đã được mở",
    "session": { /* session data */ }
}
```

#### Close Session
```
POST /api/pos/sessions/close
```
**Request:**
```json
{
    "closing_balance": 300000,
    "notes": "Ca làm việc bình thường"
}
```

### Products

#### Get Products
```
GET /api/pos/products
```
**Query Parameters:**
- `page` (optional): Page number
- `per_page` (optional): Items per page (default: 20)
- `category_id` (optional): Filter by category
- `search` (optional): Search by name, SKU, or barcode

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "name": "Product Name",
            "sku": "SKU001",
            "price": "50000.00",
            "category": {
                "id": 1,
                "name": "Category Name"
            },
            "inventory": {
                "quantity": 100,
                "available": 95,
                "low_stock": false
            },
            "image": "http://example.com/storage/products/image.jpg",
            "can_sell": true
        }
    ],
    "meta": {
        "current_page": 1,
        "total": 100
    }
}
```

#### Get Categories
```
GET /api/pos/categories
```

### Customer Management

#### Search Customers
```
GET /api/pos/customers/search?search=john
```
**Response:**
```json
[
    {
        "id": 1,
        "name": "John Doe",
        "phone": "0123456789",
        "email": "john@example.com"
    }
]
```

#### Quick Create Customer
```
POST /api/pos/customers/quick-create
```
**Request:**
```json
{
    "name": "Jane Doe",
    "phone": "0987654321",
    "email": "jane@example.com"
}
```

### Checkout

#### Process Checkout
```
POST /api/pos/checkout
```
**Request:**
```json
{
    "customer_id": 1,
    "items": [
        {
            "product_id": 1,
            "quantity": 2,
            "price": 50000
        }
    ],
    "discount_type": "percentage",
    "discount_value": 10,
    "payment_method": "cash",
    "amount_paid": 100000,
    "reference_number": null
}
```
**Response:**
```json
{
    "success": true,
    "message": "Thanh toán thành công",
    "sale": {
        "id": 123,
        "sale_number": "POS-20240101-001",
        "total": "90000.00"
    },
    "transaction": {
        "id": 456,
        "payment_method": "cash",
        "amount": "100000.00"
    }
}
```

## Error Responses

All error responses follow this format:
```json
{
    "success": false,
    "message": "Error message here",
    "errors": {
        "field_name": ["Validation error message"]
    }
}
```

### Common Error Codes
- `400` - Bad Request (validation errors)
- `401` - Unauthorized
- `403` - Forbidden (missing permissions)
- `404` - Not Found
- `422` - Unprocessable Entity
- `500` - Server Error

## Rate Limiting
API requests are limited to 60 requests per minute per user.

## Webhooks (Future)
Webhooks for real-time inventory updates will be available in future versions.
