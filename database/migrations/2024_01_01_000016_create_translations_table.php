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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('video_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type'); // title, description, subtitle, tags
            $table->text('source_text');
            $table->string('source_lang', 10);
            $table->string('target_lang', 10);
            $table->text('translated_text')->nullable();
            $table->json('seo_suggestions')->nullable();
            $table->decimal('credits_used', 8, 2)->default(0);
            $table->string('model_used')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index(['video_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
