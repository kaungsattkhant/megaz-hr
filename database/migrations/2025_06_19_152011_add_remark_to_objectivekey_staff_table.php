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
            $table->text('remark')->nullable()->after('manager_checked_by'); 
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
