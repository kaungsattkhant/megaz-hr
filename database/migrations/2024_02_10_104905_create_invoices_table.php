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
            $table->dateTime('invoice_date');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->double('head_count');
            $table->unsignedBigInteger('create_by');
            $table->double('total');
            $table->double('tax');
            $table->double('sub_total');
            $table->double('paid_amount');
            $table->double('change');
            $table->unsignedBigInteger('area_id');
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('service_id');
            $table->string('payment_status',45);
            $table->string('payment_type',45);
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
