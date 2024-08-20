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
        Schema::create('prepaid_payments', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_time');
            $table->double('amount');
            $table->unsignedBigInteger('cash_account_id');
            $table->foreignId('prepaid_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prepaid_payments');
    }
};
