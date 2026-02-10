<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('credit_package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('merchant_oid')->unique(); // Unique order ID for PayTR
            $table->unsignedInteger('amount'); // In kuruş
            $table->string('currency', 3)->default('TRY');
            $table->decimal('credits_purchased', 10, 2)->default(0);
            $table->string('status')->default('pending'); // pending, processing, completed, failed, refunded
            $table->string('paytr_token')->nullable();
            $table->json('paytr_response')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('card_last_four', 4)->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['merchant_oid']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
