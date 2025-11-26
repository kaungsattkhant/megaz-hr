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
        Schema::create('canteen_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('canteen_id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('uom_id');
            $table->unsignedInteger('uom_conversion_id');
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
        Schema::dropIfExists('canteen_items');
    }
};
