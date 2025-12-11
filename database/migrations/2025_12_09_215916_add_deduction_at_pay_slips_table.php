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
        Schema::table('pay_slips', function (Blueprint $table) {
            //
            $table->double('deduction')->default(0);
            $table->double('total_deduction')->default(0);
            $table->boolean('is_confirm')->default(false);
            $table->dateTime('confirmed_at')->nullable();
            $table->dateTime('confirmed_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pay_slips', function (Blueprint $table) {
            //
        });
    }
};
