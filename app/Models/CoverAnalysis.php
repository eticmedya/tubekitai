<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CoverAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image_path',
        'original_filename',
        'quality_score',
        'readability_score',
        'face_visibility_score',
        'contrast_score',
        'emotion_score',
        'composition_score',
        'overall_score',
        'ctr_prediction',
        'ai_feedback',
        'improvement_suggestions',
        'detected_elements',
        'credits_used',
        'model_used',
    ];

    protected function casts(): array
    {
        return [
            'quality_score' => 'integer',
            'readability_score' => 'integer',
            'face_visibility_score' => 'integer',
            'contrast_score' => 'integer',
            'emotion_score' => 'integer',
            'composition_score' => 'integer',
            'overall_score' => 'integer',
            'ctr_prediction' => 'integer',
            'improvement_suggestions' => 'array',
            'detected_elements' => 'array',
            'credits_used' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the image URL.
     */
    public function getImageUrlAttribute(): string
    {
        return Storage::disk('covers')->url($this->image_path);
    }

    /**
     * Get score grade (A, B, C, D, F).
     */
    public function getGradeAttribute(): string
    {
        return match (true) {
            $this->overall_score >= 90 => 'A',
            $this->overall_score >= 80 => 'B',
            $this->overall_score >= 70 => 'C',
            $this->overall_score >= 60 => 'D',
            default => 'F',
        };
    }

    /**
     * Get CTR prediction label.
     */
    public function getCtrLabelAttribute(): string
    {
        return match (true) {
            $this->ctr_prediction >= 80 => __('cover.ctr.excellent'),
            $this->ctr_prediction >= 60 => __('cover.ctr.good'),
            $this->ctr_prediction >= 40 => __('cover.ctr.average'),
            $this->ctr_prediction >= 20 => __('cover.ctr.below_average'),
            default => __('cover.ctr.poor'),
        };
    }

    /**
     * Get score color.
     */
    public function getScoreColorAttribute(): string
    {
        return match (true) {
            $this->overall_score >= 80 => 'green',
            $this->overall_score >= 60 => 'yellow',
            $this->overall_score >= 40 => 'orange',
            default => 'red',
        };
    }
}
