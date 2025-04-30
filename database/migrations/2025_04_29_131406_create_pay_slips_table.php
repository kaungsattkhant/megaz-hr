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
        Schema::create('pay_slips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff');
            $table->foreignId('salary_batch_id')->constrained('salary_batches');
            $table->foreignId('salary_id')->constrained('salaries');
            $table->double('basic_salary');
            $table->double('allowance');
            $table->double('added_allowance')->nullable();
            $table->double('added_deduction')->nullable();
            $table->double('total_allowance');
            $table->double('overtime')->nullable();
            $table->double('net_salary');
            $table->unsignedInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_slips');
    }
};
