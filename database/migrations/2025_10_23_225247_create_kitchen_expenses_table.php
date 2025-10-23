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
        Schema::create('kitchen_expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->string('month_name');
            $table->integer('month_number');
            $table->decimal('kitchen_total_expense', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kitchen_expenses');
    }
};
