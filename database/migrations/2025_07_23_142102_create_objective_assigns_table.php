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
        Schema::create('objective_assigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('objective_id')->constrained('objectives')->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objective_assigns');
    }
};
