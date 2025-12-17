<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            //
            DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN type ENUM('ktv', 'restaurant', 'event', 'bar') AFTER status");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            //
            DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN type ENUM('ktv', 'restaurant', 'event') AFTER status");

        });
    }
};
