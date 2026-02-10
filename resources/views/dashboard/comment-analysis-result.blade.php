@extends('layouts.dashboard')

@section('title', __('modules.comment_analysis') . ' - ' . $analysis->video->title)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <div class="flex items-center">
                <a href="{{ route('comment-analysis') }}" class="mr-4 text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                        {{ __('modules.comment_analysis') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $analysis->created_at->format('d.m.Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Info -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex items-start space-x-4">
            <img src="{{ $analysis->video->thumbnail_url }}" alt="{{ $analysis->video->title }}" class="w-40 h-24 object-cover rounded-lg">
            <div class="flex-1">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $analysis->video->title }}</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ number_format($analysis->video->view_count) }} {{ __('modules.views') }} &bull;
                    {{ number_format($analysis->video->like_count) }} {{ __('modules.likes') }}
                </p>
                <a href="https://youtube.com/watch?v={{ $analysis->video->youtube_id }}" target="_blank"
                    class="mt-2 inline-flex items-center text-sm text-red-600 hover:text-red-700">
                    <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                    </svg>
                    {{ __('modules.watch_on_youtube') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $analysis->total_comments }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.total_comments') }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">{{ $analysis->positive_count + $analysis->supportive_count }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.positive') }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-red-600">{{ $analysis->negative_count + $analysis->criticism_count }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.negative') }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $analysis->suggestion_count }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.suggestions') }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-yellow-600">{{ $analysis->question_count }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.questions') }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-purple-600">{{ $analysis->toxic_count }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.toxic') }}</p>
            </div>
        </div>
    </div>

    <!-- Sentiment Chart -->
    @php
        $total = $analysis->total_comments ?: 1;
        $positivePercent = round((($analysis->positive_count + $analysis->supportive_count) / $total) * 100);
        $negativePercent = round((($analysis->negative_count + $analysis->criticism_count) / $total) * 100);
        $neutralPercent = max(0, 100 - $positivePercent - $negativePercent);
    @endphp
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.sentiment_distribution') }}</h3>
        <div class="h-4 flex rounded-full overflow-hidden">
            <div class="bg-green-500" style="width: {{ $positivePercent }}%"></div>
            <div class="bg-gray-400" style="width: {{ $neutralPercent }}%"></div>
            <div class="bg-red-500" style="width: {{ $negativePercent }}%"></div>
        </div>
        <div class="mt-2 flex justify-between text-sm">
            <span class="text-green-600">{{ __('modules.positive') }}: {{ $positivePercent }}%</span>
            <span class="text-gray-500">{{ __('modules.neutral') }}: {{ $neutralPercent }}%</span>
            <span class="text-red-600">{{ __('modules.negative') }}: {{ $negativePercent }}%</span>
        </div>
    </div>

    <!-- AI Summary -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.ai_summary') }}</h3>
        <div class="prose dark:prose-invert max-w-none whitespace-pre-wrap">{{ $analysis->ai_summary }}</div>
    </div>

    <!-- Top Suggestions -->
    @if(!empty($analysis->top_suggestions))
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.suggestions') }}</h3>
        <ul class="space-y-3">
            @foreach($analysis->top_suggestions as $suggestion)
            <li class="flex items-start">
                <svg class="flex-shrink-0 w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span class="text-gray-700 dark:text-gray-300">{{ $suggestion }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Top Criticisms -->
    @if(!empty($analysis->top_criticisms))
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.criticisms') }}</h3>
        <ul class="space-y-3">
            @foreach($analysis->top_criticisms as $criticism)
            <li class="flex items-start">
                <svg class="flex-shrink-0 w-5 h-5 text-orange-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span class="text-gray-700 dark:text-gray-300">{{ $criticism }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection
