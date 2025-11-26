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
        Schema::create('check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id');
            $table->foreignId('time_shift_id');
            $table->dateTime('check_in_date_time');
            $table->dateTime('check_out_date_time')->nullable();
            $table->string('check_in_photo_url')->nullable();
            $table->string('check_in_photo_path')->nullable();
            $table->string('check_out_photo_url')->nullable();
            $table->string('check_out_photo_path')->nullable();
            $table->tinyInteger('is_current_checked_in')->default(null);
            $table->boolean('is_self_checkout')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_ins');
    }
};
