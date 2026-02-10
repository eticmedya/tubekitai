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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->constrained()->cascadeOnDelete();
            $table->string('youtube_id')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('like_count')->default(0);
            $table->unsignedBigInteger('comment_count')->default(0);
            $table->string('thumbnail_url')->nullable();
            $table->string('duration')->nullable(); // ISO 8601 duration
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->json('tags')->nullable();
            $table->string('category_id')->nullable();
            $table->string('default_language', 10)->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['channel_id', 'published_at']);
            $table->index(['view_count']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
