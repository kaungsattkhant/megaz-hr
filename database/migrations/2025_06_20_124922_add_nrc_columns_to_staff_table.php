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
            $table->string('nrc_code')->nullable()->after('email');
            $table->string('nrc_township_code')->nullable()->after('nrc_code');
            $table->string('nrc_type')->nullable()->after('nrc_township_code');
            $table->string('bank_id')->nullable()->after('nrc_number');
        });
    }

    /**
     * nrc_code -> 9

     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            //
        });
    }
};
