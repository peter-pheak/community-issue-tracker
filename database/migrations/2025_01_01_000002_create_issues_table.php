<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Latitude: DECIMAL(10,8), Longitude: DECIMAL(11,8). Don't use FLOAT. */
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
