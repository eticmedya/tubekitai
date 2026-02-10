<x-layouts.dashboard :title="__('modules.channel_analysis')" :header="__('modules.channel_analysis')">
    <div class="max-w-4xl mx-auto" x-data="channelAnalysis()">
        <!-- Input Form -->
        <div class="bg-white dark:bg-youtube-gray rounded-xl p-6 border border-gray-200 dark:border-gray-700 mb-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('channel.analyze_title') }}</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ __('channel.analyze_description') }}</p>

            <form @submit.prevent="analyze" class="space-y-4">
                <div>
                    <label for="channel_url" class="block text-sm font-medium mb-2">{{ __('channel.url_label') }}</label>
                    <input
                        type="text"
                        id="channel_url"
                        x-model="channelUrl"
                        placeholder="https://www.youtube.com/@channelname"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-youtube-red focus:border-transparent"
                        :disabled="loading"
                    >
                </div>

                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        <span class="font-medium text-youtube-red">{{ config('credits.costs.channel_analysis') }}</span> {{ __('app.credits_required') }}
                    </p>
                    <button
                        type="submit"
                        :disabled="loading || !channelUrl"
                        class="px-6 py-2 bg-youtube-red text-white font-medium rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center"
                    >
                        <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('channel.analyze_button') }}
                    </button>
                </div>
            </form>

            <!-- Error Message -->
            <div x-show="error" x-cloak class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <p class="text-sm text-red-600 dark:text-red-400" x-text="error"></p>
            </div>
        </div>

        <!-- Results -->
        <div x-show="result" x-cloak class="space-y-6">
            <!-- Channel Header -->
            <div class="bg-white dark:bg-youtube-gray rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-start space-x-4">
                    <img :src="result?.channel?.thumbnail_url" class="w-20 h-20 rounded-full" alt="">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold" x-text="result?.channel?.title"></h2>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1" x-text="result?.channel?.description?.substring(0, 200) + '...'"></p>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <p class="text-2xl font-bold text-youtube-red" x-text="formatNumber(result?.metrics?.subscriber_count)"></p>
                        <p class="text-sm text-gray-500">{{ __('channel.subscribers') }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <p class="text-2xl font-bold" x-text="formatNumber(result?.metrics?.total_views)"></p>
                        <p class="text-sm text-gray-500">{{ __('channel.total_views') }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <p class="text-2xl font-bold" x-text="result?.metrics?.video_count"></p>
                        <p class="text-sm text-gray-500">{{ __('channel.videos') }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <p class="text-2xl font-bold text-green-600" x-text="result?.metrics?.engagement_rate + '%'"></p>
                        <p class="text-sm text-gray-500">{{ __('channel.engagement') }}</p>
                    </div>
                </div>
            </div>

            <!-- AI Analysis -->
            <div class="bg-white dark:bg-youtube-gray rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 text-youtube-red mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/>
                    </svg>
                    {{ __('channel.ai_analysis') }}
                </h3>
                <div class="prose dark:prose-invert max-w-none" x-html="formatAnalysis(result?.ai_analysis?.summary)"></div>
            </div>

            <!-- Top Videos -->
            <div class="bg-white dark:bg-youtube-gray rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4">{{ __('channel.top_videos') }}</h3>
                <div class="space-y-4">
                    <template x-for="video in result?.top_videos" :key="video.youtube_id">
                        <div class="flex items-start space-x-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <img :src="video.thumbnail_url" class="w-32 h-20 object-cover rounded" alt="">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium truncate" x-text="video.title"></h4>
                                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                    <span x-text="formatNumber(video.view_count) + ' views'"></span>
                                    <span x-text="formatNumber(video.like_count) + ' likes'"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Previous Analyses -->
        @if($previousAnalyses->isNotEmpty())
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4">{{ __('channel.previous_analyses') }}</h3>
                <div class="space-y-3">
                    @foreach($previousAnalyses as $channel)
                        <a href="{{ route('channel-analysis.show', $channel->id) }}"
                           class="flex items-center justify-between p-4 bg-white dark:bg-youtube-gray rounded-lg border border-gray-200 dark:border-gray-700 hover:border-youtube-red transition-colors">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $channel->thumbnail_url }}" class="w-10 h-10 rounded-full" alt="">
                                <div>
                                    <p class="font-medium">{{ $channel->title }}</p>
                                    <p class="text-sm text-gray-500">{{ $channel->analyzed_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function channelAnalysis() {
            return {
                channelUrl: '',
                loading: false,
                error: null,
                result: null,

                async analyze() {
                    this.loading = true;
                    this.error = null;
                    this.result = null;

                    try {
                        const response = await fetch('{{ route("channel-analysis.analyze") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ channel_url: this.channelUrl })
                        });

                        const data = await response.json();

                        if (!data.success) {
                            this.error = data.error;
                        } else {
                            this.result = data.data;
                        }
                    } catch (e) {
                        this.error = '{{ __("app.error_occurred") }}';
                    } finally {
                        this.loading = false;
                    }
                },

                formatNumber(num) {
                    if (!num) return '0';
                    if (num >= 1000000000) return (num / 1000000000).toFixed(1) + 'B';
                    if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M';
                    if (num >= 1000) return (num / 1000).toFixed(1) + 'K';
                    return num.toString();
                },

                formatAnalysis(text) {
                    if (!text) return '';
                    return text.replace(/\n/g, '<br>');
                }
            }
        }
    </script>
    @endpush
</x-layouts.dashboard>
