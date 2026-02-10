<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CoverGeneration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image_path',
        'original_url',
        'prompt',
        'aspect_ratio',
        'has_reference',
        'reference_path',
        'model_used',
        'credits_used',
    ];

    protected function casts(): array
    {
        return [
            'has_reference' => 'boolean',
            'credits_used' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full URL to the generated image.
     */
    public function getImageUrlAttribute(): string
    {
        return Storage::disk('covers')->url($this->image_path);
    }

    /**
     * Get the full URL to the reference image if exists.
     */
    public function getReferenceUrlAttribute(): ?string
    {
        if (!$this->reference_path) {
            return null;
        }
        return Storage::disk('covers')->url($this->reference_path);
    }

    /**
     * Get a truncated version of the prompt for display.
     */
    public function getShortPromptAttribute(): string
    {
        return strlen($this->prompt) > 50
            ? substr($this->prompt, 0, 50) . '...'
            : $this->prompt;
    }
}
