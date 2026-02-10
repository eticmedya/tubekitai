<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * Switch theme.
     */
    public function switch(Request $request): JsonResponse
    {
        $request->validate([
            'theme' => ['required', 'string', 'in:light,dark,system'],
        ]);

        $theme = $request->theme;

        // Store in session
        session(['theme' => $theme]);

        // Update user preference if authenticated
        if ($request->user()) {
            $request->user()->update(['theme' => $theme]);
        }

        return response()->json([
            'success' => true,
            'theme' => $theme,
        ]);
    }
}
