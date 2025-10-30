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
        Schema::create('monthly_kitchen_menus', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->string('month_name');
            $table->string('month_number');
            $table->string('menu_name');
            $table->string('area_type');
            $table->string('cooking_area_type');
            $table->decimal('total_menu_sale', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_kitchen_menus');
    }
};
