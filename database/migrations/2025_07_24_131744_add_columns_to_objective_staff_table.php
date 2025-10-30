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
            $table->unsignedBigInteger('objective_assign_id')->constrained('objective_assigns')
            ->cascadeOnDelete();
            $table->integer('repetition_count')->default(0);
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
