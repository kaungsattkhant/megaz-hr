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
        Schema::create('prepaid_balances', function (Blueprint $table) {
            $table->id();
            $table->double('year');
            $table->double('month');
            $table->double('opening_balance');
            $table->double('closing_balance');
            $table->double('prepaid_amount');
            $table->double('monthly_cost');
            $table->double('cost');
            $table->foreignId('prepaid_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prepaid_balances');
    }
};
