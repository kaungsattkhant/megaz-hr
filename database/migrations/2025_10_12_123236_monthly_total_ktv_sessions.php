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
        //The table structure is designed to store one record per month per year for material view for total ktv sessions
        Schema::create('monthly_total_ktv_sessions', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->string('month_name');
            $table->integer('month_number');
            $table->decimal('total_ktv_sessions', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
