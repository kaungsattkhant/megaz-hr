<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE day_in_off_days
            MODIFY day ENUM(
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                'Sunday',
                'Sabbath-day',
                'Public-holiday',
                'Custom'
            )
        ");

        // Add staff_id column
        Schema::table('day_in_off_days', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE day_in_off_days
            MODIFY day ENUM(
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                'Sunday',
                'Sabbath-day',
                'Public-holiday'
            )
        ");

        // Drop staff_id column
        Schema::table('day_in_off_days', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
            $table->dropColumn('staff_id');
        });
    }
};
