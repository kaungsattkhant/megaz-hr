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
        Schema::create('daily_area_sales_volume_by_staff', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->string('month_name');
            $table->integer('month_number');
            $table->date('work_date');
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->double('total_amount');
            $table->integer('total_pax');
            $table->double('per_pax');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_area_sales_volume_by_staff');
    }
};
