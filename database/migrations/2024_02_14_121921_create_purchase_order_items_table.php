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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('item_id')->constrained();
            $table->integer('base_uom_id')->constrained();
            $table->integer('base_uom_quantity');
            $table->foreignId('uom_id')->constrained();
            $table->integer('uom_quantity');
            $table->foreignId('uom_conversion_id')->constrained();
            $table->double('quantity');
            $table->foreignId('purchase_order_id')->constrained();
            $table->double('original_quantity');
            $table->double('amount');
            $table->boolean('is_manager_checked')->default(0);
            $table->boolean('is_financial_checked')->default(0);
            $table->boolean('is_md_checked')->default(0);
            $table->boolean('is_procurement_manager_checked')->default(0);
            $table->boolean('is_confirmed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
