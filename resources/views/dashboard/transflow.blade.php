@extends('layouts.dashboard')

@section('title', __('modules.transflow'))

@section('content')
<div class="space-y-6" x-data="transflow()">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                {{ __('modules.transflow') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('modules.transflow_desc') }}
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.736 6.979C9.208 6.193 9.696 6 10 6c.304 0 .792.193 1.264.979a1 1 0 001.715-1.029C12.279 4.784 11.232 4 10 4s-2.279.784-2.979 1.95c-.285.475-.507 1-.67 1.55H6a1 1 0 000 2h.013a9.358 9.358 0 000 1H6a1 1 0 100 2h.351c.163.55.385 1.075.67 1.55C7.721 15.216 8.768 16 10 16s2.279-.784 2.979-1.95a1 1 0 10-1.715-1.029c-.472.786-.96.979-1.264.979-.304 0-.792-.193-1.264-.979a4.265 4.265 0 01-.264-.521H10a1 1 0 100-2H8.017a7.36 7.36 0 010-1H10a1 1 0 100-2H8.472c.08-.185.167-.36.264-.521z"/>
                </svg>
                1 {{ __('credits.credit') }}
            </span>
        </div>
    </div>

    <!-- Translation Type Selection -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.what_to_translate') }}</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <label :class="{'ring-2 ring-red-500 bg-red-50 dark:bg-red-900/30': type === 'title'}"
                class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none hover:border-red-300 transition-colors">
                <input type="radio" value="title" x-model="type" class="sr-only">
                <span class="flex flex-1 flex-col items-center">
                    <svg class="h-8 w-8 text-gray-600 dark:text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('modules.title') }}</span>
                </span>
            </label>

            <label :class="{'ring-2 ring-red-500 bg-red-50 dark:bg-red-900/30': type === 'description'}"
                class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none hover:border-red-300 transition-colors">
                <input type="radio" value="description" x-model="type" class="sr-only">
                <span class="flex flex-1 flex-col items-center">
                    <svg class="h-8 w-8 text-gray-600 dark:text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('modules.description') }}</span>
                </span>
            </label>

            <label :class="{'ring-2 ring-red-500 bg-red-50 dark:bg-red-900/30': type === 'tags'}"
                class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none hover:border-red-300 transition-colors">
                <input type="radio" value="tags" x-model="type" class="sr-only">
                <span class="flex flex-1 flex-col items-center">
                    <svg class="h-8 w-8 text-gray-600 dark:text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('modules.tags') }}</span>
                </span>
            </label>

            <label :class="{'ring-2 ring-red-500 bg-red-50 dark:bg-red-900/30': type === 'subtitle'}"
                class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none hover:border-red-300 transition-colors">
                <input type="radio" value="subtitle" x-model="type" class="sr-only">
                <span class="flex flex-1 flex-col items-center">
                    <svg class="h-8 w-8 text-gray-600 dark:text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('modules.subtitle') }}</span>
                </span>
            </label>
        </div>
    </div>

    <!-- Translation Form -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form @submit.prevent="translate">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Source -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('modules.source_text') }}
                        </label>
                        <select x-model="sourceLang"
                            class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-red-500 focus:border-red-500">
                            <option value="auto">{{ __('modules.auto_detect') }}</option>
                            <option value="tr">{{ __('modules.lang_tr') }}</option>
                            <option value="en">{{ __('modules.lang_en') }}</option>
                            <option value="es">{{ __('modules.lang_es') }}</option>
                            <option value="de">{{ __('modules.lang_de') }}</option>
                            <option value="fr">{{ __('modules.lang_fr') }}</option>
                            <option value="pt">{{ __('modules.lang_pt') }}</option>
                            <option value="ru">{{ __('modules.lang_ru') }}</option>
                            <option value="ar">{{ __('modules.lang_ar') }}</option>
                            <option value="ja">{{ __('modules.lang_ja') }}</option>
                            <option value="ko">{{ __('modules.lang_ko') }}</option>
                            <option value="zh">{{ __('modules.lang_zh') }}</option>
                        </select>
                    </div>
                    <textarea x-model="sourceText" rows="8" required
                        class="block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm dark:bg-gray-700 dark:text-white"
                        :placeholder="getPlaceholder()"></textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-text="sourceText.length + ' {{ __('modules.characters') }}'"></p>
                </div>

                <!-- Target -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('modules.translated_text') }}
                        </label>
                        <select x-model="targetLang" required
                            class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-red-500 focus:border-red-500">
                            <option value="">{{ __('modules.select_language') }}</option>
                            <option value="en">{{ __('modules.lang_en') }}</option>
                            <option value="tr">{{ __('modules.lang_tr') }}</option>
                            <option value="es">{{ __('modules.lang_es') }}</option>
                            <option value="de">{{ __('modules.lang_de') }}</option>
                            <option value="fr">{{ __('modules.lang_fr') }}</option>
                            <option value="pt">{{ __('modules.lang_pt') }}</option>
                            <option value="ru">{{ __('modules.lang_ru') }}</option>
                            <option value="ar">{{ __('modules.lang_ar') }}</option>
                            <option value="ja">{{ __('modules.lang_ja') }}</option>
                            <option value="ko">{{ __('modules.lang_ko') }}</option>
                            <option value="zh">{{ __('modules.lang_zh') }}</option>
                            <option value="it">{{ __('modules.lang_it') }}</option>
                            <option value="nl">{{ __('modules.lang_nl') }}</option>
                            <option value="pl">{{ __('modules.lang_pl') }}</option>
                            <option value="hi">{{ __('modules.lang_hi') }}</option>
                            <option value="id">{{ __('modules.lang_id') }}</option>
                            <option value="vi">{{ __('modules.lang_vi') }}</option>
                            <option value="th">{{ __('modules.lang_th') }}</option>
                        </select>
                    </div>
                    <div class="relative">
                        <textarea x-model="translatedText" rows="8" readonly
                            class="block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm dark:bg-gray-700 dark:text-white bg-gray-50 dark:bg-gray-600"
                            placeholder="{{ __('modules.translation_will_appear') }}"></textarea>
                        <button type="button" @click="copyTranslation" x-show="translatedText"
                            class="absolute top-2 right-2 p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SEO Suggestions -->
            <template x-if="seoSuggestions.length > 0">
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">
                        <svg class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        {{ __('modules.seo_suggestions') }}
                    </h4>
                    <ul class="space-y-1">
                        <template x-for="suggestion in seoSuggestions" :key="suggestion">
                            <li class="text-sm text-blue-700 dark:text-blue-300 flex items-start">
                                <svg class="flex-shrink-0 h-4 w-4 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span x-text="suggestion"></span>
                            </li>
                        </template>
                    </ul>
                </div>
            </template>

            <div class="mt-6 flex justify-end">
                <button type="submit" :disabled="loading || !sourceText || !targetLang"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <template x-if="loading">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </template>
                    <template x-if="!loading">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                        </svg>
                    </template>
                    <span x-text="loading ? '{{ __('app.translating') }}' : '{{ __('app.translate') }}'"></span>
                </button>
            </div>
        </form>
    </div>

    <!-- Translation History -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.recent_translations') }}</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('modules.type') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('modules.from_to') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('modules.preview') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('modules.date') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('modules.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($translations ?? [] as $translation)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ ucfirst($translation->type) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ strtoupper($translation->source_lang) }} → {{ strtoupper($translation->target_lang) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                            {{ Str::limit($translation->translated_text, 50) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $translation->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="copyToClipboard('{{ addslashes($translation->translated_text) }}')"
                                class="text-red-600 hover:text-red-900 dark:hover:text-red-400">
                                {{ __('app.copy') }}
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            {{ __('modules.no_translations_yet') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function transflow() {
    return {
        type: 'title',
        sourceLang: 'auto',
        targetLang: '',
        sourceText: '',
        translatedText: '',
        seoSuggestions: [],
        loading: false,

        getPlaceholder() {
            const placeholders = {
                'title': '{{ __("modules.title_placeholder") }}',
                'description': '{{ __("modules.description_placeholder") }}',
                'tags': '{{ __("modules.tags_placeholder") }}',
                'subtitle': '{{ __("modules.subtitle_placeholder") }}'
            };
            return placeholders[this.type] || '';
        },

        async translate() {
            if (!this.sourceText || !this.targetLang) return;

            this.loading = true;
            this.translatedText = '';
            this.seoSuggestions = [];

            try {
                const response = await fetch('{{ route("transflow.translate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        type: this.type,
                        source_text: this.sourceText,
                        source_lang: this.sourceLang,
                        target_lang: this.targetLang
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.translatedText = data.data.translation;
                    this.seoSuggestions = data.data.seo_suggestions || [];
                } else {
                    alert(data.message || '{{ __("app.error_occurred") }}');
                }
            } catch (error) {
                console.error(error);
                alert('{{ __("app.error_occurred") }}');
            } finally {
                this.loading = false;
            }
        },

        copyTranslation() {
            navigator.clipboard.writeText(this.translatedText);
            alert('{{ __("app.copied") }}');
        }
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text);
    alert('{{ __("app.copied") }}');
}
</script>
@endpush
@endsection
