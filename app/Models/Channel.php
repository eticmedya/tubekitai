<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'youtube_id',
        'title',
        'description',
        'subscriber_count',
        'video_count',
        'view_count',
        'thumbnail_url',
        'custom_url',
        'country',
        'published_at',
        'analyzed_at',
    ];

    protected function casts(): array
    {
        return [
            'subscriber_count' => 'integer',
            'video_count' => 'integer',
            'view_count' => 'integer',
            'published_at' => 'datetime',
            'analyzed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function competitors(): HasMany
    {
        return $this->hasMany(Competitor::class);
    }

    /**
     * Get the channel's YouTube URL.
     */
    public function getYoutubeUrlAttribute(): string
    {
        if ($this->custom_url) {
            return "https://www.youtube.com/{$this->custom_url}";
        }
        return "https://www.youtube.com/channel/{$this->youtube_id}";
    }

    /**
     * Get formatted subscriber count.
     */
    public function getFormattedSubscribersAttribute(): string
    {
        return $this->formatNumber($this->subscriber_count);
    }

    /**
     * Get formatted view count.
     */
    public function getFormattedViewsAttribute(): string
    {
        return $this->formatNumber($this->view_count);
    }

    /**
     * Format large numbers.
     */
    protected function formatNumber(int $number): string
    {
        if ($number >= 1000000000) {
            return round($number / 1000000000, 1) . 'B';
        }
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        }
        if ($number >= 1000) {
            return round($number / 1000, 1) . 'K';
        }
        return (string) $number;
    }

    /**
     * Get average views per video.
     */
    public function getAverageViewsPerVideoAttribute(): float
    {
        if ($this->video_count === 0) {
            return 0;
        }
        return round($this->view_count / $this->video_count);
    }
}
