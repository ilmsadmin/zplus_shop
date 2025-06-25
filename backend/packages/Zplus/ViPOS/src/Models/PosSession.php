<?php

namespace Zplus\ViPOS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\User\Models\Admin;

class PosSession extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'store_id',
        'opening_balance',
        'closing_balance',
        'total_sales',
        'total_cash',
        'total_card',
        'total_other',
        'transaction_count',
        'opened_at',
        'closed_at',
        'notes',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'opening_balance' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'total_cash' => 'decimal:2',
        'total_card' => 'decimal:2',
        'total_other' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];    /**
     * Get the user that owns the session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the transactions for the session.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(PosTransaction::class);
    }

    /**
     * Get the cash movements for the session.
     */
    public function cashMovements(): HasMany
    {
        return $this->hasMany(PosCashMovement::class);
    }

    /**
     * Scope a query to only include open sessions.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope a query to only include closed sessions.
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Check if the session is open.
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Check if the session is closed.
     */
    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    /**
     * Close the session.
     */
    public function close(float $closingBalance, ?string $notes = null): bool
    {
        $this->closing_balance = $closingBalance;
        $this->closed_at = now();
        $this->status = 'closed';
        $this->notes = $notes;
        
        return $this->save();
    }
}
