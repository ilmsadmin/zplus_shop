<?php

namespace Zplus\ViPOS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Product\Models\Product;

class PosTransactionItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pos_transaction_id',
        'product_id',
        'product_name',
        'product_sku',
        'unit_price',
        'quantity',
        'discount_amount',
        'discount_percentage',
        'tax_amount',
        'tax_percentage',
        'subtotal',
        'total',
        'options',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'unit_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'options' => 'array',
    ];

    /**
     * Get the transaction that owns the item.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(PosTransaction::class, 'pos_transaction_id');
    }

    /**
     * Get the product that is associated with the item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate the subtotal for this item.
     * 
     * @return float
     */
    public function calculateSubtotal(): float
    {
        return $this->unit_price * $this->quantity;
    }

    /**
     * Calculate the discount amount for this item.
     * 
     * @return float
     */
    public function calculateDiscountAmount(): float
    {
        $subtotal = $this->calculateSubtotal();
        
        if ($this->discount_percentage > 0) {
            return $subtotal * ($this->discount_percentage / 100);
        }
        
        return $this->discount_amount;
    }

    /**
     * Calculate the tax amount for this item.
     * 
     * @return float
     */
    public function calculateTaxAmount(): float
    {
        $subtotal = $this->calculateSubtotal() - $this->calculateDiscountAmount();
        
        if ($this->tax_percentage > 0) {
            return $subtotal * ($this->tax_percentage / 100);
        }
        
        return $this->tax_amount;
    }

    /**
     * Calculate the total for this item.
     * 
     * @return float
     */
    public function calculateTotal(): float
    {
        $subtotal = $this->calculateSubtotal();
        $discount = $this->calculateDiscountAmount();
        $tax = $this->calculateTaxAmount();
        
        return $subtotal - $discount + $tax;
    }
}
