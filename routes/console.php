<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
*/

// Clean up old activity logs (keep last 90 days)
Schedule::command('model:prune', ['--model' => 'App\\Models\\ActivityLog'])
    ->daily()
    ->at('03:00');

// Clean up old cache
Schedule::command('cache:prune-stale-tags')
    ->hourly();

// Update keyword trends (if implemented)
// Schedule::command('trends:update')->dailyAt('04:00');
