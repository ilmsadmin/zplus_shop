# POS Package Installation Guide

## 1. Installation Steps

### Step 1: Copy Package Files
Copy the `packages/pos` directory to your project root.

### Step 2: Update Composer Autoload
Add to your main `composer.json`:
```json
"autoload": {
    "psr-4": {
        "App\\Packages\\Pos\\": "packages/pos/src/"
    }
}
```
Then run: `composer dump-autoload`

### Step 3: Register Service Provider
Add to `config/app.php` in the providers array:
```php
App\Packages\Pos\PosServiceProvider::class,
```

### Step 4: Run Migrations
```bash
php artisan migrate --path=packages/pos/database/migrations
```

### Step 5: Run Seeders
```bash
php artisan db:seed --class=App\\Packages\\Pos\\Database\\Seeders\\PosPermissionSeeder
```

### Step 6: Publish Assets
```bash
php artisan vendor:publish --tag=pos-config
php artisan vendor:publish --tag=pos-assets
```

### Step 7: Build Frontend Assets
Update your `webpack.mix.js`:
```javascript
mix.js('packages/pos/resources/js/pos.js', 'public/js')
   .sass('packages/pos/resources/sass/pos.scss', 'public/css');
```
Then run: `npm run dev` or `npm run production`

## 2. Integration with Main Application

### Add POS Menu to Sidebar
In your main sidebar view (`resources/views/layouts/partials/sidebar.blade.php`), add:
```blade
@can('pos.access')
<li class="nav-item">
    <a href="{{ route('pos.index') }}" class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-cash-register"></i>
        <p>
            POS
            @if(auth()->user()->currentPosSession())
                <span class="right badge badge-success">Đang mở</span>
            @endif
        </p>
    </a>
</li>
@endcan
```

### Update User Model
Add these relationships to your User model:
```php
use App\Packages\Pos\Models\PosSession;

public function posSessions()
{
    return $this->hasMany(PosSession::class);
}

public function currentPosSession()
{
    return $this->posSessions()->where('status', 'open')->latest()->first();
}
```

### Update Sale Model
Add relationship to your Sale model:
```php
use App\Packages\Pos\Models\PosTransaction;

public function posTransactions()
{
    return $this->hasMany(PosTransaction::class);
}
```

### Create Inventory Models (if not exists)
If you don't have inventory models, create:

**app/Models/Inventory.php**:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'product_id', 'quantity', 'reserved', 'reorder_level'
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
```

**app/Models/InventoryTransaction.php**:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $fillable = [
        'product_id', 'type', 'quantity', 'reference', 'user_id'
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

### Create Events (if needed)
**app/Events/LowStockAlert.php**:
```php
<?php

namespace App\Events;

use App\Models\Inventory;
use Illuminate\Foundation\Events\Dispatchable;

class LowStockAlert
{
    use Dispatchable;
    
    public $inventory;
    
    public function __construct(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }
}
```

## 3. Configuration

### POS Configuration
Edit `config/pos.php` to customize:
- Tax settings
- Session settings
- Receipt settings
- Product display settings

### Permissions
Make sure to assign POS permissions to appropriate roles:
- `pos.access` - Access POS system
- `pos.manage_sessions` - Manage POS sessions
- `pos.process_payment` - Process payments

## 4. Testing

### Test User Permissions
```php
// Assign cashier role to a user
$user = User::find(1);
$user->assignRole('cashier');
```

### Test POS Access
1. Login as a user with POS permissions
2. Navigate to `/pos`
3. Open a new session
4. Try adding products and processing a sale

## 5. Troubleshooting

### Common Issues

**Issue**: POS page shows 403 error
- **Solution**: Make sure user has `pos.access` permission

**Issue**: Cannot open session
- **Solution**: Check user has `pos.manage_sessions` permission

**Issue**: Products not showing
- **Solution**: 
  - Check products have `status = 'active'`
  - Check inventory records exist
  - Check API endpoint permissions

**Issue**: Checkout fails
- **Solution**:
  - Check inventory levels
  - Verify user has `pos.process_payment` permission
  - Check browser console for errors

### Debug Mode
Enable debug logging in `.env`:
```
POS_DEBUG=true
```

## 6. Maintenance

### Daily Tasks
- Monitor low stock alerts
- Review session reports
- Check for unclosed sessions

### Weekly Tasks
- Review sales reports
- Update product inventory
- Train new staff

### Monthly Tasks
- Analyze performance metrics
- Update tax rates if needed
- Review and optimize slow queries
