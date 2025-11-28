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
        Schema::table('objective_staff', function (Blueprint $table) {
            $table->unsignedInteger('rejected_by')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->string('reject_remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objective_staff', function (Blueprint $table) {
            //
        });
    }
};
