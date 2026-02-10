<x-layouts.app :title="__('landing.title')">
    @push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --tube-red: #ff0000;
            --tube-red-dark: #cc0000;
            --tube-black: #0f0f0f;
            --tube-gray: #272727;
        }

        .font-display { font-family: 'Outfit', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* Animated gradient background */
        .hero-gradient {
            background:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(255, 0, 0, 0.15), transparent),
                radial-gradient(ellipse 60% 40% at 80% 50%, rgba(255, 60, 0, 0.1), transparent),
                radial-gradient(ellipse 50% 30% at 20% 80%, rgba(255, 0, 60, 0.08), transparent);
        }

        .dark .hero-gradient {
            background:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(255, 0, 0, 0.25), transparent),
                radial-gradient(ellipse 60% 40% at 80% 50%, rgba(255, 60, 0, 0.15), transparent),
                radial-gradient(ellipse 50% 30% at 20% 80%, rgba(255, 0, 60, 0.12), transparent);
        }

        /* Noise texture overlay */
        .noise-overlay::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
            opacity: 0.02;
            pointer-events: none;
        }

        .dark .noise-overlay::before { opacity: 0.04; }

        /* Glow effects */
        .glow-red {
            box-shadow: 0 0 60px rgba(255, 0, 0, 0.3), 0 0 100px rgba(255, 0, 0, 0.1);
        }

        .text-glow {
            text-shadow: 0 0 40px rgba(255, 0, 0, 0.5);
        }

        /* Staggered animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-40px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(255, 0, 0, 0.4); }
            50% { box-shadow: 0 0 40px rgba(255, 0, 0, 0.6); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .animate-fade-up { animation: fadeUp 0.8s ease-out forwards; }
        .animate-fade-in { animation: fadeIn 0.6s ease-out forwards; }
        .animate-slide-left { animation: slideInLeft 0.8s ease-out forwards; }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-gradient { animation: gradient-shift 8s ease infinite; background-size: 200% 200%; }
        .animate-pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }

        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
        .delay-600 { animation-delay: 0.6s; }

        /* Feature card hover */
        .feature-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover {
            transform: translateY(-8px) scale(1.02);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg, rgba(255,0,0,0) 0%, rgba(255,0,0,0.3) 50%, rgba(255,0,0,0) 100%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.4s;
        }

        .feature-card:hover::before { opacity: 1; }

        /* Pricing card */
        .pricing-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .pricing-card:hover {
            transform: translateY(-12px);
        }

        .pricing-popular {
            background: linear-gradient(135deg, rgba(255,0,0,0.05) 0%, rgba(255,60,0,0.02) 100%);
        }

        .dark .pricing-popular {
            background: linear-gradient(135deg, rgba(255,0,0,0.15) 0%, rgba(255,60,0,0.05) 100%);
        }

        /* Scroll indicator */
        @keyframes scroll-bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(8px); }
        }

        .scroll-indicator { animation: scroll-bounce 2s ease-in-out infinite; }

        /* Grid pattern */
        .grid-pattern {
            background-image:
                linear-gradient(rgba(255,0,0,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,0,0,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .dark .grid-pattern {
            background-image:
                linear-gradient(rgba(255,0,0,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,0,0,0.06) 1px, transparent 1px);
        }

        /* Stats counter animation */
        .stat-number {
            background: linear-gradient(135deg, #ff0000, #ff4444);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Button shine effect */
        .btn-shine {
            position: relative;
            overflow: hidden;
        }

        .btn-shine::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to right,
                transparent,
                rgba(255, 255, 255, 0.1),
                transparent
            );
            transform: rotate(45deg) translateX(-100%);
            transition: transform 0.6s;
        }

        .btn-shine:hover::after {
            transform: rotate(45deg) translateX(100%);
        }
    </style>
    @endpush

    <!-- Navigation -->
    <nav x-data="{ mobileMenuOpen: false, scrolled: false }"
         @scroll.window="scrolled = window.scrollY > 50"
         :class="scrolled ? 'bg-white/90 dark:bg-youtube-dark/90 shadow-lg shadow-black/5' : 'bg-transparent'"
         class="fixed top-0 left-0 right-0 z-50 backdrop-blur-xl border-b border-transparent transition-all duration-300"
         :style="scrolled ? 'border-color: rgba(255,0,0,0.1)' : ''">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center flex-shrink-0 group">
                    <img x-show="!darkMode" src="/images/tubekitsiyah.png" alt="TubeKit AI" class="h-9 transition-transform group-hover:scale-105">
                    <img x-show="darkMode" x-cloak src="/images/tubekitbeyaz.png" alt="TubeKit AI" class="h-9 transition-transform group-hover:scale-105">
                </a>

                <!-- Desktop Nav -->
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="#features" class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-youtube-red font-display font-medium transition-colors relative group">
                        {{ __('landing.features') }}
                        <span class="absolute bottom-0 left-4 right-4 h-0.5 bg-youtube-red scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                    </a>
                    <a href="#pricing" class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-youtube-red font-display font-medium transition-colors relative group">
                        {{ __('landing.pricing') }}
                        <span class="absolute bottom-0 left-4 right-4 h-0.5 bg-youtube-red scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                    </a>
                    <a href="#faq" class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-youtube-red font-display font-medium transition-colors relative group">
                        {{ __('landing.faq') }}
                        <span class="absolute bottom-0 left-4 right-4 h-0.5 bg-youtube-red scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                    </a>
                </div>

                <!-- Right Side -->
                <div class="flex items-center space-x-3">
                    <!-- Theme Toggle -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
                        class="p-2.5 rounded-xl bg-gray-100/80 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 transition-all duration-300"
                        :title="darkMode ? '{{ __('app.light_mode') }}' : '{{ __('app.dark_mode') }}'">
                        <svg x-show="darkMode" x-cloak class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                        </svg>
                        <svg x-show="!darkMode" class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                        </svg>
                    </button>

                    <!-- Language Switcher -->
                    <div x-data="{ langOpen: false }" class="relative">
                        <button @click="langOpen = !langOpen" @click.outside="langOpen = false"
                            class="flex items-center space-x-2 px-3 py-2.5 rounded-xl bg-gray-100/80 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 transition-all duration-300">
                            <span class="text-sm font-display font-semibold text-gray-700 dark:text-gray-200 uppercase">{{ app()->getLocale() }}</span>
                            <svg class="w-4 h-4 text-gray-500 transition-transform" :class="langOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="langOpen" x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-3 w-44 bg-white dark:bg-youtube-gray rounded-2xl shadow-2xl shadow-black/20 border border-gray-100 dark:border-gray-700 py-2 overflow-hidden">
                            <a href="{{ route('locale.switch', 'tr') }}"
                                class="flex items-center px-4 py-3 text-sm font-display {{ app()->getLocale() == 'tr' ? 'text-youtube-red bg-red-50 dark:bg-red-900/20' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }} transition-colors">
                                <span class="text-xl mr-3">🇹🇷</span>
                                <span class="font-medium">Türkçe</span>
                                @if(app()->getLocale() == 'tr')
                                    <svg class="w-5 h-5 ml-auto text-youtube-red" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </a>
                            <a href="{{ route('locale.switch', 'en') }}"
                                class="flex items-center px-4 py-3 text-sm font-display {{ app()->getLocale() == 'en' ? 'text-youtube-red bg-red-50 dark:bg-red-900/20' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }} transition-colors">
                                <span class="text-xl mr-3">🇬🇧</span>
                                <span class="font-medium">English</span>
                                @if(app()->getLocale() == 'en')
                                    <svg class="w-5 h-5 ml-auto text-youtube-red" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </a>
                        </div>
                    </div>

                    <!-- Desktop Auth -->
                    <div class="hidden sm:flex items-center space-x-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-youtube-red text-white font-display font-semibold rounded-xl hover:bg-red-600 transition-all duration-300 btn-shine">
                                {{ __('landing.dashboard') }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2.5 text-gray-600 dark:text-gray-300 hover:text-youtube-red font-display font-medium transition-colors">
                                {{ __('auth.login') }}
                            </a>
                            <a href="{{ route('register') }}" class="px-5 py-2.5 bg-youtube-red text-white font-display font-semibold rounded-xl hover:bg-red-600 transition-all duration-300 shadow-lg shadow-red-500/25 hover:shadow-red-500/40 btn-shine">
                                {{ __('landing.get_started') }}
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0 -translate-y-4"
                class="lg:hidden absolute left-4 right-4 top-full mt-2 bg-white dark:bg-youtube-gray rounded-2xl shadow-2xl shadow-black/20 border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-4 space-y-1">
                    <a href="#features" @click="mobileMenuOpen = false" class="block px-4 py-3 text-gray-700 dark:text-gray-200 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-youtube-red rounded-xl font-display font-medium transition-colors">
                        {{ __('landing.features') }}
                    </a>
                    <a href="#pricing" @click="mobileMenuOpen = false" class="block px-4 py-3 text-gray-700 dark:text-gray-200 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-youtube-red rounded-xl font-display font-medium transition-colors">
                        {{ __('landing.pricing') }}
                    </a>
                    <a href="#faq" @click="mobileMenuOpen = false" class="block px-4 py-3 text-gray-700 dark:text-gray-200 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-youtube-red rounded-xl font-display font-medium transition-colors">
                        {{ __('landing.faq') }}
                    </a>
                </div>
                <div class="border-t border-gray-100 dark:border-gray-700 p-4 space-y-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block px-4 py-3 bg-youtube-red text-white text-center font-display font-semibold rounded-xl">
                            {{ __('landing.dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-3 text-gray-700 dark:text-gray-200 text-center font-display font-medium hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition-colors">
                            {{ __('auth.login') }}
                        </a>
                        <a href="{{ route('register') }}" class="block px-4 py-3 bg-youtube-red text-white text-center font-display font-semibold rounded-xl">
                            {{ __('landing.get_started') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden hero-gradient noise-overlay">
        <!-- Animated background elements -->
        <div class="absolute inset-0 grid-pattern"></div>

        <!-- Floating orbs -->
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-youtube-red/20 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl animate-float" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/2 right-1/3 w-48 h-48 bg-pink-500/10 rounded-full blur-3xl animate-float" style="animation-delay: -1.5s;"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-20">
            <div class="text-center">
                <!-- Badge -->
                <div class="inline-flex items-center px-4 py-2 bg-youtube-red/10 dark:bg-youtube-red/20 backdrop-blur-sm rounded-full mb-8 animate-fade-up opacity-0 border border-youtube-red/20">
                    <span class="relative flex h-2.5 w-2.5 mr-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-youtube-red opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-youtube-red"></span>
                    </span>
                    <span class="text-sm font-display font-semibold text-youtube-red tracking-wide">{{ __('landing.badge_text') }}</span>
                </div>

                <!-- Main Headline -->
                <h1 class="font-display text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-black tracking-tight mb-6 animate-fade-up opacity-0 delay-100">
                    <span class="block text-gray-900 dark:text-white">{{ __('landing.hero_title') }}</span>
                    <span class="block mt-2 bg-gradient-to-r from-youtube-red via-red-500 to-orange-500 bg-clip-text text-transparent animate-gradient">
                        {{ __('landing.hero_highlight') }}
                    </span>
                </h1>

                <!-- Description -->
                <p class="max-w-2xl mx-auto text-lg sm:text-xl text-gray-600 dark:text-gray-400 font-display font-light leading-relaxed mb-10 animate-fade-up opacity-0 delay-200">
                    {{ __('landing.hero_description') }}
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12 animate-fade-up opacity-0 delay-300">
                    <a href="{{ route('register') }}"
                       class="group relative px-8 py-4 bg-youtube-red text-white text-lg font-display font-bold rounded-2xl transition-all duration-300 shadow-2xl shadow-red-500/30 hover:shadow-red-500/50 hover:-translate-y-1 btn-shine overflow-hidden">
                        <span class="relative z-10 flex items-center">
                            {{ __('landing.start_free') }}
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </span>
                    </a>
                    <a href="#features"
                       class="group px-8 py-4 bg-gray-900/5 dark:bg-white/10 backdrop-blur-sm text-gray-900 dark:text-white text-lg font-display font-semibold rounded-2xl border border-gray-200 dark:border-white/20 hover:bg-gray-900/10 dark:hover:bg-white/20 transition-all duration-300">
                        {{ __('landing.learn_more') }}
                        <svg class="inline w-5 h-5 ml-2 group-hover:translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                    </a>
                </div>

                <!-- Trust Badge -->
                <div class="flex items-center justify-center gap-2 text-sm text-gray-500 dark:text-gray-400 font-display animate-fade-up opacity-0 delay-400">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ __('landing.free_credits', ['credits' => config('credits.initial_credits', 5)]) }}
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 scroll-indicator">
            <div class="w-8 h-14 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-start justify-center p-2">
                <div class="w-1.5 h-3 bg-youtube-red rounded-full animate-bounce"></div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="relative py-20 bg-gray-900 dark:bg-black overflow-hidden">
        <div class="absolute inset-0 grid-pattern opacity-50"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-youtube-red/10 via-transparent to-youtube-red/10"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                <div class="text-center group">
                    <div class="text-5xl sm:text-6xl lg:text-7xl font-display font-black stat-number mb-3 group-hover:scale-110 transition-transform">12+</div>
                    <div class="text-sm sm:text-base text-gray-400 font-display font-medium uppercase tracking-wider">{{ __('landing.stat_tools') }}</div>
                </div>
                <div class="text-center group">
                    <div class="text-5xl sm:text-6xl lg:text-7xl font-display font-black stat-number mb-3 group-hover:scale-110 transition-transform">AI</div>
                    <div class="text-sm sm:text-base text-gray-400 font-display font-medium uppercase tracking-wider">{{ __('landing.stat_powered') }}</div>
                </div>
                <div class="text-center group">
                    <div class="text-5xl sm:text-6xl lg:text-7xl font-display font-black stat-number mb-3 group-hover:scale-110 transition-transform">100+</div>
                    <div class="text-sm sm:text-base text-gray-400 font-display font-medium uppercase tracking-wider">{{ __('landing.stat_languages') }}</div>
                </div>
                <div class="text-center group">
                    <div class="text-5xl sm:text-6xl lg:text-7xl font-display font-black stat-number mb-3 group-hover:scale-110 transition-transform">24/7</div>
                    <div class="text-sm sm:text-base text-gray-400 font-display font-medium uppercase tracking-wider">{{ __('landing.stat_available') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="relative py-24 sm:py-32 overflow-hidden">
        <div class="absolute inset-0 hero-gradient opacity-50"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-16 sm:mb-20">
                <div class="inline-flex items-center px-4 py-2 bg-youtube-red/10 rounded-full mb-6">
                    <span class="text-sm font-display font-semibold text-youtube-red uppercase tracking-wider">{{ __('landing.features') }}</span>
                </div>
                <h2 class="font-display text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                    <span class="text-gray-900 dark:text-white">{{ __('landing.features_title') }}</span>
                </h2>
                <p class="max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-400 font-display">
                    {{ __('landing.features_description') }}
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @php
                    $features = [
                        ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => __('modules.channel_analysis'), 'desc' => __('modules.channel_analysis_desc'), 'color' => 'blue', 'gradient' => 'from-blue-500 to-cyan-500'],
                        ['icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'title' => __('modules.comment_analysis'), 'desc' => __('modules.comment_analysis_desc'), 'color' => 'green', 'gradient' => 'from-green-500 to-emerald-500'],
                        ['icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'title' => __('modules.cover_ai'), 'desc' => __('modules.cover_ai_desc'), 'color' => 'purple', 'gradient' => 'from-purple-500 to-pink-500'],
                        ['icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', 'title' => __('modules.niche_analysis'), 'desc' => __('modules.niche_analysis_desc'), 'color' => 'yellow', 'gradient' => 'from-yellow-500 to-orange-500'],
                        ['icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z', 'title' => __('modules.video_ideas'), 'desc' => __('modules.video_ideas_desc'), 'color' => 'pink', 'gradient' => 'from-pink-500 to-rose-500'],
                        ['icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'title' => __('modules.trend_discovery'), 'desc' => __('modules.trend_discovery_desc'), 'color' => 'orange', 'gradient' => 'from-orange-500 to-red-500'],
                        ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'title' => __('modules.competitor_analysis'), 'desc' => __('modules.competitor_analysis_desc'), 'color' => 'indigo', 'gradient' => 'from-indigo-500 to-violet-500'],
                        ['icon' => 'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129', 'title' => __('modules.transflow'), 'desc' => __('modules.transflow_desc'), 'color' => 'cyan', 'gradient' => 'from-cyan-500 to-teal-500'],
                        ['icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222', 'title' => __('modules.creator_school'), 'desc' => __('modules.creator_school_desc'), 'color' => 'emerald', 'gradient' => 'from-emerald-500 to-green-500'],
                    ];
                @endphp

                @foreach($features as $index => $feature)
                    <div class="feature-card relative bg-white dark:bg-youtube-gray/50 backdrop-blur-sm rounded-3xl p-8 border border-gray-100 dark:border-gray-800 group overflow-hidden"
                         style="animation-delay: {{ $index * 100 }}ms;">
                        <!-- Gradient overlay on hover -->
                        <div class="absolute inset-0 bg-gradient-to-br {{ $feature['gradient'] }} opacity-0 group-hover:opacity-5 transition-opacity duration-500"></div>

                        <!-- Icon -->
                        <div class="relative mb-6">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $feature['gradient'] }} p-0.5">
                                <div class="w-full h-full bg-white dark:bg-youtube-gray rounded-2xl flex items-center justify-center group-hover:bg-opacity-0 transition-all duration-300">
                                    <svg class="w-8 h-8 text-{{ $feature['color'] }}-500 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $feature['icon'] }}"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <h3 class="font-display text-xl font-bold text-gray-900 dark:text-white mb-3">{{ $feature['title'] }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $feature['desc'] }}</p>

                        <!-- Arrow indicator -->
                        <div class="mt-6 flex items-center text-{{ $feature['color'] }}-500 font-display font-medium opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-0 group-hover:translate-x-2">
                            <span class="text-sm">{{ __('landing.learn_more') }}</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="relative py-24 sm:py-32 bg-gray-50 dark:bg-gray-900 overflow-hidden">
        <div class="absolute inset-0 grid-pattern"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-16 sm:mb-20">
                <div class="inline-flex items-center px-4 py-2 bg-youtube-red/10 rounded-full mb-6">
                    <span class="text-sm font-display font-semibold text-youtube-red uppercase tracking-wider">{{ __('landing.how_it_works') }}</span>
                </div>
                <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-bold mb-6 text-gray-900 dark:text-white">
                    {{ __('landing.how_it_works') }}
                </h2>
                <p class="max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-400 font-display">
                    {{ __('landing.how_it_works_desc') }}
                </p>
            </div>

            <!-- Steps -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-16 relative">
                <!-- Connecting line (desktop) -->
                <div class="hidden lg:block absolute top-24 left-[20%] right-[20%] h-0.5 bg-gradient-to-r from-youtube-red via-orange-500 to-green-500"></div>

                @php
                    $steps = [
                        ['number' => '01', 'title' => __('landing.step1_title'), 'desc' => __('landing.step1_desc'), 'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z', 'color' => 'youtube-red'],
                        ['number' => '02', 'title' => __('landing.step2_title'), 'desc' => __('landing.step2_desc'), 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z', 'color' => 'orange-500'],
                        ['number' => '03', 'title' => __('landing.step3_title'), 'desc' => __('landing.step3_desc'), 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'color' => 'green-500'],
                    ];
                @endphp

                @foreach($steps as $step)
                    <div class="relative group">
                        <div class="bg-white dark:bg-youtube-gray rounded-3xl p-8 border border-gray-100 dark:border-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
                            <!-- Step number -->
                            <div class="absolute -top-6 left-8">
                                <div class="w-12 h-12 bg-{{ $step['color'] }} rounded-2xl flex items-center justify-center text-white font-mono font-bold text-lg shadow-lg animate-pulse-glow">
                                    {{ $step['number'] }}
                                </div>
                            </div>

                            <!-- Icon -->
                            <div class="mt-6 mb-6">
                                <div class="w-20 h-20 rounded-2xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-10 h-10 text-{{ $step['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $step['icon'] }}"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Content -->
                            <h3 class="font-display text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ $step['title'] }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- CTA -->
            <div class="text-center mt-16">
                <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-youtube-red text-white text-lg font-display font-bold rounded-2xl hover:bg-red-600 transition-all duration-300 shadow-2xl shadow-red-500/30 hover:shadow-red-500/50 hover:-translate-y-1 btn-shine">
                    {{ __('landing.start_free') }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="relative py-24 sm:py-32 overflow-hidden">
        <div class="absolute inset-0 hero-gradient opacity-30"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-16 sm:mb-20">
                <div class="inline-flex items-center px-4 py-2 bg-youtube-red/10 rounded-full mb-6">
                    <span class="text-sm font-display font-semibold text-youtube-red uppercase tracking-wider">{{ __('landing.pricing') }}</span>
                </div>
                <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-bold mb-6 text-gray-900 dark:text-white">
                    {{ __('landing.pricing_title') }}
                </h2>
                <p class="max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-400 font-display">
                    {{ __('landing.pricing_description') }}
                </p>
            </div>

            <!-- Pricing Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                @foreach(config('credits.packages', []) as $slug => $package)
                    <div class="pricing-card relative {{ ($package['popular'] ?? false) ? 'pricing-popular' : 'bg-white dark:bg-youtube-gray/50' }} backdrop-blur-sm rounded-3xl p-8 border-2 {{ ($package['popular'] ?? false) ? 'border-youtube-red' : 'border-gray-100 dark:border-gray-800' }} overflow-hidden">
                        @if($package['popular'] ?? false)
                            <!-- Popular badge -->
                            <div class="absolute -top-px left-1/2 -translate-x-1/2">
                                <div class="px-6 py-1.5 bg-youtube-red text-white text-sm font-display font-semibold rounded-b-xl shadow-lg">
                                    {{ __('landing.most_popular') }}
                                </div>
                            </div>
                        @endif

                        <div class="text-center {{ ($package['popular'] ?? false) ? 'mt-4' : '' }}">
                            <!-- Package name -->
                            <h3 class="font-display text-xl font-bold text-gray-900 dark:text-white mb-4">{{ $package['name'] ?? $slug }}</h3>

                            <!-- Price -->
                            <div class="mb-4">
                                <span class="font-display text-5xl font-black {{ ($package['popular'] ?? false) ? 'text-youtube-red' : 'text-gray-900 dark:text-white' }}">
                                    {{ number_format(($package['price'] ?? 0) / 100, 0, ',', '.') }}
                                </span>
                                <span class="text-xl text-gray-500 font-display">₺</span>
                            </div>

                            <!-- Credits -->
                            <div class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-full mb-6">
                                <span class="font-mono font-semibold text-youtube-red">{{ $package['credits'] ?? 0 }}</span>
                                <span class="text-gray-600 dark:text-gray-400 ml-2 font-display">{{ __('app.credits') }}</span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 dark:text-gray-400 mb-8 font-display text-sm leading-relaxed">
                                {{ $package['description'] ?? '' }}
                            </p>

                            <!-- CTA Button -->
                            <a href="{{ route('register') }}"
                               class="block w-full py-4 text-center font-display font-bold rounded-xl transition-all duration-300 {{ ($package['popular'] ?? false) ? 'bg-youtube-red text-white hover:bg-red-600 shadow-lg shadow-red-500/25 hover:shadow-red-500/40' : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                                {{ __('landing.get_started') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="relative py-24 sm:py-32 bg-gray-50 dark:bg-gray-900">
        <div class="absolute inset-0 grid-pattern"></div>

        <div class="relative z-10 max-w-3xl mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-youtube-red/10 rounded-full mb-6">
                    <span class="text-sm font-display font-semibold text-youtube-red uppercase tracking-wider">{{ __('landing.faq') }}</span>
                </div>
                <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-bold mb-6 text-gray-900 dark:text-white">
                    {{ __('landing.faq_title') }}
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 font-display">
                    {{ __('landing.faq_description') }}
                </p>
            </div>

            <!-- FAQ Items -->
            <div x-data="{ openFaq: null }" class="space-y-4">
                @php
                    $faqs = [
                        ['q' => __('landing.faq1_q'), 'a' => __('landing.faq1_a')],
                        ['q' => __('landing.faq2_q'), 'a' => __('landing.faq2_a')],
                        ['q' => __('landing.faq3_q'), 'a' => __('landing.faq3_a')],
                        ['q' => __('landing.faq4_q'), 'a' => __('landing.faq4_a')],
                    ];
                @endphp

                @foreach($faqs as $index => $faq)
                    <div class="bg-white dark:bg-youtube-gray/50 backdrop-blur-sm rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300"
                         :class="openFaq === {{ $index }} ? 'shadow-xl shadow-gray-200/50 dark:shadow-none ring-2 ring-youtube-red/20' : ''">
                        <button @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
                            class="w-full px-8 py-6 text-left flex items-center justify-between group">
                            <span class="font-display font-semibold text-gray-900 dark:text-white text-lg pr-4 group-hover:text-youtube-red transition-colors">{{ $faq['q'] }}</span>
                            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center transition-all duration-300"
                                 :class="openFaq === {{ $index }} ? 'bg-youtube-red rotate-180' : ''">
                                <svg class="w-5 h-5 transition-colors"
                                     :class="openFaq === {{ $index }} ? 'text-white' : 'text-gray-500'"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                        <div x-show="openFaq === {{ $index }}" x-cloak x-collapse>
                            <div class="px-8 pb-6 text-gray-600 dark:text-gray-400 font-display leading-relaxed border-t border-gray-100 dark:border-gray-800 pt-4">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative py-24 sm:py-32 overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-youtube-red via-red-600 to-red-700"></div>
        <div class="absolute inset-0 grid-pattern opacity-10"></div>

        <!-- Floating elements -->
        <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full blur-2xl animate-float"></div>
        <div class="absolute bottom-10 right-10 w-48 h-48 bg-white/10 rounded-full blur-2xl animate-float" style="animation-delay: -2s;"></div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
            <h2 class="font-display text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6">
                {{ __('landing.cta_title') }}
            </h2>
            <p class="text-xl text-white/80 mb-10 font-display max-w-2xl mx-auto">
                {{ __('landing.cta_description') }}
            </p>
            <a href="{{ route('register') }}"
               class="inline-flex items-center px-10 py-5 bg-white text-youtube-red text-xl font-display font-bold rounded-2xl hover:bg-gray-100 transition-all duration-300 shadow-2xl hover:-translate-y-1 group">
                {{ __('landing.cta_button') }}
                <svg class="w-6 h-6 ml-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="relative bg-gray-900 dark:bg-black text-white py-16 overflow-hidden">
        <div class="absolute inset-0 grid-pattern opacity-30"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-12 mb-16">
                <!-- Brand -->
                <div class="col-span-2 md:col-span-1">
                    <img src="/images/tubekitbeyaz.png" alt="TubeKit AI" class="h-10 mb-6">
                    <p class="text-gray-400 font-display leading-relaxed">{{ __('landing.footer_description') }}</p>
                </div>

                <!-- Product -->
                <div>
                    <h4 class="font-display font-bold text-lg mb-6">{{ __('landing.product') }}</h4>
                    <ul class="space-y-4">
                        <li><a href="#features" class="text-gray-400 hover:text-white font-display transition-colors">{{ __('landing.features') }}</a></li>
                        <li><a href="#pricing" class="text-gray-400 hover:text-white font-display transition-colors">{{ __('landing.pricing') }}</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white font-display transition-colors">{{ __('auth.login') }}</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="font-display font-bold text-lg mb-6">{{ __('landing.legal') }}</h4>
                    <ul class="space-y-4">
                        <li><a href="{{ route('terms') }}" class="text-gray-400 hover:text-white font-display transition-colors">{{ __('landing.terms') }}</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-gray-400 hover:text-white font-display transition-colors">{{ __('landing.privacy') }}</a></li>
                    </ul>
                </div>

                <!-- Contact & Language -->
                <div>
                    <h4 class="font-display font-bold text-lg mb-6">{{ __('landing.contact') }}</h4>
                    <p class="text-gray-400 font-display mb-6">info@tubekitai.com</p>

                    <div class="flex items-center space-x-2">
                        <a href="{{ route('locale.switch', 'tr') }}"
                           class="px-4 py-2 rounded-xl font-display font-semibold text-sm transition-all {{ app()->getLocale() == 'tr' ? 'bg-youtube-red text-white' : 'bg-white/10 text-gray-400 hover:bg-white/20 hover:text-white' }}">
                            TR
                        </a>
                        <a href="{{ route('locale.switch', 'en') }}"
                           class="px-4 py-2 rounded-xl font-display font-semibold text-sm transition-all {{ app()->getLocale() == 'en' ? 'bg-youtube-red text-white' : 'bg-white/10 text-gray-400 hover:bg-white/20 hover:text-white' }}">
                            EN
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom bar -->
            <div class="border-t border-gray-800 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-gray-500 font-display text-sm">&copy; {{ date('Y') }} TubeKit AI. {{ __('landing.all_rights_reserved') }}</p>

                <!-- Theme toggle -->
                <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
                    class="flex items-center space-x-3 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 transition-colors group">
                    <svg x-show="darkMode" x-cloak class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                    </svg>
                    <svg x-show="!darkMode" class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                    <span class="text-gray-400 font-display text-sm group-hover:text-white transition-colors" x-text="darkMode ? '{{ __('app.light_mode') }}' : '{{ __('app.dark_mode') }}'"></span>
                </button>
            </div>
        </div>
    </footer>
</x-layouts.app>
