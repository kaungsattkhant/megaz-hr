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
        Schema::table('staff', function (Blueprint $table) {
            //
            $table->string('nrc_front_url')->after('address')->nullable();
            $table->string('nrc_front_path')->after('nrc_front_url')->nullable();
            $table->string('nrc_back_url')->after('nrc_front_path')->nullable();
            $table->string('nrc_back_path')->after('nrc_back_url')->nullable();
            $table->string('household_registration_url')->after('nrc_back_path')->nullable();
            $table->string('household_registration_path')->after('household_registration_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            //
        });
    }
};
