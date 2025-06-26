<?php

namespace Zplus\Warranty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarrantyPackage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'duration_months',
        'description',
        'is_active',
        'price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * Get the warranties that use this package.
     */
    public function warranties(): HasMany
    {
        return $this->hasMany(Warranty::class);
    }

    /**
     * Scope a query to only include active packages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get formatted duration text.
     */
    public function getDurationTextAttribute(): string
    {
        if ($this->duration_months == 1) {
            return '1 tháng';
        }
        
        return $this->duration_months . ' tháng';
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->price > 0) {
            return number_format($this->price, 0, ',', '.') . ' ₫';
        }
        
        return 'Miễn phí';
    }
}