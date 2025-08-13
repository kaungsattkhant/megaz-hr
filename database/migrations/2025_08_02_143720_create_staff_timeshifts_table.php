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
        Schema::create('staff_timeshifts', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_time');
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->foreignId('timeshift_id')->constrained('time_shifts')->onDelete('cascade');
            $table->foreignId('area_id')->nullable()->constrained('areas')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_timeshifts');
    }
};
