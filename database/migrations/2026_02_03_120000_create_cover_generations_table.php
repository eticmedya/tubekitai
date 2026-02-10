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
        Schema::create('cover_generations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->string('original_url')->nullable();
            $table->text('prompt');
            $table->string('aspect_ratio')->default('16:9');
            $table->boolean('has_reference')->default(false);
            $table->string('reference_path')->nullable();
            $table->string('model_used')->default('fal-ai/nano-banana-pro');
            $table->decimal('credits_used', 8, 2)->default(1);
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cover_generations');
    }
};
