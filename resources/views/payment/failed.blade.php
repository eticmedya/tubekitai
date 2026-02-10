@extends('layouts.dashboard')

@section('title', __('credits.payment_failed'))

@section('content')
<div class="max-w-lg mx-auto text-center">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-8">
        <!-- Error Icon -->
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-100 dark:bg-red-900">
            <svg class="h-12 w-12 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>

        <h2 class="mt-6 text-2xl font-bold text-gray-900 dark:text-white">
            {{ __('credits.payment_failed') }}
        </h2>

        <p class="mt-2 text-gray-500 dark:text-gray-400">
            {{ __('credits.payment_failed_desc') }}
        </p>

        @if(isset($error))
        <div class="mt-6 bg-red-50 dark:bg-red-900/30 rounded-lg p-4">
            <p class="text-sm text-red-600 dark:text-red-400">
                {{ $error }}
            </p>
        </div>
        @endif

        <!-- Common Issues -->
        <div class="mt-8 text-left bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">{{ __('credits.common_issues') }}</h3>
            <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                <li class="flex items-start">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('credits.issue_insufficient_funds') }}
                </li>
                <li class="flex items-start">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('credits.issue_card_expired') }}
                </li>
                <li class="flex items-start">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('credits.issue_3ds_failed') }}
                </li>
                <li class="flex items-start">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('credits.issue_bank_limit') }}
                </li>
            </ul>
        </div>

        <!-- Actions -->
        <div class="mt-8 space-y-3">
            <a href="{{ route('payment.packages') }}"
                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                {{ __('credits.try_again') }}
            </a>

            <a href="{{ route('dashboard') }}"
                class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                {{ __('app.back_to_dashboard') }}
            </a>
        </div>

        <!-- Support -->
        <p class="mt-6 text-sm text-gray-500 dark:text-gray-400">
            {{ __('credits.need_help') }}
            <a href="mailto:support@tubekitai.com" class="text-red-600 hover:text-red-500">support@tubekitai.com</a>
        </p>
    </div>
</div>
@endsection
