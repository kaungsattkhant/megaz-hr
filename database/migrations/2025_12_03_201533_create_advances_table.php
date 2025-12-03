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
        Schema::create('advances', function (Blueprint $table) {
            $table->id();
            $table->string('advance_ref_no')->unique();
            $table->dateTime('date_time');
            $table->unsignedBigInteger('staff_id');
            $table->double('advance_amount');
            $table->integer('total_months');          
            $table->double('remaining_amount'); 
            $table->integer('remaining_months');     
            $table->double('current_deduction_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advances');
    }
};
