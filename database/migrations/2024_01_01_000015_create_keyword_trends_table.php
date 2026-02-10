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
        Schema::create('keyword_trends', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->string('region', 2)->default('TR');
            $table->string('language', 5)->default('tr');
            $table->unsignedBigInteger('search_volume')->default(0);
            $table->string('competition_level')->nullable(); // low, medium, high
            $table->decimal('competition_score', 5, 2)->default(0); // 0-100
            $table->decimal('trend_score', 5, 2)->default(0); // -100 to 100 (growth)
            $table->json('related_keywords')->nullable();
            $table->json('search_trend')->nullable(); // Historical data
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();

            $table->unique(['keyword', 'region', 'language']);
            $table->index(['region', 'trend_score']);
            $table->index(['last_updated']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keyword_trends');
    }
};
