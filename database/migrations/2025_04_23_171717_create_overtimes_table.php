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
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->date('from_date');
            $table->date('to_date');
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->foreignId('overtime_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('time_shift_id')->constrained()->onDelete('cascade');
            $table->time('from_time');
            $table->time('to_time');
            $table->longText('remark')->nullable();
            $table->enum('status', ['received', 'confirmed', 'cancelled']);
            $table->unsignedInteger('created_by');
            $table->dateTime('confirmed_at')->nullable();
            $table->foreignId('confirmed_by')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->foreignId('cancelled_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtimes');
    }
};
