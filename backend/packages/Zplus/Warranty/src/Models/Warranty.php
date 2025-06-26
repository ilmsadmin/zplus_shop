<?php

namespace Zplus\Warranty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\Product;
use Webkul\User\Models\Admin;
use Zplus\ViPOS\Models\PosTransaction;

class Warranty extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'warranty_number',
        'warranty_package_id',
        'product_id',
        'product_serial',
        'product_name',
        'product_sku',
        'order_number',
        'pos_transaction_id',
        'customer_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'start_date',
        'end_date',
        'purchase_date',
        'status',
        'notes',
        'claim_history',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'purchase_date' => 'date',
        'claim_history' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($warranty) {
            // Generate a unique warranty number if not set
            if (! $warranty->warranty_number) {
                $warranty->warranty_number = 'WR-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }

    /**
     * Get the warranty package.
     */
    public function warrantyPackage(): BelongsTo
    {
        return $this->belongsTo(WarrantyPackage::class);
    }

    /**
     * Get the product associated with the warranty.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the customer associated with the warranty.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the POS transaction associated with the warranty.
     */
    public function posTransaction(): BelongsTo
    {
        return $this->belongsTo(PosTransaction::class);
    }

    /**
     * Get the admin who created the warranty.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Scope a query to only include active warranties.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include expired warranties.
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')->orWhere('end_date', '<', now());
    }

    /**
     * Scope a query to search by product serial.
     */
    public function scopeBySerial($query, $serial)
    {
        return $query->where('product_serial', 'like', '%' . $serial . '%');
    }

    /**
     * Scope a query to search by customer phone.
     */
    public function scopeByCustomerPhone($query, $phone)
    {
        return $query->where('customer_phone', 'like', '%' . $phone . '%');
    }

    /**
     * Check if warranty is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->end_date >= now();
    }

    /**
     * Check if warranty is expired.
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' || $this->end_date < now();
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'active' => 'badge-success',
            'expired' => 'badge-danger',
            'claimed' => 'badge-warning',
            'cancelled' => 'badge-secondary',
            default => 'badge-primary',
        };
    }

    /**
     * Get status text.
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'active' => 'Đang hiệu lực',
            'expired' => 'Đã hết hạn',
            'claimed' => 'Đã bảo hành',
            'cancelled' => 'Đã hủy',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get remaining days.
     */
    public function getRemainingDaysAttribute(): int
    {
        if ($this->isExpired()) {
            return 0;
        }
        
        return now()->diffInDays($this->end_date);
    }
}