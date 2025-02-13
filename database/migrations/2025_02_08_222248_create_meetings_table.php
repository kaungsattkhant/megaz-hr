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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_time');
            $table->dateTime('from_date');
            $table->dateTime('to_date');
            $table->longText('place');
            $table->foreignId('chaired_by');
            $table->longText('description');
            $table->foreignId('created_by');
            $table->string('title');
            $table->string('meeting_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
