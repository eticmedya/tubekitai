@extends('layouts.dashboard')

@section('title', __('credits.payment_success'))

@section('content')
<div class="max-w-lg mx-auto text-center">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-8">
        <!-- Success Icon -->
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 dark:bg-green-900">
            <svg class="h-12 w-12 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <h2 class="mt-6 text-2xl font-bold text-gray-900 dark:text-white">
            {{ __('credits.payment_success') }}
        </h2>

        <p class="mt-2 text-gray-500 dark:text-gray-400">
            {{ __('credits.payment_success_desc') }}
        </p>

        <!-- Order Details -->
        <div class="mt-8 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('credits.order_id') }}</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $payment->id ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('credits.package') }}</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $payment->package->name ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('credits.credits_added') }}</dt>
                    <dd class="text-sm font-medium text-green-600">+{{ $payment->package->credits ?? 0 }} {{ __('credits.credits') }}</dd>
                </div>
                <div class="flex justify-between border-t border-gray-200 dark:border-gray-600 pt-3">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('credits.new_balance') }}</dt>
                    <dd class="text-lg font-bold text-gray-900 dark:text-white">{{ auth()->user()->credits }} {{ __('credits.credits') }}</dd>
                </div>
            </dl>
        </div>

        <!-- Confetti Animation -->
        <div class="mt-6 text-4xl" id="confetti">
            🎉
        </div>

        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
            {{ __('credits.receipt_sent') }}
        </p>

        <!-- Actions -->
        <div class="mt-8 space-y-3">
            <a href="{{ route('dashboard') }}"
                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                {{ __('credits.start_using') }}
            </a>

            <a href="{{ route('payment.packages') }}"
                class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                {{ __('credits.buy_more') }}
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Simple confetti effect
    const confetti = document.getElementById('confetti');
    let count = 0;
    const interval = setInterval(() => {
        confetti.style.transform = `scale(${1 + Math.sin(count) * 0.1})`;
        count += 0.2;
        if (count > 20) clearInterval(interval);
    }, 50);
</script>
@endpush
@endsection
