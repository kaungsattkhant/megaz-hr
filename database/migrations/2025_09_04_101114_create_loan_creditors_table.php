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
        Schema::create('loan_creditors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_account_id')->constrained('accounts');
            $table->foreignId('interest_on_loan_account_id')->constrained('accounts');
            $table->foreignId('loan_creditor_account_id')->constrained('accounts');
            $table->foreignId('interest_on_loan_creditor_account_id')->constrained('accounts');
            $table->string('name');
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_creditors');
    }
};
