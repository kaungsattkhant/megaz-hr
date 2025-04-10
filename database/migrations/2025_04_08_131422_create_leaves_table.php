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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_category_id')->constrained('leave_categories');
            $table->longText('title')->nullable();
            $table->longText('detail')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('day');
            $table->boolean('isIncludeWeekends');
            $table->foreignId('staff_id');
            $table->enum('status', ['received', 'confirmed', 'cancelled']);
            $table->foreignId('created_by');
            $table->string('image_url')->nullable();
            $table->string('image_path')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->foreignId('confirmed_by')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->foreignId('cancelled_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
