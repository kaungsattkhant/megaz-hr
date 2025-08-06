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
        Schema::table('objectives', function (Blueprint $table) {
            $table->double('okr_point')->after('is_active');
            $table->enum('type', ['daily', 'occasionally'])->after('okr_point');
            $table->integer('repetition')->nullable()->after('type');
            $table->foreignId('role_id')->after('okr_point');
            $table->foreignId('sop_id')->constrained('sops')
                ->onDelete('cascade')->after('role_id');
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
