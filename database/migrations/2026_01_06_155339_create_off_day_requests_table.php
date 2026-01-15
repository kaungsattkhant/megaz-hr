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
        Schema::create('off_day_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_timeshift_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['off_day','shift_change'])->default('off_day');
            $table->unsignedBigInteger('original_timeshift_id')->nullable();
            $table->unsignedBigInteger('change_timeshift_id')->nullable();
            $table->text('remark')->nullable();
            $table->enum('status', ['pending','confirmed','rejected'])->default('pending');
            $table->unsignedBigInteger('handled_by')->nullable();
            $table->dateTime('handled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('off_day_requests');
    }
};
