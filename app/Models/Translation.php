<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id',
        'type',
        'source_text',
        'source_lang',
        'target_lang',
        'translated_text',
        'seo_suggestions',
        'credits_used',
        'model_used',
    ];

    protected function casts(): array
    {
        return [
            'seo_suggestions' => 'array',
            'credits_used' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * Get type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'title' => __('transflow.type.title'),
            'description' => __('transflow.type.description'),
            'subtitle' => __('transflow.type.subtitle'),
            'tags' => __('transflow.type.tags'),
            default => $this->type,
        };
    }

    /**
     * Get language pair label.
     */
    public function getLanguagePairAttribute(): string
    {
        return strtoupper($this->source_lang) . ' → ' . strtoupper($this->target_lang);
    }

    /**
     * Scope for type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
