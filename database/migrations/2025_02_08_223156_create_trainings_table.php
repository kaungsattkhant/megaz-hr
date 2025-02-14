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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_time');
            $table->dateTime('from_date');
            $table->dateTime('to_date');
            $table->longText('place');
            $table->foreignId('trained_by');
            $table->longText('title');
            $table->longText('description');
            $table->foreignId('created_by');
            $table->string('training_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
