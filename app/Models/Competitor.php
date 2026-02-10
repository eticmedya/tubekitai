<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Competitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'channel_id',
        'notes',
        'strategy_notes',
        'content_patterns',
        'upload_schedule',
        'title_formulas',
        'cover_styles',
        'top_videos',
        'ai_analysis',
        'credits_used',
    ];

    protected function casts(): array
    {
        return [
            'content_patterns' => 'array',
            'upload_schedule' => 'array',
            'title_formulas' => 'array',
            'cover_styles' => 'array',
            'top_videos' => 'array',
            'credits_used' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Get most used title formula.
     */
    public function getTopTitleFormulaAttribute(): ?string
    {
        if (empty($this->title_formulas)) {
            return null;
        }
        return $this->title_formulas[0] ?? null;
    }

    /**
     * Get average upload frequency (per week).
     */
    public function getUploadFrequencyAttribute(): ?float
    {
        if (empty($this->upload_schedule) || !isset($this->upload_schedule['per_week'])) {
            return null;
        }
        return $this->upload_schedule['per_week'];
    }
}
