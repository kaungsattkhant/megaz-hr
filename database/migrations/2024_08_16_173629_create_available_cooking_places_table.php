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
        Schema::create('available_cooking_places', function (Blueprint $table) {
            $table->id();
            $table->string('cooking_placeable_type');
            $table->unsignedBigInteger('cooking_placeable_id');
            $table->foreignId('cooking_place_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('available_cooking_places');
    }
};
