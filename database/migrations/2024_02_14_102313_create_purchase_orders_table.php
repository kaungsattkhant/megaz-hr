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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_id',45);
            $table->double('total_price');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('kitchen_check_id')->nullable();
            $table->unsignedBigInteger('financial_check_id')->nullable();
            $table->dateTime('kitchen_check_time')->nullable();
            $table->dateTime('financial_check_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
