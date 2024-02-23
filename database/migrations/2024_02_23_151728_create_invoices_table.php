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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id');
            $table->dateTime('invoice_date');
            $table->dateTime('complete_date');
            $table->unsignedBigInteger('created_by');
            $table->integer('total');
            $table->integer('tax');
            $table->integer('sub_total');
            $table->integer('paid_amount');
            $table->integer('total_session_price');
            $table->integer('change');
            $table->unsignedBigInteger('area_id');
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('service_id');
            $table->integer('area_id');
            $table->string('payment_status');
            $table->string('payment_type');
            $table->integer('discount_value');
            $table->unsignedBigInteger('customer_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
