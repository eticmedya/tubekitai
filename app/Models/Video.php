<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel_id',
        'youtube_id',
        'title',
        'description',
        'view_count',
        'like_count',
        'comment_count',
        'thumbnail_url',
        'duration',
        'duration_seconds',
        'tags',
        'category_id',
        'default_language',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'view_count' => 'integer',
            'like_count' => 'integer',
            'comment_count' => 'integer',
            'duration_seconds' => 'integer',
            'tags' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function commentAnalyses(): HasMany
    {
        return $this->hasMany(CommentAnalysis::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class);
    }

    public function latestCommentAnalysis(): HasOne
    {
        return $this->hasOne(CommentAnalysis::class)->latestOfMany();
    }

    /**
     * Get the video's YouTube URL.
     */
    public function getYoutubeUrlAttribute(): string
    {
        return "https://www.youtube.com/watch?v={$this->youtube_id}";
    }

    /**
     * Get formatted view count.
     */
    public function getFormattedViewsAttribute(): string
    {
        return $this->formatNumber($this->view_count);
    }

    /**
     * Get formatted like count.
     */
    public function getFormattedLikesAttribute(): string
    {
        return $this->formatNumber($this->like_count);
    }

    /**
     * Get engagement rate (likes/views * 100).
     */
    public function getEngagementRateAttribute(): float
    {
        if ($this->view_count === 0) {
            return 0;
        }
        return round(($this->like_count / $this->view_count) * 100, 2);
    }

    /**
     * Get formatted duration.
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_seconds) {
            return '0:00';
        }

        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);
        $seconds = $this->duration_seconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
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
}
