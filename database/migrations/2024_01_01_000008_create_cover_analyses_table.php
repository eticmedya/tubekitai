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
        Schema::create('cover_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->string('original_filename')->nullable();
            $table->unsignedInteger('quality_score')->default(0); // 0-100
            $table->unsignedInteger('readability_score')->default(0); // 0-100
            $table->unsignedInteger('face_visibility_score')->default(0); // 0-100
            $table->unsignedInteger('contrast_score')->default(0); // 0-100
            $table->unsignedInteger('emotion_score')->default(0); // 0-100
            $table->unsignedInteger('composition_score')->default(0); // 0-100
            $table->unsignedInteger('overall_score')->default(0); // 0-100
            $table->unsignedInteger('ctr_prediction')->default(0); // 0-100
            $table->text('ai_feedback')->nullable();
            $table->json('improvement_suggestions')->nullable();
            $table->json('detected_elements')->nullable(); // faces, text, objects
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
        Schema::dropIfExists('cover_analyses');
    }
};
