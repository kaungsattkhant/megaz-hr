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
            $table->decimal('year');
            $table->decimal('month');
            $table->decimal('opening_balance');
            $table->decimal('closing_balance');
            $table->decimal('prepaid_amount');
            $table->decimal('monthly_cost');
            $table->decimal('cost');
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
