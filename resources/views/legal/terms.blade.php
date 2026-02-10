<x-layouts.app :title="__('legal.terms_title')">
    <!-- Navigation -->
    <nav
        class="fixed top-0 left-0 right-0 z-50 bg-white/80 dark:bg-youtube-dark/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center flex-shrink-0">
                    <img x-show="!darkMode" src="/images/tubekitsiyah.png" alt="TubeKit AI" class="h-8">
                    <img x-show="darkMode" x-cloak src="/images/tubekitbeyaz.png" alt="TubeKit AI" class="h-8">
                </a>

                <!-- Right Side -->
                <div class="flex items-center space-x-4">
                    <!-- Theme Toggle -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        <svg x-show="darkMode" x-cloak class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg x-show="!darkMode" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <a href="{{ route('home') }}"
                        class="text-gray-600 dark:text-gray-300 hover:text-youtube-red transition-colors">
                        {{ __('landing.features') }}
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 pb-16 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-youtube-red to-red-600 rounded-2xl mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold mb-4">{{ __('legal.terms_title') }}</h1>
                <p class="text-gray-600 dark:text-gray-400">{{ __('legal.last_updated') }}: 19 Ocak 2026</p>
            </div>

            <!-- Content Card -->
            <div
                class="bg-white dark:bg-youtube-gray rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800 p-8 sm:p-12">
                <div class="prose prose-lg dark:prose-invert max-w-none">

                    <!-- Section 1 -->
                    <section class="mb-10">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <span
                                class="w-8 h-8 bg-youtube-red/10 rounded-lg flex items-center justify-center mr-3 text-youtube-red font-bold text-sm">1</span>
                            {{ __('legal.terms_section1_title') }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ __('legal.terms_section1_content') }}
                        </p>
                    </section>

                    <!-- Section 2 -->
                    <section class="mb-10">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <span
                                class="w-8 h-8 bg-youtube-red/10 rounded-lg flex items-center justify-center mr-3 text-youtube-red font-bold text-sm">2</span>
                            {{ __('legal.terms_section2_title') }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-4">
                            {{ __('legal.terms_section2_content') }}
                        </p>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-youtube-red mr-2 mt-0.5 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ __('legal.terms_section2_item1') }}
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-youtube-red mr-2 mt-0.5 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ __('legal.terms_section2_item2') }}
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-youtube-red mr-2 mt-0.5 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ __('legal.terms_section2_item3') }}
                            </li>
                        </ul>
                    </section>

                    <!-- Section 3 -->
                    <section class="mb-10">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <span
                                class="w-8 h-8 bg-youtube-red/10 rounded-lg flex items-center justify-center mr-3 text-youtube-red font-bold text-sm">3</span>
                            {{ __('legal.terms_section3_title') }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ __('legal.terms_section3_content') }}
                        </p>
                    </section>

                    <!-- Section 4 -->
                    <section class="mb-10">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <span
                                class="w-8 h-8 bg-youtube-red/10 rounded-lg flex items-center justify-center mr-3 text-youtube-red font-bold text-sm">4</span>
                            {{ __('legal.terms_section4_title') }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ __('legal.terms_section4_content') }}
                        </p>
                    </section>

                    <!-- Section 5 -->
                    <section class="mb-10">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <span
                                class="w-8 h-8 bg-youtube-red/10 rounded-lg flex items-center justify-center mr-3 text-youtube-red font-bold text-sm">5</span>
                            {{ __('legal.terms_section5_title') }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ __('legal.terms_section5_content') }}
                        </p>
                    </section>

                    <!-- Section 6 -->
                    <section class="mb-10">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <span
                                class="w-8 h-8 bg-youtube-red/10 rounded-lg flex items-center justify-center mr-3 text-youtube-red font-bold text-sm">6</span>
                            {{ __('legal.terms_section6_title') }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ __('legal.terms_section6_content') }}
                        </p>
                    </section>

                    <!-- Contact -->
                    <section class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-6 mt-10">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ __('legal.contact_title') }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ __('legal.contact_content') }}
                            <a href="mailto:info@tubekitai.com"
                                class="text-youtube-red hover:underline">info@tubekitai.com</a>
                        </p>
                    </section>
                </div>
            </div>

            <!-- Back Link -->
            <div class="text-center mt-8">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-youtube-red transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('legal.back_home') }}
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} TubeKit AI. {{ __('landing.all_rights_reserved') }}
            </p>
        </div>
    </footer>
</x-layouts.app>