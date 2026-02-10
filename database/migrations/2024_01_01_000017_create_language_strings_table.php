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
        Schema::create('language_strings', function (Blueprint $table) {
            $table->id();
            $table->string('group'); // app, auth, dashboard, modules, etc.
            $table->string('key');
            $table->string('locale', 5);
            $table->text('value');
            $table->timestamps();

            $table->unique(['group', 'key', 'locale']);
            $table->index(['locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_strings');
    }
};
