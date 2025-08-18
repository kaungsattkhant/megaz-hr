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
        Schema::create('cashbook_transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('date_time')->useCurrent();
            $table->double('amount')->default(0);
            $table->unsignedBigInteger('cash_account_id');
            $table->unsignedBigInteger('to_cash_account_id');
            $table->unsignedBigInteger('cashbook_balance_id');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashbook_transfers');
    }
};
