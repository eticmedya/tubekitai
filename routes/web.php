<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dashboard\ChannelAnalysisController;
use App\Http\Controllers\Dashboard\CommentAnalysisController;
use App\Http\Controllers\Dashboard\CoverAIController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\IdeaGeneratorController;
use App\Http\Controllers\Dashboard\NicheAnalysisController;
use App\Http\Controllers\Dashboard\TransFlowController;
use App\Http\Controllers\Dashboard\TrendDiscoveryController;
use App\Http\Controllers\Dashboard\VideoAnalysisController;
use App\Http\Controllers\Dashboard\CompetitorAnalysisController;
use App\Http\Controllers\Dashboard\KeywordTrendsController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::view('/', 'landing.index')->name('home');

// Static Pages
Route::view('/pricing', 'landing.pricing')->name('pricing');
Route::view('/features', 'landing.features')->name('features');
Route::view('/about', 'landing.about')->name('about');
Route::view('/contact', 'landing.contact')->name('contact');
Route::view('/privacy', 'legal.privacy')->name('privacy');
Route::view('/terms', 'legal.terms')->name('terms');

// Locale Switching
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

// Theme Switching
Route::post('/theme', [ThemeController::class, 'switch'])->name('theme.switch');

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    // Login
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Email Verification
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| Dashboard Routes (Authenticated + Verified)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {
    // Main Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Channel Analysis
    Route::get('/channel-analysis', [ChannelAnalysisController::class, 'index'])->name('channel-analysis');
    Route::post('/channel-analysis/analyze', [ChannelAnalysisController::class, 'analyze'])->name('channel-analysis.analyze');
    Route::get('/channel-analysis/{id}', [ChannelAnalysisController::class, 'show'])->name('channel-analysis.show');

    // Video Analysis
    Route::get('/video-analysis', [VideoAnalysisController::class, 'index'])->name('video-analysis');
    Route::post('/video-analysis/analyze', [VideoAnalysisController::class, 'analyze'])->name('video-analysis.analyze');

    // Comment Analysis
    Route::get('/comment-analysis', [CommentAnalysisController::class, 'index'])->name('comment-analysis');
    Route::post('/comment-analysis/analyze', [CommentAnalysisController::class, 'analyze'])->name('comment-analysis.analyze');
    Route::get('/comment-analysis/{id}', [CommentAnalysisController::class, 'show'])->name('comment-analysis.show');

    // Cover AI
    Route::get('/cover-ai', [CoverAIController::class, 'index'])->name('cover-ai');
    Route::post('/cover-ai/analyze', [CoverAIController::class, 'analyze'])->name('cover-ai.analyze');
    Route::post('/cover-ai/generate', [CoverAIController::class, 'generate'])->name('cover-ai.generate');
    Route::get('/cover-ai/suggestions', [CoverAIController::class, 'suggestions'])->name('cover-ai.suggestions');
    Route::post('/cover-ai/fetch-thumbnail', [CoverAIController::class, 'fetchYouTubeThumbnail'])->name('cover-ai.fetch-thumbnail');

    // Niche Analysis
    Route::get('/niche-analysis', [NicheAnalysisController::class, 'index'])->name('niche-analysis');
    Route::post('/niche-analysis/analyze', [NicheAnalysisController::class, 'analyze'])->name('niche-analysis.analyze');
    Route::get('/niche-analysis/{id}', [NicheAnalysisController::class, 'show'])->name('niche-analysis.show');

    // Idea Generator
    Route::get('/idea-generator', [IdeaGeneratorController::class, 'index'])->name('idea-generator');
    Route::post('/idea-generator/generate', [IdeaGeneratorController::class, 'generate'])->name('idea-generator.generate');
    Route::post('/idea-generator/{id}/favorite', [IdeaGeneratorController::class, 'toggleFavorite'])->name('idea-generator.favorite');

    // Trend Discovery
    Route::get('/trend-discovery', [TrendDiscoveryController::class, 'index'])->name('trend-discovery');
    Route::get('/trend-discovery/trends', [TrendDiscoveryController::class, 'trends'])->name('trend-discovery.trends');
    Route::get('/trend-discovery/rising', [TrendDiscoveryController::class, 'rising'])->name('trend-discovery.rising');

    // Competitor Analysis
    Route::get('/competitor-analysis', [CompetitorAnalysisController::class, 'index'])->name('competitor-analysis');
    Route::post('/competitor-analysis/analyze', [CompetitorAnalysisController::class, 'analyze'])->name('competitor-analysis.analyze');

    // Keyword Trends
    Route::get('/keyword-trends', [KeywordTrendsController::class, 'index'])->name('keyword-trends');
    Route::post('/keyword-trends/analyze', [KeywordTrendsController::class, 'analyze'])->name('keyword-trends.analyze');

    // TransFlow
    Route::get('/transflow', [TransFlowController::class, 'index'])->name('transflow');
    Route::post('/transflow/translate', [TransFlowController::class, 'translate'])->name('transflow.translate');

    // Creator School (placeholder)
    Route::view('/creator-school', 'dashboard.creator-school')->name('creator-school');
});

/*
|--------------------------------------------------------------------------
| Payment Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/credits', [PaymentController::class, 'packages'])->name('payment.packages');
    Route::get('/credits/checkout/{package}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/credits/checkout/{package}', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/credits/history', [PaymentController::class, 'history'])->name('credits.history');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/fail', [PaymentController::class, 'fail'])->name('payment.fail');
});

// PayTR Callback (no auth required)
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
