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
        Schema::table('objective_keys', function (Blueprint $table) {
            $table->dropColumn('role_id');
            $table->dropColumn('duration');
            $table->dropColumn('okr_point');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objective_keys', function (Blueprint $table) {
            //
        });
    }
};
