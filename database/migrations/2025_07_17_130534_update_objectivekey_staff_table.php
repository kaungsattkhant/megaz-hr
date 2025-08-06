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
        Schema::table('objectivekey_staff', function (Blueprint $table) {
            $table->dropColumn('objective_key_id');
            $table->dropColumn('objective_key_duty_id');
            $table->foreignId('objective_id')->after('staff_id');
            $table->dateTime('start_date')->after('objective_id');
            $table->dateTime('end_date')->after('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objectivekey_staff', function (Blueprint $table) {
            //
        });
    }
};
