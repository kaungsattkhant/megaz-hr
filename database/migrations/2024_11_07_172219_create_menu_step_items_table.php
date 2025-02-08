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
        Schema::create('menu_step_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_step_id');
            $table->integer('item_id');
            $table->integer('uom_id');
            $table->integer('quantity');
            $table->double('weight');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_step_items');
    }
};
