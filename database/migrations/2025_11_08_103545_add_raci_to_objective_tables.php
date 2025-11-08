<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('objectives', function (Blueprint $table) {
            //
            $table->unsignedInteger('accountable_id')->nullable();
            $table->unsignedInteger('consulted_id')->nullable();
            $table->unsignedInteger('informed_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objectives', function (Blueprint $table) {
            //
        });
    }
};
