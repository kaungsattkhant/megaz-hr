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
        Schema::table('items', function (Blueprint $table) {
            $table->double('conversion')->default(0);
            $table->enum('limitation_type', ['uom', 'finance'])->after('minimum_holding_amount');
            $table->unsignedInteger('amount')->nullable()->after('limitation_type');
            $table->unsignedInteger('max_limit_base_uom_quantity')->nullable()->after('amount');
            $table->unsignedInteger('max_limit_uom_quantity')->nullable()->after('max_limit_base_uom_quantity');
        });
    }

    /**
     * Reverse the migrations 
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
};
