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
        Schema::create('target_actual_monthly_menu_sales', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->string('month_name');
            $table->integer('month_number');
            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->integer('target_quantity');
            $table->double('target_sales_amount');
            $table->integer('actual_quantity');
            $table->double('actual_sales_amount');
            $table->double('achieved_percentage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_actual_monthly_menu_sales');
    }
};
