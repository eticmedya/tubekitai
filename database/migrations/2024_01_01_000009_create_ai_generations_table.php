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
        Schema::create('ai_generations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // title, description, idea, cover, etc.
            $table->text('prompt');
            $table->json('context')->nullable(); // Additional context data
            $table->longText('result')->nullable();
            $table->json('result_meta')->nullable(); // For structured results
            $table->string('model_used');
            $table->unsignedInteger('input_tokens')->default(0);
            $table->unsignedInteger('output_tokens')->default(0);
            $table->decimal('credits_used', 8, 2)->default(0);
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_generations');
    }
};
