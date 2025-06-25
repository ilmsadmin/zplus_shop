<?php

namespace Zplus\ViPOS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\User\Models\Admin;

class PosCashMovement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pos_session_id',
        'user_id',
        'amount',
        'type',
        'reference',
        'description',
        'pos_transaction_id',
        'movement_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'movement_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($movement) {
            // Set the movement_at timestamp if not set
            if (! $movement->movement_at) {
                $movement->movement_at = now();
            }
        });
    }

    /**
     * Get the session that owns the cash movement.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(PosSession::class, 'pos_session_id');
    }    /**
     * Get the user that created the cash movement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the transaction associated with the cash movement.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(PosTransaction::class, 'pos_transaction_id');
    }

    /**
     * Scope a query to only include cash in movements.
     */
    public function scopeCashIn($query)
    {
        return $query->where('type', 'cash_in');
    }

    /**
     * Scope a query to only include cash out movements.
     */
    public function scopeCashOut($query)
    {
        return $query->where('type', 'cash_out');
    }

    /**
     * Scope a query to only include sale movements.
     */
    public function scopeSale($query)
    {
        return $query->where('type', 'sale');
    }

    /**
     * Scope a query to only include refund movements.
     */
    public function scopeRefund($query)
    {
        return $query->where('type', 'refund');
    }

    /**
     * Check if the movement is a cash in.
     */
    public function isCashIn(): bool
    {
        return $this->type === 'cash_in';
    }

    /**
     * Check if the movement is a cash out.
     */
    public function isCashOut(): bool
    {
        return $this->type === 'cash_out';
    }

    /**
     * Check if the movement is a sale.
     */
    public function isSale(): bool
    {
        return $this->type === 'sale';
    }

    /**
     * Check if the movement is a refund.
     */
    public function isRefund(): bool
    {
        return $this->type === 'refund';
    }
}
