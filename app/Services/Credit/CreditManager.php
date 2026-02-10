<?php

namespace App\Services\Credit;

use App\Enums\OperationType;
use App\Models\Credit;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreditManager
{
    /**
     * Check if user has enough credits for operation.
     */
    public function hasEnough(User $user, string $operation): bool
    {
        $cost = $this->getCost($operation);
        return $user->credits >= $cost;
    }

    /**
     * Get cost for an operation.
     */
    public function getCost(string $operation): float
    {
        return config("credits.costs.{$operation}", 1);
    }

    /**
     * Deduct credits from user.
     */
    public function deduct(
        User $user,
        string $operation,
        ?string $description = null,
        ?Model $creditable = null
    ): bool {
        $cost = $this->getCost($operation);

        if (!$this->hasEnough($user, $operation)) {
            return false;
        }

        return DB::transaction(function () use ($user, $cost, $operation, $description, $creditable) {
            // Lock user row
            $user = User::lockForUpdate()->find($user->id);

            // Double check credits
            if ($user->credits < $cost) {
                return false;
            }

            // Deduct from user
            $user->decrement('credits', $cost);

            // Create transaction record
            Credit::create([
                'user_id' => $user->id,
                'amount' => -$cost,
                'operation_type' => 'usage',
                'description' => $description ?? $this->getOperationLabel($operation),
                'balance_after' => $user->credits,
                'creditable_type' => $creditable?->getMorphClass(),
                'creditable_id' => $creditable?->getKey(),
            ]);

            Log::info('Credits deducted', [
                'user_id' => $user->id,
                'operation' => $operation,
                'amount' => $cost,
                'balance_after' => $user->credits,
            ]);

            return true;
        });
    }

    /**
     * Add credits to user.
     */
    public function add(
        User $user,
        float $amount,
        string $operationType = 'purchase',
        ?string $description = null,
        ?Model $creditable = null
    ): void {
        DB::transaction(function () use ($user, $amount, $operationType, $description, $creditable) {
            // Lock user row
            $user = User::lockForUpdate()->find($user->id);

            // Add credits
            $user->increment('credits', $amount);

            // Create transaction record
            Credit::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'operation_type' => $operationType,
                'description' => $description ?? __("credits.operation.{$operationType}"),
                'balance_after' => $user->credits,
                'creditable_type' => $creditable?->getMorphClass(),
                'creditable_id' => $creditable?->getKey(),
            ]);

            Log::info('Credits added', [
                'user_id' => $user->id,
                'operation_type' => $operationType,
                'amount' => $amount,
                'balance_after' => $user->credits,
            ]);
        });
    }

    /**
     * Refund credits to user.
     */
    public function refund(User $user, float $amount, string $reason, ?Model $creditable = null): bool
    {
        $this->add($user, $amount, 'refund', $reason, $creditable);
        return true;
    }

    /**
     * Get user's credit balance.
     */
    public function getBalance(User $user): float
    {
        return $user->credits;
    }

    /**
     * Get user's usage history.
     */
    public function getUsageHistory(User $user, int $days = 30): Collection
    {
        return Credit::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays($days))
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get usage statistics for user.
     */
    public function getUsageStats(User $user, int $days = 30): array
    {
        $transactions = $this->getUsageHistory($user, $days);

        $totalUsed = $transactions->where('amount', '<', 0)->sum('amount') * -1;
        $totalPurchased = $transactions->where('operation_type', 'purchase')->sum('amount');
        $totalRefunded = $transactions->where('operation_type', 'refund')->sum('amount');

        $usageByOperation = $transactions
            ->where('amount', '<', 0)
            ->groupBy('description')
            ->map(fn($items) => $items->sum('amount') * -1);

        return [
            'total_used' => $totalUsed,
            'total_purchased' => $totalPurchased,
            'total_refunded' => $totalRefunded,
            'current_balance' => $user->credits,
            'usage_by_operation' => $usageByOperation,
            'transaction_count' => $transactions->count(),
        ];
    }

    /**
     * Get operation label.
     */
    protected function getOperationLabel(string $operation): string
    {
        $operationType = OperationType::tryFrom($operation);

        if ($operationType) {
            return $operationType->getLabel();
        }

        return __("credits.operation.{$operation}") ?? $operation;
    }

    /**
     * Give initial credits to new user.
     */
    public function giveInitialCredits(User $user): void
    {
        $initialCredits = config('credits.initial_credits', 5);

        $this->add(
            $user,
            $initialCredits,
            'initial',
            __('credits.initial_welcome')
        );
    }

    /**
     * Check if operation would exhaust all credits.
     */
    public function wouldExhaust(User $user, string $operation): bool
    {
        $cost = $this->getCost($operation);
        return $user->credits <= $cost && $user->credits > 0;
    }

    /**
     * Get remaining operations count.
     */
    public function getRemainingOperations(User $user, string $operation): int
    {
        $cost = $this->getCost($operation);

        if ($cost <= 0) {
            return PHP_INT_MAX;
        }

        return (int) floor($user->credits / $cost);
    }
}
