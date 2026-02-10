@extends('layouts.dashboard')

@section('title', __('modules.comment_analysis'))

@section('content')
<div class="space-y-6" x-data="commentAnalysis()">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                {{ __('modules.comment_analysis') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('modules.comment_analysis_desc') }}
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.736 6.979C9.208 6.193 9.696 6 10 6c.304 0 .792.193 1.264.979a1 1 0 001.715-1.029C12.279 4.784 11.232 4 10 4s-2.279.784-2.979 1.95c-.285.475-.507 1-.67 1.55H6a1 1 0 000 2h.013a9.358 9.358 0 000 1H6a1 1 0 100 2h.351c.163.55.385 1.075.67 1.55C7.721 15.216 8.768 16 10 16s2.279-.784 2.979-1.95a1 1 0 10-1.715-1.029c-.472.786-.96.979-1.264.979-.304 0-.792-.193-1.264-.979a4.265 4.265 0 01-.264-.521H10a1 1 0 100-2H8.017a7.36 7.36 0 010-1H10a1 1 0 100-2H8.472c.08-.185.167-.36.264-.521z"/>
                </svg>
                3 {{ __('credits.credits') }}
            </span>
        </div>
    </div>

    <!-- Input Form -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form @submit.prevent="analyzeComments">
            <div class="space-y-4">
                <div>
                    <label for="video_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('modules.video_url') }}
                    </label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 sm:text-sm">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                            </svg>
                        </span>
                        <input type="url" name="video_url" id="video_url" x-model="videoUrl" required
                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500 sm:text-sm"
                            placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                </div>

                <div>
                    <label for="limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('modules.comment_limit') }}
                    </label>
                    <select id="limit" x-model="limit"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                        <option value="50">50 {{ __('modules.comments') }}</option>
                        <option value="100" selected>100 {{ __('modules.comments') }}</option>
                        <option value="200">200 {{ __('modules.comments') }}</option>
                        <option value="500">500 {{ __('modules.comments') }}</option>
                    </select>
                </div>

                <div class="flex justify-end">
                    <button type="submit" :disabled="loading"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        <template x-if="loading">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <template x-if="!loading">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </template>
                        <span x-text="loading ? '{{ __('app.analyzing') }}' : '{{ __('app.analyze') }}'"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Results -->
    <template x-if="result">
        <div class="space-y-6">
            <!-- Summary Stats -->
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="result.summary.total"></p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.total_comments') }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600" x-text="result.summary.counts.positive + result.summary.counts.supportive"></p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.positive') }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-red-600" x-text="result.summary.counts.negative + result.summary.counts.criticism"></p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.negative') }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600" x-text="result.summary.counts.suggestion"></p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.suggestions') }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-yellow-600" x-text="result.summary.counts.question"></p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.questions') }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-purple-600" x-text="result.summary.counts.toxic"></p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.toxic') }}</p>
                    </div>
                </div>
            </div>

            <!-- Sentiment Chart -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.sentiment_distribution') }}</h3>
                <div class="h-4 flex rounded-full overflow-hidden">
                    <div class="bg-green-500" :style="'width: ' + result.summary.positive_ratio + '%'"></div>
                    <div class="bg-gray-400" :style="'width: ' + getNeutralPercent() + '%'"></div>
                    <div class="bg-red-500" :style="'width: ' + result.summary.negative_ratio + '%'"></div>
                </div>
                <div class="mt-2 flex justify-between text-sm">
                    <span class="text-green-600">{{ __('modules.positive') }}: <span x-text="result.summary.positive_ratio + '%'"></span></span>
                    <span class="text-gray-500">{{ __('modules.neutral') }}: <span x-text="getNeutralPercent() + '%'"></span></span>
                    <span class="text-red-600">{{ __('modules.negative') }}: <span x-text="result.summary.negative_ratio + '%'"></span></span>
                </div>
            </div>

            <!-- AI Summary -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    {{ __('modules.ai_summary') }}
                </h3>
                <div class="prose dark:prose-invert max-w-none whitespace-pre-wrap" x-text="result.summary.ai_summary"></div>
            </div>

            <!-- Comment Categories -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <template x-for="(tab, index) in tabs" :key="index">
                            <button @click="activeTab = tab.key"
                                :class="{'border-red-500 text-red-600': activeTab === tab.key, 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== tab.key}"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                <span x-text="tab.label"></span>
                                <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100" x-text="result.comments[tab.key]?.length || 0"></span>
                            </button>
                        </template>
                    </nav>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <template x-for="comment in (result.comments[activeTab] || [])" :key="comment.youtube_id">
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="flex items-start space-x-3">
                                    <img :src="comment.author_avatar || 'https://www.gravatar.com/avatar/?d=mp'"
                                        class="h-10 w-10 rounded-full" :alt="comment.author">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="comment.author"></p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" x-text="comment.text"></p>
                                        <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center">
                                                <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                                </svg>
                                                <span x-text="comment.like_count"></span>
                                            </span>
                                            <span x-text="comment.published_at"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script>
function commentAnalysis() {
    return {
        videoUrl: '',
        limit: '100',
        loading: false,
        result: null,
        activeTab: 'positive',
        tabs: [
            { key: 'positive', label: '{{ __("modules.positive") }}' },
            { key: 'negative', label: '{{ __("modules.negative") }}' },
            { key: 'suggestion', label: '{{ __("modules.suggestions") }}' },
            { key: 'question', label: '{{ __("modules.questions") }}' },
            { key: 'toxic', label: '{{ __("modules.toxic") }}' }
        ],

        getNeutralPercent() {
            if (!this.result) return 0;
            return Math.max(0, 100 - this.result.summary.positive_ratio - this.result.summary.negative_ratio);
        },

        async analyzeComments() {
            if (!this.videoUrl) return;

            this.loading = true;
            this.result = null;

            try {
                const response = await fetch('{{ route("comment-analysis.analyze") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        video_url: this.videoUrl,
                        limit: parseInt(this.limit)
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.result = data.data;
                } else {
                    alert(data.message || '{{ __("app.error_occurred") }}');
                }
            } catch (error) {
                console.error(error);
                alert('{{ __("app.error_occurred") }}');
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endpush
@endsection
