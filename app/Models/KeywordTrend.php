<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeywordTrend extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword',
        'region',
        'language',
        'search_volume',
        'competition_level',
        'competition_score',
        'trend_score',
        'related_keywords',
        'search_trend',
        'last_updated',
    ];

    protected function casts(): array
    {
        return [
            'search_volume' => 'integer',
            'competition_score' => 'decimal:2',
            'trend_score' => 'decimal:2',
            'related_keywords' => 'array',
            'search_trend' => 'array',
            'last_updated' => 'datetime',
        ];
    }

    /**
     * Check if keyword is trending up.
     */
    public function getIsTrendingUpAttribute(): bool
    {
        return $this->trend_score > 0;
    }

    /**
     * Get trend label.
     */
    public function getTrendLabelAttribute(): string
    {
        return match (true) {
            $this->trend_score >= 50 => __('trends.hot'),
            $this->trend_score >= 20 => __('trends.rising'),
            $this->trend_score >= 0 => __('trends.stable'),
            $this->trend_score >= -20 => __('trends.declining'),
            default => __('trends.cold'),
        };
    }

    /**
     * Get competition label.
     */
    public function getCompetitionLabelAttribute(): string
    {
        return match ($this->competition_level) {
            'low' => __('trends.competition.low'),
            'medium' => __('trends.competition.medium'),
            'high' => __('trends.competition.high'),
            default => $this->competition_level ?? __('trends.competition.unknown'),
        };
    }

    /**
     * Scope for region.
     */
    public function scopeForRegion($query, string $region)
    {
        return $query->where('region', $region);
    }

    /**
     * Scope for trending keywords.
     */
    public function scopeTrending($query)
    {
        return $query->where('trend_score', '>', 0)->orderByDesc('trend_score');
    }

    /**
     * Scope for low competition.
     */
    public function scopeLowCompetition($query)
    {
        return $query->where('competition_level', 'low');
    }
}
