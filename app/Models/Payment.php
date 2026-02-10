<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'credit_package_id',
        'merchant_oid',
        'amount',
        'currency',
        'credits_purchased',
        'status',
        'paytr_token',
        'paytr_response',
        'payment_method',
        'card_last_four',
        'failure_reason',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'credits_purchased' => 'decimal:2',
            'status' => PaymentStatus::class,
            'paytr_response' => 'array',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creditPackage(): BelongsTo
    {
        return $this->belongsTo(CreditPackage::class);
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount / 100, 2, ',', '.') . ' ₺';
    }

    /**
     * Check if payment is successful.
     */
    public function isSuccessful(): bool
    {
        return $this->status === PaymentStatus::COMPLETED;
    }

    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === PaymentStatus::PENDING;
    }

    /**
     * Mark as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => PaymentStatus::COMPLETED,
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark as failed.
     */
    public function markAsFailed(string $reason = null): void
    {
        $this->update([
            'status' => PaymentStatus::FAILED,
            'failure_reason' => $reason,
        ]);
    }

    /**
     * Scope for successful payments.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', PaymentStatus::COMPLETED);
    }

    /**
     * Scope for pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', PaymentStatus::PENDING);
    }

    /**
     * Generate unique merchant order ID.
     */
    public static function generateMerchantOid(): string
    {
        return 'TK' . time() . str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}
