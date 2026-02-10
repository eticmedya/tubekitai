<x-layouts.dashboard :title="__('dashboard.title')" :header="__('dashboard.welcome', ['name' => auth()->user()->name])">
    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-youtube-gray rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.credits_remaining') }}</p>
                        <p class="text-3xl font-bold text-youtube-red">{{ number_format($user->credits, 1) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-youtube-red" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-youtube-gray rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.analyses_this_month') }}</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $usageStats['transaction_count'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-youtube-gray rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.credits_used') }}</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($usageStats['total_used'], 1) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-youtube-gray rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold mb-4">{{ __('dashboard.quick_actions') }}</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('channel-analysis') }}" class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-center">{{ __('modules.channel_analysis') }}</span>
                </a>

                <a href="{{ route('comment-analysis') }}" class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-center">{{ __('modules.comment_analysis') }}</span>
                </a>

                <a href="{{ route('cover-ai') }}" class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-center">{{ __('modules.cover_ai') }}</span>
                </a>

                <a href="{{ route('idea-generator') }}" class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-center">{{ __('modules.video_ideas') }}</span>
                </a>
            </div>
        </div>

        <!-- Modules Grid -->
        <div>
            <h2 class="text-lg font-semibold mb-4">{{ __('dashboard.all_modules') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($modules as $module)
                    <a href="{{ route($module['route']) }}"
                       class="block bg-white dark:bg-youtube-gray rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:border-youtube-red dark:hover:border-youtube-red transition-colors group">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-{{ $module['color'] }}-100 dark:bg-{{ $module['color'] }}-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-{{ $module['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @switch($module['icon'])
                                        @case('chart-bar')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            @break
                                        @case('play-circle')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @break
                                        @case('chat-bubble-left-right')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            @break
                                        @case('photo')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            @break
                                        @case('light-bulb')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                            @break
                                        @case('sparkles')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                            @break
                                        @default
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    @endswitch
                                </svg>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if(isset($module['badge']))
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full">
                                        {{ $module['badge'] }}
                                    </span>
                                @endif
                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 rounded-full">
                                    {{ $module['cost'] }} {{ __('app.credits') }}
                                </span>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold mb-2 group-hover:text-youtube-red transition-colors">{{ $module['title'] }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $module['description'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Recent Activity -->
        @if($recentActivities->isNotEmpty())
            <div class="bg-white dark:bg-youtube-gray rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">{{ __('dashboard.recent_activity') }}</h2>
                    <span class="text-sm text-gray-500">{{ $recentActivities->total() }} {{ __('dashboard.total_activities') }}</span>
                </div>
                <div class="space-y-3">
                    @foreach($recentActivities as $activity)
                        @php
                            $iconConfig = match($activity->action) {
                                'channel_analysis' => ['bg' => 'blue', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                                'video_analysis' => ['bg' => 'red', 'icon' => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z'],
                                'comment_analysis' => ['bg' => 'green', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                                'cover_analysis' => ['bg' => 'purple', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                                'niche_analysis' => ['bg' => 'yellow', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
                                'translation' => ['bg' => 'cyan', 'icon' => 'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129'],
                                'ai_generation' => ['bg' => 'pink', 'icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z'],
                                default => ['bg' => 'gray', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                            };

                            $viewRoute = match($activity->action) {
                                'comment_analysis' => $activity->model_id ? route('comment-analysis.show', $activity->model_id) : null,
                                'niche_analysis' => $activity->model_id ? route('niche-analysis.show', $activity->model_id) : null,
                                'video_analysis' => route('video-analysis'),
                                'channel_analysis' => route('channel-analysis'),
                                'cover_analysis' => route('cover-ai'),
                                default => null,
                            };

                            $title = $activity->subject?->video?->title
                                ?? $activity->subject?->title
                                ?? $activity->data['title']
                                ?? null;
                        @endphp
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-{{ $iconConfig['bg'] }}-100 dark:bg-{{ $iconConfig['bg'] }}-900/30 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-{{ $iconConfig['bg'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconConfig['icon'] }}"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium">
                                        {{ $activity->action_label }}@if($title): {{ Str::limit($title, 30) }}@endif
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @if($viewRoute)
                                <a href="{{ $viewRoute }}" class="text-sm text-youtube-red hover:underline">
                                    {{ __('app.view') }}
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($recentActivities->hasPages())
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        {{ $recentActivities->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-layouts.dashboard>
