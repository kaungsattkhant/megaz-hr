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
        Schema::create('kitchen_monthly_sales', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->string('month_name');
            $table->integer('month_number');
            $table->decimal('total_kitchen_sale', 10, 2)->default(0);
            $table->string('area_type');
            $table->string('cooking_area_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kitchen_monthly_sales');
    }
};
