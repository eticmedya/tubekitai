<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocaleController extends Controller
{
    /**
     * Switch locale.
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        $supportedLocales = array_keys(config('app.supported_locales', ['en', 'tr']));

        if (!in_array($locale, $supportedLocales)) {
            abort(400, 'Unsupported locale');
        }

        // Store in session
        session(['locale' => $locale]);

        // Update user preference if authenticated
        if ($request->user()) {
            $request->user()->update(['locale' => $locale]);
        }

        App::setLocale($locale);

        return redirect()->back();
    }
}
