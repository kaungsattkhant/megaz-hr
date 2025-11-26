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
            $table->string('alt_phone_number', 45)->nullable()->unique()->after('phone_number');
            $table->string('email')->nullable()->unique()->after('alt_phone_number');
            $table->date('joined_date')->nullable()->after('remember_token');
            $table->date('birthdate')->nullable()->after('joined_date');
            $table->string('father_name')->nullable()->after('birthdate');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->string('state')->nullable()->after('mother_name');
            $table->string('city')->nullable()->after('state');
            $table->string('zip_code')->nullable()->after('city');
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
