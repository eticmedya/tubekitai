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
        Schema::create('niche_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('interests')->nullable();
            $table->json('lifestyle')->nullable();
            $table->json('skills')->nullable();
            $table->string('time_availability')->nullable(); // part-time, full-time, etc.
            $table->string('content_language', 10)->default('tr');
            $table->string('target_audience')->nullable();
            $table->json('recommendations')->nullable();
            $table->json('competition_data')->nullable();
            $table->json('monetization_potential')->nullable();
            $table->json('suggested_niches')->nullable();
            $table->json('content_ideas')->nullable();
            $table->text('ai_summary')->nullable();
            $table->decimal('credits_used', 8, 2)->default(0);
            $table->string('model_used')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niche_analyses');
    }
};
