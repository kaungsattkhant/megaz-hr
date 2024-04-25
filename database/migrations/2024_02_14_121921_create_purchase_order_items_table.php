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
            $table->double('original_quantity');
            $table->double('quantity');
            $table->double('amount');
            $table->foreignId('purchase_order_id')->constrained();
            $table->foreignId('item_id')->constrained();
            $table->boolean('is_manager_checked')->default(0);
            $table->boolean('is_financial_checked')->default(0);
            $table->boolean('is_md_checked')->default(0);
            $table->boolean('is_grn')->default(0);
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
