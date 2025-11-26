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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->text('description')->nullable();
            $table->unsignedInteger('transactionable_id')->nullable();
            $table->char('transactionable_type')->nullable();
            $table->boolean('is_confirmed')->default(0);
            $table->boolean('is_closing')->default(0);
            $table->boolean('closing_date')->nullable();
            $table->boolean('created_by');
            $table->unsignedInteger('inventory_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
