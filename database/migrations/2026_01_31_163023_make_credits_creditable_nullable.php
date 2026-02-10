<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table
        if (DB::getDriverName() === 'sqlite') {
            // Create temporary table
            Schema::create('credits_temp', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->decimal('amount', 10, 2);
                $table->string('operation_type');
                $table->string('description')->nullable();
                $table->decimal('balance_after', 10, 2);
                $table->nullableMorphs('creditable');
                $table->timestamps();

                $table->index(['user_id', 'created_at']);
                $table->index(['operation_type']);
            });

            // Copy data
            DB::statement('INSERT INTO credits_temp SELECT * FROM credits');

            // Drop old table
            Schema::dropIfExists('credits');

            // Rename temp table
            Schema::rename('credits_temp', 'credits');
        } else {
            Schema::table('credits', function (Blueprint $table) {
                $table->string('creditable_type')->nullable()->change();
                $table->unsignedBigInteger('creditable_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For SQLite, we'd need to recreate again with NOT NULL
        // For simplicity, we'll leave it nullable
    }
};
