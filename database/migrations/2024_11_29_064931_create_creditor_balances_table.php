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
        Schema::create('creditor_balances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('amount');
            $table->dateTime('date_time');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('cash_account_id')->nullable();
            $table->char('type'); //settlement & addition 
            $table->unsignedInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creditor_balances');
    }
};
