@extends('layouts.dashboard')

@section('title', __('modules.creator_school'))

@section('content')
<div class="space-y-6" x-data="creatorSchool()">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                {{ __('creator_school.title') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('creator_school.description') }}
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                {{ __('creator_school.free_badge') }}
            </span>
        </div>
    </div>

    <!-- Course Tabs -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex -mb-px overflow-x-auto">
                <button @click="activeTab = 'getting-started'" :class="activeTab === 'getting-started' ? 'border-youtube-red text-youtube-red' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    {{ __('creator_school.tabs.getting_started') }}
                </button>
                <button @click="activeTab = 'equipment'" :class="activeTab === 'equipment' ? 'border-youtube-red text-youtube-red' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    {{ __('creator_school.tabs.equipment') }}
                </button>
                <button @click="activeTab = 'content'" :class="activeTab === 'content' ? 'border-youtube-red text-youtube-red' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    {{ __('creator_school.tabs.content_creation') }}
                </button>
                <button @click="activeTab = 'growth'" :class="activeTab === 'growth' ? 'border-youtube-red text-youtube-red' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    {{ __('creator_school.tabs.growth') }}
                </button>
                <button @click="activeTab = 'monetization'" :class="activeTab === 'monetization' ? 'border-youtube-red text-youtube-red' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    {{ __('creator_school.tabs.monetization') }}
                </button>
                <button @click="activeTab = 'motivation'" :class="activeTab === 'motivation' ? 'border-youtube-red text-youtube-red' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    {{ __('creator_school.tabs.motivation') }}
                </button>
            </nav>
        </div>

        <!-- Tab Contents -->
        <div class="p-6">
            <!-- Getting Started -->
            <div x-show="activeTab === 'getting-started'" x-cloak>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('creator_school.getting_started.title') }}</h3>
                <div class="space-y-6">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">1. {{ __('creator_school.getting_started.niche_selection') }}</h4>
                        <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                            <p>{{ __('creator_school.getting_started.niche_intro') }}</p>
                            <ul class="mt-3 space-y-2">
                                <li><strong>{{ __('creator_school.getting_started.passion') }}:</strong> {{ __('creator_school.getting_started.passion_desc') }}</li>
                                <li><strong>{{ __('creator_school.getting_started.knowledge') }}:</strong> {{ __('creator_school.getting_started.knowledge_desc') }}</li>
                                <li><strong>{{ __('creator_school.getting_started.demand') }}:</strong> {{ __('creator_school.getting_started.demand_desc') }}</li>
                                <li><strong>{{ __('creator_school.getting_started.competition') }}:</strong> {{ __('creator_school.getting_started.competition_desc') }}</li>
                            </ul>
                            <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <p class="text-sm text-blue-800 dark:text-blue-300"><strong>{{ __('creator_school.tip') }}:</strong> {{ __('creator_school.getting_started.niche_tip') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">2. {{ __('creator_school.getting_started.channel_creation') }}</h4>
                        <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                            <p>{{ __('creator_school.getting_started.channel_intro') }}</p>
                            <ol class="mt-3 space-y-2">
                                <li><strong>{{ __('creator_school.getting_started.channel_name') }}:</strong> {{ __('creator_school.getting_started.channel_name_desc') }}</li>
                                <li><strong>{{ __('creator_school.getting_started.profile_pic') }}:</strong> {{ __('creator_school.getting_started.profile_pic_desc') }}</li>
                                <li><strong>{{ __('creator_school.getting_started.banner') }}:</strong> {{ __('creator_school.getting_started.banner_desc') }}</li>
                                <li><strong>{{ __('creator_school.getting_started.channel_desc') }}:</strong> {{ __('creator_school.getting_started.channel_desc_desc') }}</li>
                                <li><strong>{{ __('creator_school.getting_started.links') }}:</strong> {{ __('creator_school.getting_started.links_desc') }}</li>
                            </ol>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">3. {{ __('creator_school.getting_started.target_audience') }}</h4>
                        <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                            <p>{{ __('creator_school.getting_started.audience_intro') }}</p>
                            <ul class="mt-3 space-y-2">
                                <li>{{ __('creator_school.getting_started.audience_age') }}</li>
                                <li>{{ __('creator_school.getting_started.audience_interests') }}</li>
                                <li>{{ __('creator_school.getting_started.audience_problems') }}</li>
                                <li>{{ __('creator_school.getting_started.audience_device') }}</li>
                                <li>{{ __('creator_school.getting_started.audience_time') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipment -->
            <div x-show="activeTab === 'equipment'" x-cloak>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('creator_school.equipment.title') }}</h3>
                <div class="space-y-6">
                    <!-- Starter Budget -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 mr-3">
                                {{ __('creator_school.equipment.starter_budget') }}
                            </span>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.equipment.camera') }}: {{ __('creator_school.equipment.smartphone') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.equipment.smartphone_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.equipment.microphone') }}: {{ __('creator_school.equipment.lav_mic') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.equipment.lav_mic_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.equipment.lighting') }}: {{ __('creator_school.equipment.natural_light') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.equipment.natural_light_desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mid Budget -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mr-3">
                                {{ __('creator_school.equipment.mid_budget') }}
                            </span>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.equipment.camera') }}: {{ __('creator_school.equipment.vlog_camera') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.equipment.vlog_camera_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.equipment.microphone') }}: {{ __('creator_school.equipment.shotgun_mic') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.equipment.shotgun_mic_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.equipment.lighting') }}: {{ __('creator_school.equipment.softbox') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.equipment.softbox_desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pro Budget -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 mr-3">
                                {{ __('creator_school.equipment.pro_budget') }}
                            </span>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.equipment.camera') }}: {{ __('creator_school.equipment.pro_camera') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.equipment.pro_camera_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.equipment.microphone') }}: {{ __('creator_school.equipment.pro_mic') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.equipment.pro_mic_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.equipment.lighting') }}: {{ __('creator_school.equipment.pro_light') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.equipment.pro_light_desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Free Software -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">{{ __('creator_school.equipment.free_software') }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                                <p class="font-medium text-gray-900 dark:text-white">DaVinci Resolve</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.equipment.davinci_desc') }}</p>
                            </div>
                            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                                <p class="font-medium text-gray-900 dark:text-white">OBS Studio</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.equipment.obs_desc') }}</p>
                            </div>
                            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                                <p class="font-medium text-gray-900 dark:text-white">Canva</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.equipment.canva_desc') }}</p>
                            </div>
                            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                                <p class="font-medium text-gray-900 dark:text-white">Audacity</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.equipment.audacity_desc') }}</p>
                            </div>
                            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                                <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.equipment.capcut') }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.equipment.capcut_desc') }}</p>
                            </div>
                            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg border-2 border-purple-200 dark:border-purple-800">
                                <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.equipment.ai_tools') }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.equipment.ai_tools_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Creation -->
            <div x-show="activeTab === 'content'" x-cloak>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('creator_school.content.title') }}</h3>
                <div class="space-y-6">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">{{ __('creator_school.content.first_10_videos') }}</h4>
                        <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                            <ol class="space-y-3">
                                <li><strong>{{ __('creator_school.content.rule_1') }}:</strong> {{ __('creator_school.content.rule_1_desc') }}</li>
                                <li><strong>{{ __('creator_school.content.rule_2') }}:</strong> {{ __('creator_school.content.rule_2_desc') }}</li>
                                <li><strong>{{ __('creator_school.content.rule_3') }}:</strong> {{ __('creator_school.content.rule_3_desc') }}</li>
                                <li><strong>{{ __('creator_school.content.rule_4') }}:</strong> {{ __('creator_school.content.rule_4_desc') }}</li>
                                <li><strong>{{ __('creator_school.content.rule_5') }}:</strong> {{ __('creator_school.content.rule_5_desc') }}</li>
                                <li><strong>{{ __('creator_school.content.rule_6') }}:</strong> {{ __('creator_school.content.rule_6_desc') }}</li>
                                <li><strong>{{ __('creator_school.content.rule_7') }}:</strong> {{ __('creator_school.content.rule_7_desc') }}</li>
                                <li><strong>{{ __('creator_school.content.rule_8') }}:</strong> {{ __('creator_school.content.rule_8_desc') }}</li>
                                <li><strong>{{ __('creator_school.content.rule_9') }}:</strong> {{ __('creator_school.content.rule_9_desc') }}</li>
                                <li><strong>{{ __('creator_school.content.rule_10') }}:</strong> {{ __('creator_school.content.rule_10_desc') }}</li>
                            </ol>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">{{ __('creator_school.content.video_structure') }}</h4>
                        <div class="space-y-4">
                            <div class="flex items-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                <span class="text-2xl font-bold text-red-600 mr-4">1</span>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.content.hook') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.content.hook_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                <span class="text-2xl font-bold text-yellow-600 mr-4">2</span>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.content.intro') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.content.intro_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <span class="text-2xl font-bold text-blue-600 mr-4">3</span>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.content.main_content') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.content.main_content_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <span class="text-2xl font-bold text-green-600 mr-4">4</span>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.content.cta_outro') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.content.cta_outro_desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">{{ __('creator_school.content.title_formulas') }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="p-3 bg-white dark:bg-gray-800 rounded border-l-4 border-red-500">
                                <p class="text-sm">"X {{ __('creator_school.content.formula_1') }} Y"</p>
                            </div>
                            <div class="p-3 bg-white dark:bg-gray-800 rounded border-l-4 border-blue-500">
                                <p class="text-sm">"{{ __('creator_school.content.formula_2') }}"</p>
                            </div>
                            <div class="p-3 bg-white dark:bg-gray-800 rounded border-l-4 border-green-500">
                                <p class="text-sm">"{{ __('creator_school.content.formula_3') }}"</p>
                            </div>
                            <div class="p-3 bg-white dark:bg-gray-800 rounded border-l-4 border-yellow-500">
                                <p class="text-sm">"X {{ __('creator_school.content.formula_4') }} Y?"</p>
                            </div>
                            <div class="p-3 bg-white dark:bg-gray-800 rounded border-l-4 border-purple-500">
                                <p class="text-sm">"X {{ __('creator_school.content.formula_5') }}"</p>
                            </div>
                            <div class="p-3 bg-white dark:bg-gray-800 rounded border-l-4 border-pink-500">
                                <p class="text-sm">"{{ __('creator_school.content.formula_6') }}"</p>
                            </div>
                            <div class="p-3 bg-white dark:bg-gray-800 rounded border-l-4 border-indigo-500">
                                <p class="text-sm">"{{ __('creator_school.content.formula_7') }}"</p>
                            </div>
                            <div class="p-3 bg-white dark:bg-gray-800 rounded border-l-4 border-orange-500">
                                <p class="text-sm">"{{ __('creator_school.content.formula_8') }}"</p>
                            </div>
                        </div>
                    </div>

                    <!-- Shorts Strategy -->
                    <div class="bg-gradient-to-r from-red-500 to-pink-500 rounded-lg p-6 text-white">
                        <h4 class="text-lg font-bold mb-3">{{ __('creator_school.content.shorts_strategy') }}</h4>
                        <p class="text-red-100 mb-4">{{ __('creator_school.content.shorts_intro') }}</p>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>{{ __('creator_school.content.shorts_tip_1') }}</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>{{ __('creator_school.content.shorts_tip_2') }}</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>{{ __('creator_school.content.shorts_tip_3') }}</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>{{ __('creator_school.content.shorts_tip_4') }}</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>{{ __('creator_school.content.shorts_tip_5') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Growth -->
            <div x-show="activeTab === 'growth'" x-cloak>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('creator_school.growth.title') }}</h3>
                <div class="space-y-6">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">{{ __('creator_school.growth.algorithm') }}</h4>
                        <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                            <p>{{ __('creator_school.growth.algorithm_intro') }}</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.growth.ctr') }}</p>
                                    <p class="text-sm text-gray-500">{{ __('creator_school.growth.ctr_desc') }}</p>
                                    <p class="text-xs text-green-600 mt-1">{{ __('creator_school.growth.ctr_target') }}</p>
                                </div>
                                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.growth.watch_time') }}</p>
                                    <p class="text-sm text-gray-500">{{ __('creator_school.growth.watch_time_desc') }}</p>
                                    <p class="text-xs text-green-600 mt-1">{{ __('creator_school.growth.avd_target') }}</p>
                                </div>
                                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.growth.engagement') }}</p>
                                    <p class="text-sm text-gray-500">{{ __('creator_school.growth.engagement_desc') }}</p>
                                    <p class="text-xs text-green-600 mt-1">{{ __('creator_school.growth.reply_comments') }}</p>
                                </div>
                                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.growth.session_time') }}</p>
                                    <p class="text-sm text-gray-500">{{ __('creator_school.growth.session_time_desc') }}</p>
                                    <p class="text-xs text-green-600 mt-1">{{ __('creator_school.growth.use_playlists') }}</p>
                                </div>
                            </div>
                            <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <p class="text-sm text-blue-800 dark:text-blue-300">{{ __('creator_school.growth.new_subs_source') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">{{ __('creator_school.growth.tactics') }}</h4>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-gray-700 dark:text-gray-300"><strong>YouTube Shorts:</strong> {{ __('creator_school.growth.shorts_desc') }}</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-gray-700 dark:text-gray-300"><strong>{{ __('creator_school.growth.trending') }}:</strong> {{ __('creator_school.growth.trending_desc') }}</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-gray-700 dark:text-gray-300"><strong>{{ __('creator_school.growth.collaborations') }}:</strong> {{ __('creator_school.growth.collaborations_desc') }}</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-gray-700 dark:text-gray-300"><strong>{{ __('creator_school.growth.community') }}:</strong> {{ __('creator_school.growth.community_desc') }}</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-gray-700 dark:text-gray-300"><strong>{{ __('creator_school.growth.seo') }}:</strong> {{ __('creator_school.growth.seo_desc') }}</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-gray-700 dark:text-gray-300"><strong>{{ __('creator_school.growth.crossplatform') }}:</strong> {{ __('creator_school.growth.crossplatform_desc') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Monetization -->
            <div x-show="activeTab === 'monetization'" x-cloak>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('creator_school.monetization.title') }}</h3>
                <div class="space-y-6">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">{{ __('creator_school.monetization.ypp') }}</h4>
                        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg mb-4">
                            <p class="font-medium text-yellow-800 dark:text-yellow-300">{{ __('creator_school.monetization.requirements') }}:</p>
                            <ul class="mt-2 text-sm text-yellow-700 dark:text-yellow-400 space-y-1">
                                <li>{{ __('creator_school.monetization.req_subs') }}</li>
                                <li>{{ __('creator_school.monetization.req_hours') }}</li>
                            </ul>
                            <p class="mt-2 text-xs text-yellow-600 dark:text-yellow-500">{{ __('creator_school.monetization.req_note') }}</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                                <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.monetization.adsense') }}</p>
                                <p class="text-sm text-gray-500">{{ __('creator_school.monetization.adsense_desc') }}</p>
                            </div>
                            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                                <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.monetization.memberships') }}</p>
                                <p class="text-sm text-gray-500">{{ __('creator_school.monetization.memberships_desc') }}</p>
                            </div>
                            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                                <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.monetization.superchat') }}</p>
                                <p class="text-sm text-gray-500">{{ __('creator_school.monetization.superchat_desc') }}</p>
                            </div>
                            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                                <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.monetization.shopping') }}</p>
                                <p class="text-sm text-gray-500">{{ __('creator_school.monetization.shopping_desc') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">{{ __('creator_school.monetization.alternatives') }}</h4>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <span class="text-green-500 font-bold mr-3">$</span>
                                <span class="text-gray-700 dark:text-gray-300"><strong>{{ __('creator_school.monetization.sponsorships') }}:</strong> {{ __('creator_school.monetization.sponsorships_desc') }}</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-500 font-bold mr-3">$</span>
                                <span class="text-gray-700 dark:text-gray-300"><strong>{{ __('creator_school.monetization.affiliate') }}:</strong> {{ __('creator_school.monetization.affiliate_desc') }}</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-500 font-bold mr-3">$</span>
                                <span class="text-gray-700 dark:text-gray-300"><strong>{{ __('creator_school.monetization.digital') }}:</strong> {{ __('creator_school.monetization.digital_desc') }}</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-500 font-bold mr-3">$</span>
                                <span class="text-gray-700 dark:text-gray-300"><strong>{{ __('creator_school.monetization.consulting') }}:</strong> {{ __('creator_school.monetization.consulting_desc') }}</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-500 font-bold mr-3">$</span>
                                <span class="text-gray-700 dark:text-gray-300"><strong>{{ __('creator_school.monetization.merch') }}:</strong> {{ __('creator_school.monetization.merch_desc') }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Income Expectations -->
                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg p-6 text-white">
                        <h4 class="text-lg font-bold mb-3">{{ __('creator_school.monetization.income_expectations') }}</h4>
                        <div class="space-y-2">
                            <p>{{ __('creator_school.monetization.income_1k') }}</p>
                            <p>{{ __('creator_school.monetization.income_10k') }}</p>
                            <p>{{ __('creator_school.monetization.income_100k') }}</p>
                        </div>
                        <p class="mt-3 text-sm text-green-100">{{ __('creator_school.monetization.income_note') }}</p>
                    </div>
                </div>
            </div>

            <!-- Motivation -->
            <div x-show="activeTab === 'motivation'" x-cloak>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('creator_school.motivation.title') }}</h3>
                <div class="space-y-6">
                    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-6 text-white">
                        <h4 class="text-xl font-bold mb-2">{{ __('creator_school.motivation.remember') }}</h4>
                        <p class="text-red-100">{{ __('creator_school.motivation.remember_desc') }}</p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">{{ __('creator_school.motivation.tips') }}</h4>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <span class="text-2xl mr-4">🎯</span>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.motivation.small_goals') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.motivation.small_goals_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="text-2xl mr-4">📅</span>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.motivation.content_calendar') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.motivation.content_calendar_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="text-2xl mr-4">👥</span>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.motivation.join_community') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.motivation.join_community_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="text-2xl mr-4">📊</span>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.motivation.dont_obsess_stats') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.motivation.dont_obsess_stats_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="text-2xl mr-4">🔄</span>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('creator_school.motivation.keep_learning') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('creator_school.motivation.keep_learning_desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">{{ __('creator_school.motivation.burnout') }}</h4>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-300">
                            <li>• {{ __('creator_school.motivation.burnout_1') }}</li>
                            <li>• {{ __('creator_school.motivation.burnout_2') }}</li>
                            <li>• {{ __('creator_school.motivation.burnout_3') }}</li>
                            <li>• {{ __('creator_school.motivation.burnout_4') }}</li>
                            <li>• {{ __('creator_school.motivation.burnout_5') }}</li>
                            <li>• {{ __('creator_school.motivation.burnout_6') }}</li>
                        </ul>
                    </div>

                    <!-- Success Stories -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">{{ __('creator_school.motivation.success_stories') }}</h4>
                        <div class="space-y-3">
                            <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border-l-4 border-red-500">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ __('creator_school.motivation.success_1') }}</p>
                            </div>
                            <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border-l-4 border-blue-500">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ __('creator_school.motivation.success_2') }}</p>
                            </div>
                            <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border-l-4 border-green-500">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ __('creator_school.motivation.success_3') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function creatorSchool() {
    return {
        activeTab: 'getting-started'
    }
}
</script>
@endpush
@endsection
