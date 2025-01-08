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
        Schema::create('customer_deposits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('date_time');
            $table->unsignedBigInteger('customer_id');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('cash_account_id')->nullable();
            $table->double('amount');
            $table->string('type'); //deposit || withdrawl
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_deposits');
    }
};
