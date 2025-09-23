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
        Schema::create('staff_equipment_handovers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_staff_id')->constrained('staff')->cascadeOnDelete();
            $table->foreignId('to_staff_id')->constrained('staff')->cascadeOnDelete();
            $table->foreignId('staff_timeshift_id')->constrained('staff_timeshifts')->cascadeOnDelete();
            $table->datetime('handover_date');
            $table->string('handover_note')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->datetime('confirmed_at')->nullable();
            $table->unsignedBigInteger('confirmed_by')->nullable();
            $table->datetime('cancelled_at')->nullable();
            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_equipment_handovers');
    }
};
