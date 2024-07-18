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
        Schema::create('food_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->foreignId('area_id')->nullable()->constrained()->onDelete('cascade');
            $table->double('quantity');
            $table->double('discount_price');
            $table->double('original_price');
            $table->foreignId('menu_service_discount_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('status')->default('received');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_order_items');
    }
};
