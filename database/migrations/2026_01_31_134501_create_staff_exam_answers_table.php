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
        Schema::create('staff_exam_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('staff_exam_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('exam_question_id');
            $table->unsignedInteger('answer_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_exam_answers');
    }
};
