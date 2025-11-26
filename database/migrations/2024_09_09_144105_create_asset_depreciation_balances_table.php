<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asset_depreciation_balances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('month');
            $table->double('year');
            $table->date('date');
            $table->unsignedInteger('asset_id');
            $table->double('original_cost')->default(0);
            $table->double('addition_year_cost')->default(0);
            $table->double('total_cost')->default(0);
            $table->double('current_month_depreciation')->default(0);
            $table->double('addition_year_depreciation')->default(0);
            $table->double('total_depreciation')->default(0);
            $table->double('book_value')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_depreciation_balances');
    }
};
