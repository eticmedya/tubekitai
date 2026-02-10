<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = array_keys(config('app.supported_locales', ['en' => 'English', 'tr' => 'Türkçe']));

        // Priority: User preference > Session > Browser > Default
        $locale = null;

        // 1. Check authenticated user preference
        if (auth()->check() && auth()->user()->locale) {
            $locale = auth()->user()->locale;
        }

        // 2. Check session
        if (!$locale && session()->has('locale')) {
            $locale = session('locale');
        }

        // 3. Check browser preference
        if (!$locale) {
            $browserLocale = $request->getPreferredLanguage($supportedLocales);
            if ($browserLocale) {
                $locale = $browserLocale;
            }
        }

        // 4. Validate and set locale
        if ($locale && in_array($locale, $supportedLocales)) {
            App::setLocale($locale);
            session(['locale' => $locale]);
        }

        return $next($request);
    }
}
