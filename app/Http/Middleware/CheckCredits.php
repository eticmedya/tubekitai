<?php

namespace App\Http\Middleware;

use App\Enums\OperationType;
use App\Services\Credit\CreditManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCredits
{
    public function __construct(
        protected CreditManager $creditManager
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ?string $operation = null): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // If operation is specified, check if user has enough credits
        if ($operation) {
            $operationType = OperationType::tryFrom($operation);

            if ($operationType && !$this->creditManager->hasEnough(auth()->user(), $operationType->value)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => __('credits.insufficient'),
                        'required' => $operationType->getCost(),
                        'available' => auth()->user()->credits,
                    ], 402);
                }

                return redirect()
                    ->route('credits.buy')
                    ->with('error', __('credits.insufficient'));
            }
        }

        return $next($request);
    }
}
