<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreditPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'description',
        'credits',
        'price',
        'currency',
        'is_popular',
        'is_active',
        'sort_order',
        'features',
    ];

    protected function casts(): array
    {
        return [
            'credits' => 'decimal:2',
            'price' => 'integer',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'features' => 'array',
        ];
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price / 100, 2, ',', '.') . ' ₺';
    }

    /**
     * Get price per credit.
     */
    public function getPricePerCreditAttribute(): float
    {
        if ($this->credits == 0) {
            return 0;
        }
        return round(($this->price / 100) / $this->credits, 2);
    }

    /**
     * Scope for active packages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered packages.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
