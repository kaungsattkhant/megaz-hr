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

            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->foreignId('entity_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('head_count_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('created_by');
            $table->dateTime('invoice_date');
            $table->dateTime('complete_date');

            $table->double('total');
            $table->double('tax');
            $table->double('sub_total');
            $table->double('paid_amount');
            $table->double('total_session_price');
            $table->double('change');
            $table->double('discount_value');

            $table->string('payment_status');
            $table->string('payment_type');

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
