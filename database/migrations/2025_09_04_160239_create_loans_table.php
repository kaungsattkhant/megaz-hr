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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_time');
            $table->enum('type', ['addition','interest addition','settlement','interest settlement']);
            $table->enum('category', ['loan', 'interest'])->nullable();
            $table->unsignedBigInteger('account_id')->constrained('accounts');
            $table->unsignedBigInteger('main_account_id')->constrained('accounts');//parent loan account
            $table->unsignedBigInteger('cash_account_id')->nullable()->constrained('accounts');
            $table->decimal('amount', 15, 2);
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
