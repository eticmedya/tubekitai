@extends('layouts.dashboard')

@section('title', __('modules.niche_analysis'))

@section('content')
<div class="space-y-6" x-data="nicheAnalysis()">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                {{ __('modules.niche_analysis') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('modules.niche_analysis_desc') }}
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.736 6.979C9.208 6.193 9.696 6 10 6c.304 0 .792.193 1.264.979a1 1 0 001.715-1.029C12.279 4.784 11.232 4 10 4s-2.279.784-2.979 1.95c-.285.475-.507 1-.67 1.55H6a1 1 0 000 2h.013a9.358 9.358 0 000 1H6a1 1 0 100 2h.351c.163.55.385 1.075.67 1.55C7.721 15.216 8.768 16 10 16s2.279-.784 2.979-1.95a1 1 0 10-1.715-1.029c-.472.786-.96.979-1.264.979-.304 0-.792-.193-1.264-.979a4.265 4.265 0 01-.264-.521H10a1 1 0 100-2H8.017a7.36 7.36 0 010-1H10a1 1 0 100-2H8.472c.08-.185.167-.36.264-.521z"/>
                </svg>
                2 {{ __('credits.credits') }}
            </span>
        </div>
    </div>

    <!-- Step Indicator -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <nav aria-label="Progress">
            <ol class="flex items-center">
                <template x-for="(stepItem, index) in steps" :key="index">
                    <li :class="{'flex-1': index < steps.length - 1}" class="relative">
                        <div class="flex items-center">
                            <div :class="{
                                'bg-red-600 text-white': step > index,
                                'border-2 border-red-600 text-red-600': step === index,
                                'border-2 border-gray-300 dark:border-gray-600 text-gray-500': step < index
                            }" class="relative z-10 w-8 h-8 flex items-center justify-center rounded-full">
                                <template x-if="step > index">
                                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </template>
                                <template x-if="step <= index">
                                    <span x-text="index + 1"></span>
                                </template>
                            </div>
                            <template x-if="index < steps.length - 1">
                                <div :class="{'bg-red-600': step > index, 'bg-gray-300 dark:bg-gray-600': step <= index}"
                                    class="flex-1 h-0.5 mx-2"></div>
                            </template>
                        </div>
                        <div class="mt-2">
                            <span class="text-xs font-medium" :class="{'text-red-600': step >= index, 'text-gray-500': step < index}"
                                x-text="stepItem"></span>
                        </div>
                    </li>
                </template>
            </ol>
        </nav>
    </div>

    <!-- Step 1: Interests -->
    <div x-show="step === 0" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.what_interests_you') }}</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
            <template x-for="interest in availableInterests" :key="interest.key">
                <label :class="{'ring-2 ring-red-500 bg-red-50 dark:bg-red-900/30': selectedInterests.includes(interest.key)}"
                    class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none hover:border-red-300 transition-colors">
                    <input type="checkbox" :value="interest.key" x-model="selectedInterests" class="sr-only">
                    <span class="flex flex-1 flex-col">
                        <span class="text-2xl mb-1" x-text="interest.icon"></span>
                        <span class="block text-sm font-medium text-gray-900 dark:text-white" x-text="interest.label"></span>
                    </span>
                </label>
            </template>
        </div>
        <div class="mt-6 flex justify-end">
            <button @click="step = 1" :disabled="selectedInterests.length === 0"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                {{ __('app.next') }}
                <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Step 2: Lifestyle -->
    <div x-show="step === 1" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.your_lifestyle') }}</h3>
        <div class="space-y-4">
            <template x-for="lifestyle in availableLifestyles" :key="lifestyle.key">
                <label :class="{'ring-2 ring-red-500 bg-red-50 dark:bg-red-900/30': selectedLifestyle === lifestyle.key}"
                    class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none hover:border-red-300 transition-colors">
                    <input type="radio" :value="lifestyle.key" x-model="selectedLifestyle" class="sr-only">
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <span class="block text-sm font-medium text-gray-900 dark:text-white" x-text="lifestyle.label"></span>
                            <span class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-text="lifestyle.description"></span>
                        </span>
                    </span>
                </label>
            </template>
        </div>
        <div class="mt-6 flex justify-between">
            <button @click="step = 0"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                {{ __('app.back') }}
            </button>
            <button @click="step = 2" :disabled="!selectedLifestyle"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                {{ __('app.next') }}
                <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Step 3: Skills -->
    <div x-show="step === 2" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.your_skills') }}</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            <template x-for="skill in availableSkills" :key="skill.key">
                <label :class="{'ring-2 ring-red-500 bg-red-50 dark:bg-red-900/30': selectedSkills.includes(skill.key)}"
                    class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none hover:border-red-300 transition-colors">
                    <input type="checkbox" :value="skill.key" x-model="selectedSkills" class="sr-only">
                    <span class="flex flex-1 flex-col">
                        <span class="block text-sm font-medium text-gray-900 dark:text-white" x-text="skill.label"></span>
                    </span>
                </label>
            </template>
        </div>
        <div class="mt-6 flex justify-between">
            <button @click="step = 1"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                {{ __('app.back') }}
            </button>
            <button @click="step = 3" :disabled="selectedSkills.length === 0"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                {{ __('app.next') }}
                <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Step 4: Time Availability -->
    <div x-show="step === 3" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.time_availability') }}</h3>
        <div class="space-y-4">
            <template x-for="time in availableTimes" :key="time.key">
                <label :class="{'ring-2 ring-red-500 bg-red-50 dark:bg-red-900/30': selectedTime === time.key}"
                    class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none hover:border-red-300 transition-colors">
                    <input type="radio" :value="time.key" x-model="selectedTime" class="sr-only">
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <span class="block text-sm font-medium text-gray-900 dark:text-white" x-text="time.label"></span>
                            <span class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-text="time.description"></span>
                        </span>
                    </span>
                </label>
            </template>
        </div>
        <div class="mt-6 flex justify-between">
            <button @click="step = 2"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                {{ __('app.back') }}
            </button>
            <button @click="analyze" :disabled="!selectedTime || loading"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                <template x-if="loading">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                {{ __('modules.find_my_niche') }}
            </button>
        </div>
    </div>

    <!-- Results -->
    <template x-if="result">
        <div class="space-y-6">
            <!-- Recommended Niches -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.recommended_niches') }}</h3>
                <div class="space-y-4">
                    <template x-for="(niche, index) in result.suggested_niches" :key="index">
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <span class="flex items-center justify-center h-8 w-8 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 text-sm font-bold" x-text="index + 1"></span>
                                        <h4 class="ml-3 text-lg font-medium text-gray-900 dark:text-white" x-text="niche.name"></h4>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400" x-text="niche.why_suitable"></p>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <span :class="{
                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': niche.competition_level === 'low' || niche.competition_level === 'düşük',
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': niche.competition_level === 'medium' || niche.competition_level === 'orta',
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': niche.competition_level === 'high' || niche.competition_level === 'yüksek'
                                    }" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" x-text="niche.competition_level"></span>
                                </div>
                            </div>
                            <div class="mt-3">
                                <p class="text-sm text-gray-600 dark:text-gray-300"><strong>{{ __('modules.content_format') }}:</strong> <span x-text="niche.content_format"></span></p>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1"><strong>{{ __('modules.growth_potential') }}:</strong> <span x-text="niche.growth_potential"></span></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Content Ideas -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6" x-show="result.content_ideas && result.content_ideas.length > 0">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.content_ideas') }}</h3>
                <ul class="space-y-2">
                    <template x-for="(idea, index) in result.content_ideas" :key="index">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 text-xs font-bold mr-3" x-text="index + 1"></span>
                            <span class="text-gray-700 dark:text-gray-300" x-text="idea"></span>
                        </li>
                    </template>
                </ul>
            </div>

            <!-- Recommendations -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6" x-show="result.recommendations && result.recommendations.length > 0">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.ai_recommendations') }}</h3>
                <ul class="space-y-2">
                    <template x-for="(rec, index) in result.recommendations" :key="index">
                        <li class="flex items-start">
                            <svg class="flex-shrink-0 w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300" x-text="rec"></span>
                        </li>
                    </template>
                </ul>
            </div>

            <!-- AI Summary -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.ai_summary') }}</h3>
                <div class="prose dark:prose-invert max-w-none whitespace-pre-wrap" x-text="result.ai_summary"></div>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script>
