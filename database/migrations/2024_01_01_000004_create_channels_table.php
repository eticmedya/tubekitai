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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('youtube_id')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('subscriber_count')->default(0);
            $table->unsignedBigInteger('video_count')->default(0);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->string('thumbnail_url')->nullable();
            $table->string('custom_url')->nullable();
            $table->string('country', 2)->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('analyzed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'analyzed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
