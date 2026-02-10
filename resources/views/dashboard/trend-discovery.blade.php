@extends('layouts.dashboard')

@section('title', __('modules.trend_discovery'))

@section('content')
<div class="space-y-6" x-data="trendDiscovery()">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                {{ __('modules.trend_discovery') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('modules.trend_discovery_desc') }}
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.736 6.979C9.208 6.193 9.696 6 10 6c.304 0 .792.193 1.264.979a1 1 0 001.715-1.029C12.279 4.784 11.232 4 10 4s-2.279.784-2.979 1.95c-.285.475-.507 1-.67 1.55H6a1 1 0 000 2h.013a9.358 9.358 0 000 1H6a1 1 0 100 2h.351c.163.55.385 1.075.67 1.55C7.721 15.216 8.768 16 10 16s2.279-.784 2.979-1.95a1 1 0 10-1.715-1.029c-.472.786-.96.979-1.264.979-.304 0-.792-.193-1.264-.979a4.265 4.265 0 01-.264-.521H10a1 1 0 100-2H8.017a7.36 7.36 0 010-1H10a1 1 0 100-2H8.472c.08-.185.167-.36.264-.521z"/>
                </svg>
                1 {{ __('credits.credit') }}
            </span>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('modules.region') }}
                </label>
                <select x-model="region" @change="loadTrends"
                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="TR">{{ __('modules.region_tr') }}</option>
                    <option value="US">{{ __('modules.region_us') }}</option>
                    <option value="GB">{{ __('modules.region_gb') }}</option>
                    <option value="DE">{{ __('modules.region_de') }}</option>
                    <option value="FR">{{ __('modules.region_fr') }}</option>
                    <option value="JP">{{ __('modules.region_jp') }}</option>
                    <option value="KR">{{ __('modules.region_kr') }}</option>
                    <option value="BR">{{ __('modules.region_br') }}</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('modules.category') }}
                </label>
                <select x-model="category" @change="loadTrends"
                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="">{{ __('modules.all_categories') }}</option>
                    <option value="1">{{ __('modules.cat_film') }}</option>
                    <option value="2">{{ __('modules.cat_autos') }}</option>
                    <option value="10">{{ __('modules.cat_music') }}</option>
                    <option value="15">{{ __('modules.cat_pets') }}</option>
                    <option value="17">{{ __('modules.cat_sports') }}</option>
                    <option value="20">{{ __('modules.cat_gaming') }}</option>
                    <option value="22">{{ __('modules.cat_people') }}</option>
                    <option value="23">{{ __('modules.cat_comedy') }}</option>
                    <option value="24">{{ __('modules.cat_entertainment') }}</option>
                    <option value="25">{{ __('modules.cat_news') }}</option>
                    <option value="26">{{ __('modules.cat_howto') }}</option>
                    <option value="27">{{ __('modules.cat_education') }}</option>
                    <option value="28">{{ __('modules.cat_science') }}</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('modules.time_range') }}
                </label>
                <select x-model="timeRange" @change="loadTrends"
                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="today">{{ __('modules.today') }}</option>
                    <option value="week">{{ __('modules.this_week') }}</option>
                    <option value="month">{{ __('modules.this_month') }}</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Trending Videos Grid -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                {{ __('modules.trending_now') }}
            </h3>
            <button @click="loadTrends" :disabled="loading"
                class="inline-flex items-center px-3 py-1 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50">
                <svg class="mr-1 h-4 w-4" :class="{'animate-spin': loading}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                {{ __('app.refresh') }}
            </button>
        </div>

        <template x-if="loading">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="i in 6" :key="i">
                    <div class="animate-pulse">
                        <div class="bg-gray-200 dark:bg-gray-700 rounded-lg h-40 mb-3"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
                        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                    </div>
                </template>
            </div>
        </template>

        <template x-if="!loading && trends.length > 0">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="(video, index) in trends" :key="video.id">
                    <div class="group cursor-pointer" @click="showVideoDetails(video)">
                        <div class="relative">
                            <img :src="video.thumbnail" :alt="video.title"
                                class="w-full h-40 object-cover rounded-lg group-hover:opacity-75 transition-opacity">
                            <div class="absolute top-2 left-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-600 text-white">
                                    #<span x-text="index + 1"></span>
                                </span>
                            </div>
                            <div class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                                <span x-text="video.duration"></span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white line-clamp-2 group-hover:text-red-600 transition-colors" x-text="video.title"></h4>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-text="video.channel_title"></p>
                            <div class="mt-2 flex items-center space-x-3 text-xs text-gray-500 dark:text-gray-400">
                                <span class="flex items-center">
                                    <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span x-text="formatNumber(video.view_count)"></span>
                                </span>
                                <span x-text="video.published_at"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        <template x-if="!loading && trends.length === 0">
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('modules.no_trends') }}</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('modules.no_trends_desc') }}</p>
            </div>
        </template>
    </div>

    <!-- Rising Topics -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            {{ __('modules.rising_topics') }}
        </h3>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            <template x-for="topic in risingTopics" :key="topic.keyword">
                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-red-300 dark:hover:border-red-700 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-900 dark:text-white" x-text="topic.keyword"></span>
                        <span class="text-green-600 text-xs font-medium flex items-center">
                            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span x-text="topic.growth + '%'"></span>
                        </span>
                    </div>
                    <div class="h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-red-500 to-orange-500 rounded-full transition-all duration-500"
                            :style="'width: ' + topic.score + '%'"></div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400" x-text="topic.search_volume + ' {{ __('modules.searches') }}'"></p>
                </div>
            </template>
        </div>
    </div>

    <!-- Video Details Modal -->
    <div x-show="selectedVideo" x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        @click.self="selectedVideo = null">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="selectedVideo = null"></div>

            <div class="inline-block bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <template x-if="selectedVideo">
                    <div>
                        <div class="aspect-w-16 aspect-h-9">
                            <iframe :src="'https://www.youtube.com/embed/' + selectedVideo.id" frameborder="0" allowfullscreen class="w-full h-64"></iframe>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white" x-text="selectedVideo.title"></h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400" x-text="selectedVideo.channel_title"></p>

                            <div class="mt-4 flex flex-wrap gap-4">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(selectedVideo.view_count)"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('modules.views') }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(selectedVideo.like_count)"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('modules.likes') }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(selectedVideo.comment_count)"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('modules.comments') }}</p>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button @click="selectedVideo = null"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    {{ __('app.close') }}
                                </button>
                                <a :href="'https://www.youtube.com/watch?v=' + selectedVideo.id" target="_blank"
                                    class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                    {{ __('modules.watch_on_youtube') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function trendDiscovery() {
    return {
        region: 'TR',
        category: '',
        timeRange: 'today',
        loading: false,
        trends: [],
        risingTopics: [],
        selectedVideo: null,

        init() {
            this.loadTrends();
            this.loadRisingTopics();
        },

        async loadTrends() {
            this.loading = true;

            try {
                const response = await fetch('{{ route("trend-discovery.trends") }}?' + new URLSearchParams({
                    region: this.region,
                    category: this.category,
                    time_range: this.timeRange
                }));

                const data = await response.json();

                if (data.success) {
                    this.trends = data.data.videos;
                }
            } catch (error) {
                console.error(error);
            } finally {
                this.loading = false;
            }
        },

        async loadRisingTopics() {
            try {
                const response = await fetch('{{ route("trend-discovery.rising") }}?' + new URLSearchParams({
                    region: this.region
                }));

                const data = await response.json();

                if (data.success) {
                    this.risingTopics = data.data.topics;
                }
            } catch (error) {
                console.error(error);
            }
        },

        showVideoDetails(video) {
            this.selectedVideo = video;
        },

        formatNumber(num) {
            if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'M';
            }
            if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'K';
            }
            return num?.toString() || '0';
        }
    }
}
</script>
@endpush
@endsection
