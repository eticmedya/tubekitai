<x-layouts.app :title="$title ?? __('dashboard.title')">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">
        <!-- Sidebar -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-youtube-gray border-r border-gray-200 dark:border-gray-700 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
        >
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-youtube-red" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0C.488 3.45.029 5.804 0 12c.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0C23.512 20.55 23.971 18.196 24 12c-.029-6.185-.484-8.549-4.385-8.816zM9 16V8l8 4-8 4z"/>
                    </svg>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">TubeKit AI</span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                @php
                    $menuItems = [
                        ['route' => 'dashboard', 'icon' => 'home', 'label' => __('nav.dashboard')],
                        ['route' => 'channel-analysis', 'icon' => 'chart-bar', 'label' => __('nav.channel_analysis')],
                        ['route' => 'video-analysis', 'icon' => 'play', 'label' => __('nav.video_analysis')],
                        ['route' => 'comment-analysis', 'icon' => 'chat', 'label' => __('nav.comment_analysis')],
                        ['route' => 'cover-ai', 'icon' => 'photo', 'label' => __('nav.cover_ai')],
                        ['route' => 'niche-analysis', 'icon' => 'bulb', 'label' => __('nav.niche_analysis')],
                        ['route' => 'idea-generator', 'icon' => 'sparkles', 'label' => __('nav.idea_generator')],
                        ['route' => 'trend-discovery', 'icon' => 'trending', 'label' => __('nav.trend_discovery')],
                        ['route' => 'competitor-analysis', 'icon' => 'users', 'label' => __('nav.competitor_analysis')],
                        ['route' => 'keyword-trends', 'icon' => 'search', 'label' => __('nav.keyword_trends')],
                        ['route' => 'transflow', 'icon' => 'translate', 'label' => __('nav.transflow')],
                        ['route' => 'creator-school', 'icon' => 'academic', 'label' => __('nav.creator_school')],
                    ];
                @endphp

                @foreach($menuItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                              {{ request()->routeIs($item['route']) || request()->routeIs($item['route'].'.*')
                                 ? 'bg-youtube-red text-white'
                                 : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        @switch($item['icon'])
                            @case('home')
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                @break
                            @case('chart-bar')
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                @break
                            @case('play')
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                @break
                            @case('chat')
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                @break
                            @case('photo')
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                @break
                            @case('bulb')
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                @break
                            @case('sparkles')
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                                @break
                            @case('trending')
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                @break
                            @case('users')
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                @break
                            @case('search')
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                @break
                            @case('translate')
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                </svg>
                                @break
                            @case('academic')
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                                </svg>
                                @break
                        @endswitch
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <!-- Credits -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('nav.credits') }}</span>
                        <span class="text-lg font-bold text-youtube-red">{{ number_format(auth()->user()->credits, 1) }}</span>
                    </div>
                    <a href="{{ route('payment.packages') }}"
                       class="block w-full text-center py-2 bg-youtube-red text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        {{ __('nav.buy_credits') }}
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header -->
            <header class="h-16 bg-white dark:bg-youtube-gray border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-4 lg:px-6">
                <!-- Mobile menu button -->
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Page Title (Desktop) -->
                <h1 class="hidden lg:block text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $header ?? '' }}
                </h1>

                <!-- Right Side -->
                <div class="flex items-center space-x-4">
                    <!-- Credits Badge -->
                    <div class="hidden sm:flex items-center bg-gray-100 dark:bg-gray-800 rounded-full px-3 py-1">
                        <svg class="w-4 h-4 text-youtube-red mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ number_format(auth()->user()->credits, 1) }}</span>
                    </div>

                    <!-- Language Switcher -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                            <span class="uppercase">{{ app()->getLocale() }}</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-32 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                            @foreach(config('app.supported_locales', ['tr' => 'Turkce', 'en' => 'English']) as $code => $name)
                                <a href="{{ route('locale.switch', $code) }}"
                                   class="block px-4 py-2 text-sm {{ app()->getLocale() === $code ? 'text-youtube-red' : 'text-gray-700 dark:text-gray-300' }} hover:bg-gray-100 dark:hover:bg-gray-700">
                                    {{ $name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Theme Toggle -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
                            class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-youtube-red text-white flex items-center justify-center font-medium">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('credits.history') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                {{ __('nav.credit_history') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    {{ __('nav.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-6 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Overlay for mobile sidebar -->
    <div x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>
</x-layouts.app>
