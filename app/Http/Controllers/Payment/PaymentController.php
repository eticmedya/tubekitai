<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\CreditPackage;
use App\Services\Payment\PayTRService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        protected PayTRService $paytr
    ) {}

    /**
     * Display credit packages.
     */
    public function packages(): View
    {
        $packages = CreditPackage::active()->ordered()->get();

        return view('payment.packages', compact('packages'));
    }

    /**
     * Initiate payment for a package.
     */
    public function checkout(Request $request, CreditPackage $package): View|JsonResponse
    {
        if (!$package->is_active) {
            return redirect()->route('credits.buy')
                ->with('error', __('payment.package_not_available'));
        }

        try {
            $result = $this->paytr->createPayment($request->user(), $package);

            return view('payment.checkout', [
                'package' => $package,
                'payment' => $result['payment'],
                'iframeUrl' => $result['iframe_url'],
            ]);
        } catch (\Exception $e) {
            return redirect()->route('credits.buy')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * PayTR callback handler.
     */
    public function callback(Request $request): string
    {
        $success = $this->paytr->handleCallback($request->all());

        // PayTR expects "OK" response
        return $success ? 'OK' : 'FAIL';
    }

    /**
     * Payment success page.
     */
    public function success(Request $request): View
    {
        return view('payment.success');
    }

    /**
     * Payment failure page.
     */
    public function fail(Request $request): View
    {
        return view('payment.fail');
    }

    /**
     * Payment history.
     */
    public function history(Request $request): View
    {
        $payments = $request->user()
            ->payments()
            ->with('creditPackage')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('payment.history', compact('payments'));
    }
}
