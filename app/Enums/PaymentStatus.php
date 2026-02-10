<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => __('payment.status.pending'),
            self::PROCESSING => __('payment.status.processing'),
            self::COMPLETED => __('payment.status.completed'),
            self::FAILED => __('payment.status.failed'),
            self::REFUNDED => __('payment.status.refunded'),
            self::CANCELLED => __('payment.status.cancelled'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::PROCESSING => 'info',
            self::COMPLETED => 'success',
            self::FAILED => 'danger',
            self::REFUNDED => 'secondary',
            self::CANCELLED => 'secondary',
        };
    }

    public function isSuccessful(): bool
    {
        return $this === self::COMPLETED;
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::COMPLETED, self::FAILED, self::REFUNDED, self::CANCELLED]);
    }
}
