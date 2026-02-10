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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained()->cascadeOnDelete();
            $table->string('youtube_id')->unique();
            $table->string('author');
            $table->string('author_channel_id')->nullable();
            $table->text('text');
            $table->unsignedInteger('like_count')->default(0);
            $table->unsignedInteger('reply_count')->default(0);
            $table->string('category')->nullable(); // positive, negative, suggestion, etc.
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['video_id', 'category']);
            $table->index(['video_id', 'like_count']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
