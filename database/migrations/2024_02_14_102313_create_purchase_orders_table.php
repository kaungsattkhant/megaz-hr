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
            $table->bigIncrements('id');
            $table->string('po_id', 45);
            $table->decimal('total_price', 8, 2);
            $table->date('date');
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_bought')->default(0);
            $table->unsignedBigInteger('manager_check_id')->nullable();
            $table->unsignedBigInteger('financial_check_id')->nullable();
            $table->dateTime('manager_check_time')->nullable();
            $table->dateTime('financial_check_time')->nullable();
            $table->dateTime('md_check_time')->nullable();
            $table->boolean('is_md_checked')->default(0);
            $table->boolean(column: 'purchased_date_time')->nullable();
            $table->unsignedBigInteger('procurement_manager_check_id')->nullable();
            $table->dateTime('procurement_manager_check_time')->nullable();
            $table->enum('status', ['created', 'manager_checked', 'financial_checked', 'md_checked', 'procurement_manager_checked'])->default('created');
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