function nicheAnalysis() {
    return {
        step: 0,
        loading: false,
        result: null,

        steps: ['{{ __("modules.interests") }}', '{{ __("modules.lifestyle") }}', '{{ __("modules.skills") }}', '{{ __("modules.time") }}'],

        selectedInterests: [],
        selectedLifestyle: '',
        selectedSkills: [],
        selectedTime: '',

        availableInterests: [
            { key: 'gaming', label: '{{ __("modules.interest_gaming") }}', icon: '🎮' },
            { key: 'tech', label: '{{ __("modules.interest_tech") }}', icon: '💻' },
            { key: 'cooking', label: '{{ __("modules.interest_cooking") }}', icon: '🍳' },
            { key: 'fitness', label: '{{ __("modules.interest_fitness") }}', icon: '💪' },
            { key: 'music', label: '{{ __("modules.interest_music") }}', icon: '🎵' },
            { key: 'art', label: '{{ __("modules.interest_art") }}', icon: '🎨' },
            { key: 'travel', label: '{{ __("modules.interest_travel") }}', icon: '✈️' },
            { key: 'education', label: '{{ __("modules.interest_education") }}', icon: '📚' },
            { key: 'finance', label: '{{ __("modules.interest_finance") }}', icon: '💰' },
            { key: 'fashion', label: '{{ __("modules.interest_fashion") }}', icon: '👗' },
            { key: 'pets', label: '{{ __("modules.interest_pets") }}', icon: '🐕' },
            { key: 'diy', label: '{{ __("modules.interest_diy") }}', icon: '🔧' },
        ],

        availableLifestyles: [
            { key: 'student', label: '{{ __("modules.lifestyle_student") }}', description: '{{ __("modules.lifestyle_student_desc") }}' },
            { key: 'employed', label: '{{ __("modules.lifestyle_employed") }}', description: '{{ __("modules.lifestyle_employed_desc") }}' },
            { key: 'freelancer', label: '{{ __("modules.lifestyle_freelancer") }}', description: '{{ __("modules.lifestyle_freelancer_desc") }}' },
            { key: 'fulltime_creator', label: '{{ __("modules.lifestyle_fulltime") }}', description: '{{ __("modules.lifestyle_fulltime_desc") }}' },
        ],

        availableSkills: [
            { key: 'video_editing', label: '{{ __("modules.skill_video_editing") }}' },
            { key: 'photography', label: '{{ __("modules.skill_photography") }}' },
            { key: 'writing', label: '{{ __("modules.skill_writing") }}' },
            { key: 'public_speaking', label: '{{ __("modules.skill_speaking") }}' },
            { key: 'animation', label: '{{ __("modules.skill_animation") }}' },
            { key: 'programming', label: '{{ __("modules.skill_programming") }}' },
        ],

        availableTimes: [
            { key: 'minimal', label: '{{ __("modules.time_minimal") }}', description: '{{ __("modules.time_minimal_desc") }}' },
            { key: 'moderate', label: '{{ __("modules.time_moderate") }}', description: '{{ __("modules.time_moderate_desc") }}' },
            { key: 'significant', label: '{{ __("modules.time_significant") }}', description: '{{ __("modules.time_significant_desc") }}' },
            { key: 'fulltime', label: '{{ __("modules.time_fulltime") }}', description: '{{ __("modules.time_fulltime_desc") }}' },
        ],

        async analyze() {
            this.loading = true;
            this.result = null;

            try {
                const response = await fetch('{{ route("niche-analysis.analyze") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        interests: this.selectedInterests,
                        lifestyle: this.selectedLifestyle,
                        skills: this.selectedSkills,
                        time_availability: this.selectedTime
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.result = data.data;
                    this.step = 4;
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
