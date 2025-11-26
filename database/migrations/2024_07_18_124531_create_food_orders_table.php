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
        Schema::create('food_orders', function (Blueprint $table) {
            $table->id();
            $table->dateTime("date_time");
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_address_id')->constrained()->onDelete('cascade');
            $table->double('delivery_charge');
            $table->double('sub_total');
            $table->double('total_discount_price');
            $table->double('total_price');
            $table->string('status')->default('pending');
            $table->dateTime('confirmed_at')->nullable();
            $table->unsignedBigInteger('confirmed_by')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->dateTime('kitchen_confirmed_at')->nullable();
            $table->dateTime('done_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_orders');
    }
};
