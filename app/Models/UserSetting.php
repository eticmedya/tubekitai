<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'settings_json',
    ];

    protected function casts(): array
    {
        return [
            'settings_json' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get a setting value.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return data_get($this->settings_json, $key, $default);
    }

    /**
     * Set a setting value.
     */
    public function set(string $key, mixed $value): void
    {
        $settings = $this->settings_json ?? [];
        data_set($settings, $key, $value);
        $this->settings_json = $settings;
        $this->save();
    }
}
