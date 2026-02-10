<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LanguageString extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'locale',
        'value',
    ];

    /**
     * Get translation for key.
     */
    public static function getTranslation(string $group, string $key, string $locale): ?string
    {
        $cacheKey = "lang_string:{$locale}:{$group}:{$key}";

        return Cache::remember($cacheKey, 3600, function () use ($group, $key, $locale) {
            return static::where('group', $group)
                ->where('key', $key)
                ->where('locale', $locale)
                ->value('value');
        });
    }

    /**
     * Set translation for key.
     */
    public static function setTranslation(string $group, string $key, string $locale, string $value): void
    {
        static::updateOrCreate(
            [
                'group' => $group,
                'key' => $key,
                'locale' => $locale,
            ],
            ['value' => $value]
        );

        Cache::forget("lang_string:{$locale}:{$group}:{$key}");
    }

    /**
     * Get all translations for a group and locale.
     */
    public static function getGroup(string $group, string $locale): array
    {
        $cacheKey = "lang_group:{$locale}:{$group}";

        return Cache::remember($cacheKey, 3600, function () use ($group, $locale) {
            return static::where('group', $group)
                ->where('locale', $locale)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Clear cache for group.
     */
    public static function clearGroupCache(string $group, string $locale): void
    {
        Cache::forget("lang_group:{$locale}:{$group}");
    }
}
