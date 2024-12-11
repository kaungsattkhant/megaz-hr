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
        Schema::create('mrp_raw_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mrp_forecast_id');
            $table->integer('item_id');
            $table->integer('uom_id');
            $table->integer('quantity');
            $table->double('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mrp_raw_materials');
    }
};
