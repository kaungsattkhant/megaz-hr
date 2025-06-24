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
        Schema::table('leave_allowances', function (Blueprint $table) {
            if (Schema::hasColumn('leave_allowances', 'role_id')) {
                $table->dropColumn('role_id');
            }
            $table->string('allowanceable_type')->nullable()->after('id');
            $table->unsignedBigInteger('allowanceable_id')->nullable()->after('allowanceable_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_allowances', function (Blueprint $table) {
            //
        });
    }
};
