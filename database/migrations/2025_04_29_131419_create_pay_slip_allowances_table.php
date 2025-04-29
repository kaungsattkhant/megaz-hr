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
        Schema::create('pay_slip_allowances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pay_slip_id')->constrained('pay_slips');
            $table->foreignId('allowance_id')->constrained('allowances');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_slip_allowances');
    }
};
