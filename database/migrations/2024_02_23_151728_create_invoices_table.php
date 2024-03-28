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
            $table->string('invoice_id')->unique()->nullable();

            $table->foreignId('area_id')->onDelete('cascade');
            $table->foreignId('entity_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('head_count_id')->nullable();

            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->dateTime('invoice_date');
            $table->dateTime('complete_date')->nullable();

            $table->double('total')->nullable();
            $table->double('tax')->nullable();
            $table->double('sub_total')->default(0);
            $table->double('total_session_price')->default(0);
            $table->double('discount_value')->default(0);

            $table->string('payment_status')->nullable();
            $table->string('payment_type')->nullable();

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
