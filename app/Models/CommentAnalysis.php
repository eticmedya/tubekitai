<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'user_id',
        'total_comments',
        'positive_count',
        'negative_count',
        'supportive_count',
        'criticism_count',
        'suggestion_count',
        'question_count',
        'toxic_count',
        'top_suggestions',
        'top_criticisms',
        'common_topics',
        'ai_summary',
        'credits_used',
        'model_used',
    ];

    protected function casts(): array
    {
        return [
            'total_comments' => 'integer',
            'positive_count' => 'integer',
            'negative_count' => 'integer',
            'supportive_count' => 'integer',
            'criticism_count' => 'integer',
            'suggestion_count' => 'integer',
            'question_count' => 'integer',
            'toxic_count' => 'integer',
            'top_suggestions' => 'array',
            'top_criticisms' => 'array',
            'common_topics' => 'array',
            'credits_used' => 'decimal:2',
        ];
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get sentiment score (-100 to 100).
     */
    public function getSentimentScoreAttribute(): int
    {
        $positive = $this->positive_count + $this->supportive_count;
        $negative = $this->negative_count + $this->criticism_count + $this->toxic_count;
        $total = $positive + $negative;

        if ($total === 0) {
            return 0;
        }

        return (int) round((($positive - $negative) / $total) * 100);
    }

    /**
     * Get positive percentage.
     */
    public function getPositivePercentageAttribute(): float
    {
        if ($this->total_comments === 0) {
            return 0;
        }
        $positive = $this->positive_count + $this->supportive_count;
        return round(($positive / $this->total_comments) * 100, 1);
    }

    /**
     * Get negative percentage.
     */
    public function getNegativePercentageAttribute(): float
    {
        if ($this->total_comments === 0) {
            return 0;
        }
        $negative = $this->negative_count + $this->criticism_count + $this->toxic_count;
        return round(($negative / $this->total_comments) * 100, 1);
    }
}
