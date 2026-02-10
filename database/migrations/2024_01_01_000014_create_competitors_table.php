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
        Schema::create('competitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('channel_id')->constrained()->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->text('strategy_notes')->nullable();
            $table->json('content_patterns')->nullable();
            $table->json('upload_schedule')->nullable();
            $table->json('title_formulas')->nullable();
            $table->json('cover_styles')->nullable();
            $table->json('top_videos')->nullable();
            $table->text('ai_analysis')->nullable();
            $table->decimal('credits_used', 8, 2)->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'channel_id']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitors');
    }
};
