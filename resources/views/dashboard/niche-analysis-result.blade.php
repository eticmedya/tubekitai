@extends('layouts.dashboard')

@section('title', __('modules.niche_analysis'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <div class="flex items-center">
                <a href="{{ route('niche-analysis') }}" class="mr-4 text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                        {{ __('modules.niche_analysis') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $analysis->created_at->format('d.m.Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Summary -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.your_profile') }}</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.interests') }}</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ implode(', ', $analysis->interests ?? []) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.skills') }}</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ implode(', ', $analysis->skills ?? []) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.lifestyle') }}</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ implode(', ', $analysis->lifestyle ?? []) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.time') }}</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ $analysis->time_availability_label }}</p>
            </div>
        </div>
    </div>

    <!-- Recommended Niches -->
    @if(!empty($analysis->suggested_niches))
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.recommended_niches') }}</h3>
        <div class="space-y-4">
            @foreach($analysis->suggested_niches as $index => $niche)
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <span class="flex items-center justify-center h-8 w-8 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 text-sm font-bold">{{ $index + 1 }}</span>
                                <h4 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">{{ $niche['name'] ?? '' }}</h4>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $niche['why_suitable'] ?? '' }}</p>
                        </div>
                        <div class="ml-4 flex-shrink-0">
                            @php
                                $level = strtolower($niche['competition_level'] ?? '');
                                $bgClass = match(true) {
                                    str_contains($level, 'low') || str_contains($level, 'düşük') => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    str_contains($level, 'medium') || str_contains($level, 'orta') => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    default => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bgClass }}">
                                {{ $niche['competition_level'] ?? '' }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-3">
                        @if(!empty($niche['content_format']))
                            <p class="text-sm text-gray-600 dark:text-gray-300"><strong>{{ __('modules.content_format') }}:</strong> {{ $niche['content_format'] }}</p>
                        @endif
                        @if(!empty($niche['growth_potential']))
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1"><strong>{{ __('modules.growth_potential') }}:</strong> {{ $niche['growth_potential'] }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Content Ideas -->
    @if(!empty($analysis->content_ideas))
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.content_ideas') }}</h3>
        <ul class="space-y-2">
            @foreach($analysis->content_ideas as $index => $idea)
                <li class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 text-xs font-bold mr-3">{{ $index + 1 }}</span>
                    <span class="text-gray-700 dark:text-gray-300">{{ $idea }}</span>
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Recommendations -->
    @if(!empty($analysis->recommendations))
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.ai_recommendations') }}</h3>
        <ul class="space-y-2">
            @foreach($analysis->recommendations as $rec)
                <li class="flex items-start">
                    <svg class="flex-shrink-0 w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">{{ $rec }}</span>
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- AI Summary -->
    @if(!empty($analysis->ai_summary))
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.ai_summary') }}</h3>
        <div class="prose dark:prose-invert max-w-none whitespace-pre-wrap">{{ $analysis->ai_summary }}</div>
    </div>
    @endif
</div>
@endsection
