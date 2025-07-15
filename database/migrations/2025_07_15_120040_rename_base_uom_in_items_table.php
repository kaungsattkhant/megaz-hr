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
            $table->renameColumn('min_holding_base_uom_quantity', 'min_holding_quantity');
            $table->unsignedInteger('min_uom_id')->nullable()->after('min_holding_quantity');
            $table->renameColumn('max_limit_base_uom_quantity', 'max_limit_quantity');
            $table->unsignedInteger('max_uom_id')->nullable()->after('max_limit_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
};
