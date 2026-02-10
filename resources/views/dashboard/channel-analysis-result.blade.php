<x-layouts.dashboard :title="$channel->title . ' - ' . __('modules.channel_analysis')" :header="__('modules.channel_analysis')">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Back Button -->
        <div class="flex items-center justify-between">
            <a href="{{ route('channel-analysis') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-youtube-red transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('app.back') }}
            </a>
            <span class="text-sm text-gray-500">
                {{ __('channel.analyzed_at') }}: {{ $channel->analyzed_at->format('d.m.Y H:i') }}
            </span>
        </div>

        <!-- Channel Header -->
        <div class="bg-white dark:bg-youtube-gray rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-start space-x-4">
                <img src="{{ $channel->thumbnail_url }}" class="w-20 h-20 rounded-full" alt="{{ $channel->title }}">
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold">{{ $channel->title }}</h2>
                        <a href="{{ $channel->youtube_url }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-youtube-red text-white text-sm rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                            YouTube
                        </a>
                    </div>
                    @if($channel->custom_url)
                        <p class="text-gray-500 text-sm mt-1">{{ $channel->custom_url }}</p>
                    @endif
                    @if($channel->description)
                        <p class="text-gray-600 dark:text-gray-400 text-sm mt-2 line-clamp-3">{{ Str::limit($channel->description, 250) }}</p>
                    @endif
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <p class="text-2xl font-bold text-youtube-red">{{ $channel->formatted_subscribers }}</p>
                    <p class="text-sm text-gray-500">{{ __('channel.subscribers') }}</p>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <p class="text-2xl font-bold">{{ $channel->formatted_views }}</p>
                    <p class="text-sm text-gray-500">{{ __('channel.total_views') }}</p>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <p class="text-2xl font-bold">{{ number_format($channel->video_count) }}</p>
                    <p class="text-sm text-gray-500">{{ __('channel.videos') }}</p>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <p class="text-2xl font-bold text-green-600">{{ number_format($channel->average_views_per_video) }}</p>
                    <p class="text-sm text-gray-500">{{ __('channel.avg_views') }}</p>
                </div>
            </div>

            <!-- Channel Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                @if($channel->country)
                    <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">{{ __('channel.country') }}</p>
                        <p class="font-medium mt-1">{{ $channel->country }}</p>
                    </div>
                @endif
                @if($channel->published_at)
                    <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">{{ __('channel.created_at') }}</p>
                        <p class="font-medium mt-1">{{ $channel->published_at->format('d.m.Y') }}</p>
                    </div>
                @endif
                <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">{{ __('channel.channel_age') }}</p>
                    <p class="font-medium mt-1">{{ $channel->published_at ? $channel->published_at->diffForHumans(null, true) : '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Top Videos -->
        @if($channel->videos->isNotEmpty())
            <div class="bg-white dark:bg-youtube-gray rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 text-youtube-red mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                    </svg>
                    {{ __('channel.top_videos') }}
                </h3>
                <div class="space-y-4">
                    @foreach($channel->videos->sortByDesc('view_count')->take(5) as $video)
                        <a href="{{ $video->youtube_url }}" target="_blank" class="flex items-start space-x-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            @if($video->thumbnail_url)
                                <img src="{{ $video->thumbnail_url }}" class="w-32 h-20 object-cover rounded" alt="">
                            @endif
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium truncate">{{ $video->title }}</h4>
                                <div class="flex items-center flex-wrap gap-3 mt-2 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        {{ $video->formatted_views }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                        </svg>
                                        {{ $video->formatted_likes }}
                                    </span>
                                    @if($video->published_at)
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $video->published_at->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-green-600 text-sm font-medium">
                                {{ $video->engagement_rate }}%
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Re-analyze Button -->
        <div class="flex justify-center">
            <a href="{{ route('channel-analysis') }}?url={{ urlencode($channel->youtube_url) }}"
               class="inline-flex items-center px-6 py-3 bg-youtube-red text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                {{ __('channel.reanalyze') }}
            </a>
        </div>
    </div>
</x-layouts.dashboard>
