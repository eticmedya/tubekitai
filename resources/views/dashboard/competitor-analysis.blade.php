@extends('layouts.dashboard')

@section('title', __('modules.competitor_analysis'))

@section('content')
<div class="space-y-6" x-data="competitorAnalysis()">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                {{ __('modules.competitor_analysis') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('competitor_analysis.description') }}
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/>
                </svg>
                3 {{ __('credits.credit') }}
            </span>
        </div>
    </div>

    <!-- Input Form -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form @submit.prevent="analyze">
            <div class="space-y-4">
                <!-- My Channel -->
                <div>
                    <label for="my_channel" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('competitor_analysis.my_channel') }}
                    </label>
                    <div class="mt-1">
                        <input type="text" id="my_channel" x-model="myChannel" required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500 sm:text-sm"
                            placeholder="{{ __('competitor_analysis.my_channel_placeholder') }}">
                    </div>
                </div>

                <!-- Competitor Channels -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('competitor_analysis.competitor_channels') }}
                    </label>
                    <template x-for="(competitor, index) in competitors" :key="index">
                        <div class="flex items-center space-x-2 mb-2">
                            <input type="text" x-model="competitors[index]" required
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                :placeholder="'{{ __('competitor_analysis.competitor') }} ' + (index + 1) + ' URL {{ __('competitor_analysis.or_name') }}'">
                            <button type="button" @click="removeCompetitor(index)" x-show="competitors.length > 1"
                                class="p-2 text-red-600 hover:text-red-800 dark:text-red-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="addCompetitor" x-show="competitors.length < 5"
                        class="mt-2 inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('competitor_analysis.add_competitor') }}
                    </button>
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
                        <span x-text="loading ? '{{ __('competitor_analysis.analyzing') }}' : '{{ __('competitor_analysis.analyze_button') }}'"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Results -->
    <template x-if="result">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ __('competitor_analysis.results_title') }}
                </h3>
                <span class="text-sm text-gray-500 dark:text-gray-400" x-text="'{{ __('competitor_analysis.competitor_count') }}: ' + result.competitors.length"></span>
            </div>

            <div class="prose dark:prose-invert max-w-none" x-html="result.analysis"></div>
        </div>
    </template>

    <!-- Previous Analyses -->
    @if($previousAnalyses->isNotEmpty())
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('competitor_analysis.previous_analyses') }}</h3>
        <div class="space-y-3">
            @foreach($previousAnalyses as $analysis)
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $analysis->context['my_channel'] ?? __('competitor_analysis.channel') }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ count($analysis->context['competitors'] ?? []) }} {{ __('competitor_analysis.competitor') }} - {{ $analysis->created_at->diffForHumans() }}
                    </p>
                </div>
                <button @click="showPrevious({{ json_encode($analysis) }})" class="text-red-600 hover:text-red-800 text-sm">
                    {{ __('common.view') }}
                </button>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function competitorAnalysis() {
    return {
        myChannel: '',
        competitors: [''],
        loading: false,
        result: null,

        addCompetitor() {
            if (this.competitors.length < 5) {
                this.competitors.push('');
            }
        },

        removeCompetitor(index) {
            this.competitors.splice(index, 1);
        },

        async analyze() {
            if (!this.myChannel || this.competitors.some(c => !c.trim())) {
                alert('{{ __('competitor_analysis.fill_all_fields') }}');
                return;
            }

            this.loading = true;
            this.result = null;

            try {
                const response = await fetch('{{ route("competitor-analysis.analyze") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        my_channel: this.myChannel,
                        competitor_channels: this.competitors.filter(c => c.trim())
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.result = data.data;
                } else {
                    alert(data.error || '{{ __('common.error_occurred') }}');
                }
            } catch (error) {
                console.error(error);
                alert('{{ __('common.error_occurred') }}');
            } finally {
                this.loading = false;
            }
        },

        showPrevious(analysis) {
            this.result = {
                analysis: analysis.result,
                competitors: analysis.context?.competitors || [],
                my_channel: analysis.context?.my_channel || ''
            };
        }
    }
}
</script>
@endpush
@endsection
