@extends('layouts.dashboard')

@section('title', __('modules.keyword_trends'))

@section('content')
<div class="space-y-6" x-data="keywordTrends()">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                {{ __('modules.keyword_trends') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('keyword_trends.description') }}
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/>
                </svg>
                1 {{ __('credits.credit') }}
            </span>
        </div>
    </div>

    <!-- Input Form -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form @submit.prevent="analyze">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="niche" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('keyword_trends.niche_label') }} *
                    </label>
                    <div class="mt-1">
                        <input type="text" id="niche" x-model="niche" required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500 sm:text-sm"
                            placeholder="{{ __('keyword_trends.niche_placeholder') }}">
                    </div>
                </div>

                <div>
                    <label for="keywords" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('keyword_trends.keywords_label') }}
                    </label>
                    <div class="mt-1">
                        <input type="text" id="keywords" x-model="keywords"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500 sm:text-sm"
                            placeholder="{{ __('keyword_trends.keywords_placeholder') }}">
                    </div>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit" :disabled="loading"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <template x-if="loading">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </template>
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!loading">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span x-text="loading ? '{{ __('keyword_trends.analyzing') }}' : '{{ __('keyword_trends.analyze_button') }}'"></span>
                </button>
            </div>
        </form>
    </div>

    <!-- Results -->
    <template x-if="result">
        <div class="space-y-6">
            <!-- Trending Keywords -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    {{ __('keyword_trends.trending_keywords') }}
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('keyword_trends.keyword') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('keyword_trends.search_volume') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('keyword_trends.competition') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('keyword_trends.trend') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('keyword_trends.opportunity_score') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="(kw, index) in (result.analysis?.trending_keywords || [])" :key="index">
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white" x-text="kw.keyword"></td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <span class="px-2 py-1 text-xs rounded-full"
                                              :class="{
                                                  'bg-green-100 text-green-800': kw.search_volume_estimate === 'yüksek' || kw.search_volume_estimate === 'high',
                                                  'bg-yellow-100 text-yellow-800': kw.search_volume_estimate === 'orta' || kw.search_volume_estimate === 'medium',
                                                  'bg-gray-100 text-gray-800': kw.search_volume_estimate === 'düşük' || kw.search_volume_estimate === 'low'
                                              }"
                                              x-text="kw.search_volume_estimate"></span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <span class="px-2 py-1 text-xs rounded-full"
                                              :class="{
                                                  'bg-red-100 text-red-800': kw.competition === 'yüksek' || kw.competition === 'high',
                                                  'bg-yellow-100 text-yellow-800': kw.competition === 'orta' || kw.competition === 'medium',
                                                  'bg-green-100 text-green-800': kw.competition === 'düşük' || kw.competition === 'low'
                                              }"
                                              x-text="kw.competition"></span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <span class="flex items-center"
                                              :class="{
                                                  'text-green-600': kw.trend_direction === 'yükseliyor' || kw.trend_direction === 'rising',
                                                  'text-gray-600': kw.trend_direction === 'stabil' || kw.trend_direction === 'stable',
                                                  'text-red-600': kw.trend_direction === 'düşüyor' || kw.trend_direction === 'falling'
                                              }">
                                            <svg x-show="kw.trend_direction === 'yükseliyor' || kw.trend_direction === 'rising'" class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                            <svg x-show="kw.trend_direction === 'düşüyor' || kw.trend_direction === 'falling'" class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                            <span x-text="kw.trend_direction"></span>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <div class="flex items-center">
                                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                <div class="bg-green-600 h-2 rounded-full" :style="'width: ' + kw.opportunity_score + '%'"></div>
                                            </div>
                                            <span class="text-gray-900 dark:text-white" x-text="kw.opportunity_score"></span>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Long Tail Keywords -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    {{ __('keyword_trends.long_tail_keywords') }}
                </h3>
                <div class="flex flex-wrap gap-2">
                    <template x-for="(kw, index) in (result.analysis?.long_tail_keywords || [])" :key="index">
                        <span class="px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full text-sm" x-text="kw"></span>
                    </template>
                </div>
            </div>

            <!-- Question Keywords -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('keyword_trends.question_keywords') }}
                </h3>
                <ul class="space-y-2">
                    <template x-for="(q, index) in (result.analysis?.question_keywords || [])" :key="index">
                        <li class="flex items-start">
                            <span class="text-purple-500 mr-2">?</span>
                            <span class="text-gray-700 dark:text-gray-300" x-text="q"></span>
                        </li>
                    </template>
                </ul>
            </div>

            <!-- Content Ideas -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    {{ __('keyword_trends.video_ideas') }}
                </h3>
                <div class="space-y-3">
                    <template x-for="(idea, index) in (result.analysis?.content_ideas || [])" :key="index">
                        <div class="flex items-start p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                            <span class="text-yellow-600 font-bold mr-3" x-text="(index + 1) + '.'"></span>
                            <span class="text-gray-700 dark:text-gray-300" x-text="idea"></span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- SEO Tips -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('keyword_trends.seo_tips') }}
                </h3>
                <ul class="space-y-2">
                    <template x-for="(tip, index) in (result.analysis?.seo_tips || [])" :key="index">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-teal-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300" x-text="tip"></span>
                        </li>
                    </template>
                </ul>
            </div>

            <!-- Raw Result (if parsing failed) -->
            <template x-if="!result.analysis?.trending_keywords">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('keyword_trends.analysis_results') }}</h3>
                    <div class="prose dark:prose-invert max-w-none whitespace-pre-wrap" x-text="result.raw"></div>
                </div>
            </template>
        </div>
    </template>

    <!-- Previous Searches -->
    @if($previousSearches->isNotEmpty())
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('keyword_trends.previous_searches') }}</h3>
        <div class="space-y-3">
            @foreach($previousSearches as $search)
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $search->context['niche'] ?? __('keyword_trends.niche') }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $search->created_at->diffForHumans() }}
                    </p>
                </div>
                <button @click="showPrevious({{ json_encode($search) }})" class="text-red-600 hover:text-red-800 text-sm">
                    {{ __('common.view') }}
                </button>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function keywordTrends() {
    return {
        niche: '',
        keywords: '',
        loading: false,
        result: null,

        async analyze() {
            if (!this.niche.trim()) {
                alert('{{ __('keyword_trends.enter_niche') }}');
                return;
            }

            this.loading = true;
            this.result = null;

            try {
                const response = await fetch('{{ route("keyword-trends.analyze") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        niche: this.niche,
                        keywords: this.keywords,
                        language: '{{ app()->getLocale() }}'
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.result = data.data;
                } else {
                    alert(data.error || '{{ __('common.error_occurred') }}');
                }
            } catch (error) {
                console.error(error);
                alert('{{ __('common.error_occurred') }}');
            } finally {
                this.loading = false;
            }
        },

        showPrevious(search) {
            this.result = {
                niche: search.context?.niche || '',
                analysis: search.result_meta,
                raw: search.result
            };
        }
    }
}
</script>
@endpush
@endsection
