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
        Schema::create('po_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('base_uom_id');
            $table->integer('base_uom_quantity');
            $table->foreignId('uom_id');
            $table->integer('uom_quantity');
            $table->foreignId('uom_conversion_unit_id');
            $table->double('quantity');
            $table->double('amount');
            $table->foreignId('item_id')->constrained();
            $table->foreignId('brand_id')->constrained();
            $table->foreignId('supplier_id')->constrained();
            $table->foreignId('purchase_order_id')->constrained();
            $table->foreignId('item_price_id');
            $table->boolean('is_arrival_completed')->default(true);
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_orders');
    }
};
