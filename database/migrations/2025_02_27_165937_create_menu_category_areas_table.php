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
        Schema::create('menu_category_areas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_category_id');
            $table->unsignedBigInteger('selling_area_id');
            $table->unsignedBigInteger('cooking_area_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_category_areas');
    }
};
