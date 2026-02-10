<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIGeneration extends Model
{
    use HasFactory;

    protected $table = 'ai_generations';

    protected $fillable = [
        'user_id',
        'type',
        'prompt',
        'context',
        'result',
        'result_meta',
        'model_used',
        'input_tokens',
        'output_tokens',
        'credits_used',
        'is_favorite',
    ];

    protected function casts(): array
    {
        return [
            'context' => 'array',
            'result_meta' => 'array',
            'input_tokens' => 'integer',
            'output_tokens' => 'integer',
            'credits_used' => 'decimal:2',
            'is_favorite' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get total tokens used.
     */
    public function getTotalTokensAttribute(): int
    {
        return $this->input_tokens + $this->output_tokens;
    }

    /**
     * Scope for type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for favorites.
     */
    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true);
    }

    /**
     * Toggle favorite status.
     */
    public function toggleFavorite(): bool
    {
        $this->is_favorite = !$this->is_favorite;
        $this->save();
        return $this->is_favorite;
    }

    /**
     * Get type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'title' => __('ai.type.title'),
            'description' => __('ai.type.description'),
            'idea' => __('ai.type.idea'),
            'cover' => __('ai.type.cover'),
            'hashtags' => __('ai.type.hashtags'),
            'script' => __('ai.type.script'),
            'calendar' => __('ai.type.calendar'),
            default => $this->type,
        };
    }
}
