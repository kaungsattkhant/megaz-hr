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
        Schema::table('staff_equipment_assigns', function (Blueprint $table) {
            $table->dropColumn('staff_equipment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_equipment_assigns', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_equipment_id')->constrained('staff_equipments')->cascadeOnDelete();
        });
    }
};
