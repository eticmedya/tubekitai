<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'data',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }

    /**
     * Get action label.
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'login' => __('activity.login'),
            'logout' => __('activity.logout'),
            'register' => __('activity.register'),
            'password_reset' => __('activity.password_reset'),
            'purchase' => __('activity.purchase'),
            'channel_analysis' => __('activity.channel_analysis'),
            'video_analysis' => __('activity.video_analysis'),
            'comment_analysis' => __('activity.comment_analysis'),
            'cover_analysis' => __('activity.cover_analysis'),
            'cover_generation' => __('activity.cover_generation'),
            'niche_analysis' => __('activity.niche_analysis'),
            'translation' => __('activity.translation'),
            'ai_generation' => __('activity.ai_generation'),
            default => $this->action,
        };
    }

    /**
     * Log an activity.
     */
    public static function log(
        string $action,
        ?Model $subject = null,
        ?array $data = null,
        ?int $userId = null
    ): static {
        return static::create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'model_type' => $subject?->getMorphClass(),
            'model_id' => $subject?->getKey(),
            'data' => $data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Scope for user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for action.
     */
    public function scopeForAction($query, string $action)
    {
        return $query->where('action', $action);
    }
}
