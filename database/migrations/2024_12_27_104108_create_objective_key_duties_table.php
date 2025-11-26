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
        // Schema::create('objective_key_duties', function (Blueprint $table) {
        // $table->id();
        // $table->date('assign_date');
        // $table->unsignedBigInteger('created_by');
        // $table->boolean('is_active')->default(1);
        // $table->date('due_date');
        // $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objective_key_duties');
    }
};
