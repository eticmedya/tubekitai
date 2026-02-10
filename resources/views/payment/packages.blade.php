@extends('layouts.dashboard')

@section('title', __('credits.buy_credits'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="text-center">
        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
            {{ __('credits.choose_package') }}
        </h2>
        <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 dark:text-gray-400 sm:mt-4">
            {{ __('credits.choose_package_desc') }}
        </p>
    </div>

    <!-- Current Balance -->
    <div class="flex justify-center">
        <div class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-full">
            <svg class="h-5 w-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.736 6.979C9.208 6.193 9.696 6 10 6c.304 0 .792.193 1.264.979a1 1 0 001.715-1.029C12.279 4.784 11.232 4 10 4s-2.279.784-2.979 1.95c-.285.475-.507 1-.67 1.55H6a1 1 0 000 2h.013a9.358 9.358 0 000 1H6a1 1 0 100 2h.351c.163.55.385 1.075.67 1.55C7.721 15.216 8.768 16 10 16s2.279-.784 2.979-1.95a1 1 0 10-1.715-1.029c-.472.786-.96.979-1.264.979-.304 0-.792-.193-1.264-.979a4.265 4.265 0 01-.264-.521H10a1 1 0 100-2H8.017a7.36 7.36 0 010-1H10a1 1 0 100-2H8.472c.08-.185.167-.36.264-.521z"/>
            </svg>
            <span class="text-gray-700 dark:text-gray-300">{{ __('credits.current_balance') }}:</span>
            <span class="ml-2 font-bold text-gray-900 dark:text-white">{{ auth()->user()->credits }} {{ __('credits.credits') }}</span>
        </div>
    </div>

    <!-- Packages Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($packages as $package)
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden {{ $package->is_popular ? 'ring-2 ring-red-500' : '' }}">
            @if($package->is_popular)
            <div class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">
                {{ __('credits.most_popular') }}
            </div>
            @endif

            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $package->name }}</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $package->description }}</p>

                <div class="mt-4">
                    <span class="text-4xl font-extrabold text-gray-900 dark:text-white">{{ number_format($package->price / 100, 0) }}</span>
                    <span class="text-xl font-medium text-gray-500 dark:text-gray-400">TL</span>
                </div>

                <div class="mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                        {{ $package->credits }} {{ __('credits.credits') }}
                    </span>
                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                        ({{ number_format(($package->price / 100) / $package->credits, 2) }} TL / {{ __('credits.credit') }})
                    </span>
                </div>

                <ul class="mt-6 space-y-3">
                    @foreach($package->features ?? [] as $feature)
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>

                <div class="mt-8">
                    <a href="{{ route('payment.checkout', $package->slug) }}"
                        class="block w-full text-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white {{ $package->is_popular ? 'bg-red-600 hover:bg-red-700' : 'bg-gray-800 dark:bg-gray-600 hover:bg-gray-700 dark:hover:bg-gray-500' }} transition-colors">
                        {{ __('credits.select_package') }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- FAQ Section -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('credits.faq_title') }}</h3>

        <div class="space-y-4" x-data="{ open: null }">
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <button @click="open = open === 1 ? null : 1" class="flex justify-between items-center w-full text-left">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ __('credits.faq_q1') }}</span>
                    <svg class="h-5 w-5 text-gray-500 transition-transform" :class="{'rotate-180': open === 1}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <p x-show="open === 1" x-collapse class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('credits.faq_a1') }}
                </p>
            </div>

            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <button @click="open = open === 2 ? null : 2" class="flex justify-between items-center w-full text-left">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ __('credits.faq_q2') }}</span>
                    <svg class="h-5 w-5 text-gray-500 transition-transform" :class="{'rotate-180': open === 2}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <p x-show="open === 2" x-collapse class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('credits.faq_a2') }}
                </p>
            </div>

            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <button @click="open = open === 3 ? null : 3" class="flex justify-between items-center w-full text-left">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ __('credits.faq_q3') }}</span>
                    <svg class="h-5 w-5 text-gray-500 transition-transform" :class="{'rotate-180': open === 3}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <p x-show="open === 3" x-collapse class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('credits.faq_a3') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="text-center">
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('credits.secure_payment') }}</p>
        <div class="flex justify-center space-x-4">
            <img src="{{ asset('images/visa.svg') }}" alt="Visa" class="h-8">
            <img src="{{ asset('images/mastercard.svg') }}" alt="Mastercard" class="h-8">
            <img src="{{ asset('images/amex.svg') }}" alt="Amex" class="h-8">
        </div>
        <p class="mt-4 text-xs text-gray-400">
            {{ __('credits.powered_by_paytr') }}
        </p>
    </div>
</div>
@endsection
