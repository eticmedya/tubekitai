<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NicheAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'interests',
        'lifestyle',
        'skills',
        'time_availability',
        'content_language',
        'target_audience',
        'recommendations',
        'competition_data',
        'monetization_potential',
        'suggested_niches',
        'content_ideas',
        'ai_summary',
        'credits_used',
        'model_used',
    ];

    protected function casts(): array
    {
        return [
            'interests' => 'array',
            'lifestyle' => 'array',
            'skills' => 'array',
            'recommendations' => 'array',
            'competition_data' => 'array',
            'monetization_potential' => 'array',
            'suggested_niches' => 'array',
            'content_ideas' => 'array',
            'credits_used' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get top recommended niche.
     */
    public function getTopNicheAttribute(): ?array
    {
        if (empty($this->suggested_niches)) {
            return null;
        }
        return $this->suggested_niches[0] ?? null;
    }

    /**
     * Get time availability label.
     */
    public function getTimeAvailabilityLabelAttribute(): string
    {
        return match ($this->time_availability) {
            'very_limited' => __('niche.time.very_limited'),
            'part_time' => __('niche.time.part_time'),
            'full_time' => __('niche.time.full_time'),
            'unlimited' => __('niche.time.unlimited'),
            default => $this->time_availability ?? __('niche.time.not_specified'),
        };
    }
}
