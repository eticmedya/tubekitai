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
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2); // Can be negative (deduction) or positive (purchase/refund)
            $table->string('operation_type'); // purchase, usage, refund, bonus, etc.
            $table->string('description')->nullable();
            $table->decimal('balance_after', 10, 2);
            $table->morphs('creditable'); // polymorphic relation to payment, ai_generation, etc.
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['operation_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
