<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Credit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'operation_type',
        'description',
        'balance_after',
        'creditable_type',
        'creditable_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'balance_after' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creditable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Check if this is a deduction.
     */
    public function getIsDeductionAttribute(): bool
    {
        return $this->amount < 0;
    }

    /**
     * Check if this is an addition.
     */
    public function getIsAdditionAttribute(): bool
    {
        return $this->amount > 0;
    }

    /**
     * Get formatted amount with sign.
     */
    public function getFormattedAmountAttribute(): string
    {
        $sign = $this->amount >= 0 ? '+' : '';
        return $sign . number_format($this->amount, 1);
    }

    /**
     * Get operation type label.
     */
    public function getOperationTypeLabelAttribute(): string
    {
        return match ($this->operation_type) {
            'purchase' => __('credits.operation.purchase'),
            'usage' => __('credits.operation.usage'),
            'refund' => __('credits.operation.refund'),
            'bonus' => __('credits.operation.bonus'),
            'initial' => __('credits.operation.initial'),
            'adjustment' => __('credits.operation.adjustment'),
            default => $this->operation_type,
        };
    }

    /**
     * Scope for purchases.
     */
    public function scopePurchases($query)
    {
        return $query->where('operation_type', 'purchase');
    }

    /**
     * Scope for usage.
     */
    public function scopeUsage($query)
    {
        return $query->where('operation_type', 'usage');
    }
}
