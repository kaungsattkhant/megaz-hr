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
        Schema::create('objectivekey_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id');
            $table->foreignId('objective_key_id');
            $table->string('status')->default('assigned');
            $table->dateTime('in_progressed_at')->nullable();
            $table->integer('in_progressed_by')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->integer('completed_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->integer('approved_by')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->integer('cancelled_by')->nullable();
            $table->double('okr_point');
            $table->dateTime('manager_checked_at')->nullable();
            $table->integer('manager_checked_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objectivekey_staff');
    }
};
