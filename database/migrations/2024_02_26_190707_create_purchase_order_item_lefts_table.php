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
        Schema::create('purchase_order_item_lefts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id');
            $table->integer('quantity');
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity_by_manager')->nullable();
            $table->integer('quantity_by_financial')->nullable();
            $table->integer('quantity_by_md')->nullable();
            $table->integer('quantity_after_md')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_item_lefts');
    }
};
