<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'locale',
        'theme',
        'credits',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'credits' => 'decimal:2',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get user settings.
     */
    public function settings(): HasOne
    {
        return $this->hasOne(UserSetting::class);
    }

    /**
     * Get user's channels.
     */
    public function channels(): HasMany
    {
        return $this->hasMany(Channel::class);
    }

    /**
     * Get user's credit transactions.
     */
    public function creditTransactions(): HasMany
    {
        return $this->hasMany(Credit::class);
    }

    /**
     * Get user's payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get user's AI generations.
     */
    public function aiGenerations(): HasMany
    {
        return $this->hasMany(AIGeneration::class);
    }

    /**
     * Get user's cover analyses.
     */
    public function coverAnalyses(): HasMany
    {
        return $this->hasMany(CoverAnalysis::class);
    }

    /**
     * Get user's cover generations.
     */
    public function coverGenerations(): HasMany
    {
        return $this->hasMany(CoverGeneration::class);
    }

    /**
     * Get user's comment analyses.
     */
    public function commentAnalyses(): HasMany
    {
        return $this->hasMany(CommentAnalysis::class);
    }

    /**
     * Get user's niche analyses.
     */
    public function nicheAnalyses(): HasMany
    {
        return $this->hasMany(NicheAnalysis::class);
    }

    /**
     * Get user's competitors.
     */
    public function competitors(): HasMany
    {
        return $this->hasMany(Competitor::class);
    }

    /**
     * Get user's translations.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class);
    }

    /**
     * Get user's activity logs.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Check if user has enough credits.
     */
    public function hasCredits(float $amount): bool
    {
        return $this->credits >= $amount;
    }

    /**
     * Deduct credits from user.
     */
    public function deductCredits(float $amount): bool
    {
        if (!$this->hasCredits($amount)) {
            return false;
        }

        $this->decrement('credits', $amount);
        return true;
    }

    /**
     * Add credits to user.
     */
    public function addCredits(float $amount): void
    {
        $this->increment('credits', $amount);
    }

    /**
     * Get settings value with default.
     */
    public function getSetting(string $key, mixed $default = null): mixed
    {
        $settings = $this->settings?->settings_json ?? [];
        return data_get($settings, $key, $default);
    }
}
