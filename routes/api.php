<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // User
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Credits
    Route::get('/credits/balance', function (Request $request) {
        return response()->json([
            'credits' => $request->user()->credits,
        ]);
    });

    Route::get('/credits/history', function (Request $request) {
        $history = $request->user()
            ->creditTransactions()
            ->orderByDesc('created_at')
            ->take(50)
            ->get();

        return response()->json([
            'history' => $history,
        ]);
    });
});
