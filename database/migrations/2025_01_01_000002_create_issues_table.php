<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * IMPORTANT: latitude is DECIMAL(10,8) and longitude is DECIMAL(11,8).
     * Never use FLOAT — it silently loses precision and causes map marker drift.
     *
     * After running this migration, run: php artisan storage:link
     */
    public function up(): void
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description');
            $table->enum('category', ['Road', 'Lighting', 'Waste', 'Other']);
            $table->enum('status', ['Open', 'In Progress', 'Resolved'])->default('Open');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('image', 255)->nullable(); // storage-relative path
            $table->string('reported_by', 100)->nullable();
            $table->timestamps();

            // Composite index on primary filter columns
            $table->index(['status', 'category']);
            $table->index('category');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
