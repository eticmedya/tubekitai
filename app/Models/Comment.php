<?php

namespace App\Models;

use App\Enums\CommentCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'youtube_id',
        'author',
        'author_channel_id',
        'text',
        'like_count',
        'reply_count',
        'category',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'like_count' => 'integer',
            'reply_count' => 'integer',
            'category' => CommentCategory::class,
            'published_at' => 'datetime',
        ];
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * Get author's channel URL.
     */
    public function getAuthorUrlAttribute(): ?string
    {
        if (!$this->author_channel_id) {
            return null;
        }
        return "https://www.youtube.com/channel/{$this->author_channel_id}";
    }

    /**
     * Scope for category.
     */
    public function scopeOfCategory($query, CommentCategory $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for positive comments.
     */
    public function scopePositive($query)
    {
        return $query->whereIn('category', [
            CommentCategory::POSITIVE,
            CommentCategory::SUPPORTIVE,
        ]);
    }

    /**
     * Scope for negative comments.
     */
    public function scopeNegative($query)
    {
        return $query->whereIn('category', [
            CommentCategory::NEGATIVE,
            CommentCategory::CRITICISM,
            CommentCategory::TOXIC,
        ]);
    }
}
