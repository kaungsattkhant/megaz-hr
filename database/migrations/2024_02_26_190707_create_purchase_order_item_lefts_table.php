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
            $table->boolean('is_manager_checked');
            $table->boolean('is_financial_checked');
            $table->boolean('is_md_checked');
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
