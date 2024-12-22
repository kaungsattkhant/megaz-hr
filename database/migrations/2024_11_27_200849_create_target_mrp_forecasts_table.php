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
        Schema::create('target_mrp_forecasts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mrp_forecast_id');
            $table->integer('mrp_forecastable_id');
            $table->string('mrp_forecastable_type');
            $table->integer('quantity');
            $table->integer('amount');
            $table->integer('hour')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_mrp_forecasts');
    }
};
