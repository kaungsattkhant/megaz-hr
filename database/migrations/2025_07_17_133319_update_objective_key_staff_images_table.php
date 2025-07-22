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
        Schema::table('objective_key_staff_images', function (Blueprint $table) {
            $table->dropColumn('objectivekey_staff_id');
            $table->foreignId('objective_staff_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objective_key_staff_images', function (Blueprint $table) {
            //
        });
    }
};
