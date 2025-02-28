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
        Schema::create('po_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 255);
            $table->date('date_time');
            $table->integer('cash_account_id')->nullable();
            $table->double('discount_value')->nullable();
            $table->double('sub_total')->nullable();
            $table->double('paid_amount')->nullable();
            $table->double('total_invoice_amount');
            $table->integer('created_by');
            $table->boolean('is_complete')->default(0);
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_invoices');
    }
};
