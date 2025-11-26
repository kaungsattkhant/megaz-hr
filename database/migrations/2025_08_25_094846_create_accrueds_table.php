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
        Schema::create('accrueds', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_time');
            $table->enum('category', ['accrued', 'other_payable']);
            $table->enum('type', ['addition', 'settlement']);
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('main_account_id');
            $table->unsignedBigInteger('cash_account_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accrueds');
    }
};
