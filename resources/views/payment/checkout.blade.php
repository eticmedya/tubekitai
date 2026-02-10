@extends('layouts.dashboard')

@section('title', __('credits.checkout'))

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('payment.packages') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600 dark:text-gray-400 dark:hover:text-white">
                    {{ __('credits.packages') }}
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ __('credits.checkout') }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <!-- Package Summary -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('credits.order_summary') }}</h2>
        </div>

        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $package->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $package->description }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                        {{ $package->credits }} {{ __('credits.credits') }}
                    </span>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-500 dark:text-gray-400">{{ __('credits.subtotal') }}</span>
                    <span class="text-gray-900 dark:text-white">{{ number_format($package->price / 100, 2) }} TL</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-500 dark:text-gray-400">{{ __('credits.tax') }}</span>
                    <span class="text-gray-900 dark:text-white">{{ __('credits.included') }}</span>
                </div>
                <div class="flex justify-between text-lg font-semibold border-t border-gray-200 dark:border-gray-600 pt-4 mt-4">
                    <span class="text-gray-900 dark:text-white">{{ __('credits.total') }}</span>
                    <span class="text-red-600">{{ number_format($package->price / 100, 2) }} TL</span>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('credits.payment_details') }}</h3>

            @if(isset($iframeToken))
                <!-- PayTR iFrame -->
                <div class="aspect-w-16 aspect-h-9">
                    <iframe src="https://www.paytr.com/odeme/guvenli/{{ $iframeToken }}"
                        id="paytriframe"
                        frameborder="0"
                        scrolling="no"
                        class="w-full"
                        style="height: 500px;">
                    </iframe>
                </div>
            @else
                <!-- Billing Information Form -->
                <form action="{{ route('payment.process', $package->slug) }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="billing_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('credits.billing_name') }}
                            </label>
                            <input type="text" name="billing_name" id="billing_name" required
                                value="{{ old('billing_name', auth()->user()->name) }}"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm dark:bg-gray-800 dark:text-white">
                            @error('billing_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="billing_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('credits.billing_email') }}
                            </label>
                            <input type="email" name="billing_email" id="billing_email" required
                                value="{{ old('billing_email', auth()->user()->email) }}"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm dark:bg-gray-800 dark:text-white">
                            @error('billing_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="billing_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('credits.billing_phone') }}
                            </label>
                            <input type="tel" name="billing_phone" id="billing_phone" required
                                value="{{ old('billing_phone') }}"
                                placeholder="05XX XXX XX XX"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm dark:bg-gray-800 dark:text-white">
                            @error('billing_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="billing_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('credits.billing_address') }}
                            </label>
                            <textarea name="billing_address" id="billing_address" rows="2" required
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm dark:bg-gray-800 dark:text-white">{{ old('billing_address') }}</textarea>
                            @error('billing_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="terms" name="terms" type="checkbox" required
                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="terms" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            <a href="{{ route('terms') }}" target="_blank" class="text-red-600 hover:text-red-500">{{ __('credits.terms_conditions') }}</a>
                            {{ __('credits.accept_terms') }}
                        </label>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full flex justify-center items-center px-4 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            {{ __('credits.pay_now') }} - {{ number_format($package->price / 100, 2) }} TL
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <!-- Security Notice -->
        <div class="px-6 py-4 bg-green-50 dark:bg-green-900/30 border-t border-green-200 dark:border-green-800">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="ml-2 text-sm text-green-700 dark:text-green-300">
                    {{ __('credits.ssl_secure') }}
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // PayTR iframe resize
    window.addEventListener('message', function(e) {
        if (e.data.iframeHeight) {
            document.getElementById('paytriframe').style.height = e.data.iframeHeight + 'px';
        }
    });
</script>
@endpush
@endsection
