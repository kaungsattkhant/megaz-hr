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
            $table->unsignedBigInteger('equipment_typeable_id')->after('uom_type');
            $table->string('equipment_typeable_type')->after('equipment_typeable_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_equipment_assigns', function (Blueprint $table) {
            //
        });
    }
};
