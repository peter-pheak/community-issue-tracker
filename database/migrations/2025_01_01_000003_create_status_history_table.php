<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')->constrained('issues')->cascadeOnDelete();
            $table->string('status', 50);
            $table->string('changed_by', 100); // admin username at time of change
            $table->timestamp('changed_at')->useCurrent();

            $table->index('issue_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_history');
    }
};
