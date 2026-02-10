<?php

namespace App\Services\Payment;

use App\Enums\PaymentStatus;
use App\Models\CreditPackage;
use App\Models\Payment;
use App\Models\User;
use App\Services\Credit\CreditManager;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayTRService
{
    protected string $merchantId;
    protected string $merchantKey;
    protected string $merchantSalt;
    protected bool $testMode;
    protected string $baseUrl;

    public function __construct(
        ?string $merchantId = null,
        ?string $merchantKey = null,
        ?string $merchantSalt = null,
        ?bool $testMode = null
    ) {
        $this->merchantId = $merchantId ?? config('services.paytr.merchant_id');
        $this->merchantKey = $merchantKey ?? config('services.paytr.merchant_key');
        $this->merchantSalt = $merchantSalt ?? config('services.paytr.merchant_salt');
        $this->testMode = $testMode ?? config('services.paytr.test_mode', true);
        $this->baseUrl = config('services.paytr.base_url', 'https://www.paytr.com/odeme/api/get-token');
    }

    /**
     * Create payment and get iframe token.
     */
    public function createPayment(User $user, CreditPackage $package): array
    {
        $merchantOid = Payment::generateMerchantOid();

        // Create payment record
        $payment = Payment::create([
            'user_id' => $user->id,
            'credit_package_id' => $package->id,
            'merchant_oid' => $merchantOid,
            'amount' => $package->price,
            'currency' => $package->currency,
            'credits_purchased' => $package->credits,
            'status' => PaymentStatus::PENDING,
        ]);

        // Prepare basket
        $basket = [
            [
                $package->name . ' - ' . $package->credits . ' Kredi',
                $package->price / 100, // Convert to TL
                1, // Quantity
            ],
        ];
        $basketJson = base64_encode(json_encode($basket));

        // User info
        $userIp = request()->ip();
        $email = $user->email;
        $userName = $user->name;
        $userAddress = 'Not provided';
        $userPhone = 'Not provided';

        // Payment params
        $paymentAmount = $package->price; // In kuruş
        $currency = 'TL';
        $noInstallment = 1;
        $maxInstallment = 0;
        $lang = app()->getLocale() === 'tr' ? 'tr' : 'en';
        $testMode = $this->testMode ? '1' : '0';

        // Callback URLs
        $merchantOkUrl = route('payment.success');
        $merchantFailUrl = route('payment.fail');

        // Create hash
        $hashStr = $this->merchantId .
            $userIp .
            $merchantOid .
            $email .
            $paymentAmount .
            $basketJson .
            $noInstallment .
            $maxInstallment .
            $currency .
            $testMode;

        $paytrToken = base64_encode(hash_hmac('sha256', $hashStr . $this->merchantSalt, $this->merchantKey, true));

        // API request
        $response = Http::asForm()->timeout(30)->post($this->baseUrl, [
            'merchant_id' => $this->merchantId,
            'user_ip' => $userIp,
            'merchant_oid' => $merchantOid,
            'email' => $email,
            'payment_amount' => $paymentAmount,
            'paytr_token' => $paytrToken,
            'user_basket' => $basketJson,
            'debug_on' => $this->testMode ? 1 : 0,
            'no_installment' => $noInstallment,
            'max_installment' => $maxInstallment,
            'user_name' => $userName,
            'user_address' => $userAddress,
            'user_phone' => $userPhone,
            'merchant_ok_url' => $merchantOkUrl,
            'merchant_fail_url' => $merchantFailUrl,
            'timeout_limit' => 30,
            'currency' => $currency,
            'test_mode' => $testMode,
            'lang' => $lang,
        ]);

        $result = $response->json();

        Log::info('PayTR Token Request', [
            'merchant_oid' => $merchantOid,
            'response' => $result,
        ]);

        if ($result['status'] !== 'success') {
            $payment->markAsFailed($result['reason'] ?? 'Token creation failed');

            throw new \Exception($result['reason'] ?? __('payment.token_failed'));
        }

        // Update payment with token
        $payment->update([
            'paytr_token' => $result['token'],
            'status' => PaymentStatus::PROCESSING,
        ]);

        return [
            'payment' => $payment,
            'token' => $result['token'],
            'iframe_url' => 'https://www.paytr.com/odeme/guvenli/' . $result['token'],
        ];
    }

    /**
     * Handle PayTR callback.
     */
    public function handleCallback(array $data): bool
    {
        // Verify hash
        if (!$this->verifyHash($data)) {
            Log::warning('PayTR callback hash verification failed', $data);
            return false;
        }

        $merchantOid = $data['merchant_oid'];
        $status = $data['status'];
        $totalAmount = $data['total_amount'];

        $payment = Payment::where('merchant_oid', $merchantOid)->first();

        if (!$payment) {
            Log::error('PayTR callback: Payment not found', ['merchant_oid' => $merchantOid]);
            return false;
        }

        // Store callback response
        $payment->update([
            'paytr_response' => $data,
            'payment_method' => $data['payment_type'] ?? null,
        ]);

        if ($status === 'success') {
            return $this->handleSuccessfulPayment($payment);
        } else {
            $failReason = $data['failed_reason_msg'] ?? $data['failed_reason_code'] ?? 'Unknown error';
            $payment->markAsFailed($failReason);
            Log::info('PayTR payment failed', [
                'merchant_oid' => $merchantOid,
                'reason' => $failReason,
            ]);
            return true;
        }
    }

    /**
     * Handle successful payment.
     */
    protected function handleSuccessfulPayment(Payment $payment): bool
    {
        // Prevent double processing
        if ($payment->status === PaymentStatus::COMPLETED) {
            Log::info('PayTR payment already processed', [
                'merchant_oid' => $payment->merchant_oid,
            ]);
            return true;
        }

        $payment->markAsCompleted();

        // Add credits to user
        $creditManager = app(CreditManager::class);
        $creditManager->add(
            $payment->user,
            $payment->credits_purchased,
            'purchase',
            __('credits.purchased', ['package' => $payment->creditPackage?->name ?? 'Credits']),
            $payment
        );

        Log::info('PayTR payment completed', [
            'merchant_oid' => $payment->merchant_oid,
            'user_id' => $payment->user_id,
            'credits' => $payment->credits_purchased,
        ]);

        return true;
    }

    /**
     * Verify PayTR callback hash.
     */
    public function verifyHash(array $data): bool
    {
        $merchantOid = $data['merchant_oid'] ?? '';
        $status = $data['status'] ?? '';
        $totalAmount = $data['total_amount'] ?? '';
        $hash = $data['hash'] ?? '';

        $hashStr = $merchantOid . $this->merchantSalt . $status . $totalAmount;
        $expectedHash = base64_encode(hash_hmac('sha256', $hashStr, $this->merchantKey, true));

        return hash_equals($expectedHash, $hash);
    }

    /**
     * Get payment status.
     */
    public function getPaymentStatus(string $merchantOid): ?Payment
    {
        return Payment::where('merchant_oid', $merchantOid)->first();
    }
}
