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
            $table->dateTime('invoice_date');
            $table->dateTime('complete_date')->nullable();
            $table->string('invoice_type')->nullable();
            $table->string('discount_type')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->double('total')->nullable();
            $table->double('tax')->nullable();
            $table->double('sub_total')->default(0);
            $table->double('paid_amount')->default(0); //new
            $table->double('total_session_price')->default(0);
            $table->double('change')->default(0);//new
            $table->foreignId('area_id')->onDelete('cascade');
            $table->double('service_charge')->default(0);
            $table->unsignedBigInteger('head_count_id')->nullable();
            $table->unsignedInteger('entity_id')->nullable(); //readd for requirement
            $table->string('payment_status')->nullable();
            $table->string('payment_type')->nullable();
            $table->double('discount_value')->default(0);
            $table->double('order_discount_value')->default(0);
            $table->double('room_discount_value')->default(0);
            $table->double('total_service_value')->default(0);
            $table->double('birthday_discount')->default(0);
            $table->double('customer_level_discount')->default(0);
            $table->double('total_discount')->default(0);
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->nullable()->constrained()->onDelete('cascade');//new
            $table->foreignId('room_discount_id')->nullable()->constrained()->onDelete('cascade')->nullable();//new
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
