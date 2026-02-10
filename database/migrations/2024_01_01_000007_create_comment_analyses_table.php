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
        Schema::create('comment_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('total_comments')->default(0);
            $table->unsignedInteger('positive_count')->default(0);
            $table->unsignedInteger('negative_count')->default(0);
            $table->unsignedInteger('supportive_count')->default(0);
            $table->unsignedInteger('criticism_count')->default(0);
            $table->unsignedInteger('suggestion_count')->default(0);
            $table->unsignedInteger('question_count')->default(0);
            $table->unsignedInteger('toxic_count')->default(0);
            $table->json('top_suggestions')->nullable();
            $table->json('top_criticisms')->nullable();
            $table->json('common_topics')->nullable();
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
        Schema::dropIfExists('comment_analyses');
    }
};
