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
        Schema::create('staff_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('staff_id');
            $table->unsignedInteger('exam_id');
            $table->unsignedInteger('grade_id');
            $table->double('total_mark');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_exams');
    }
};
