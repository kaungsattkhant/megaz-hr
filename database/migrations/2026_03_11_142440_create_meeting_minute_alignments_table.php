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
        Schema::create('meeting_minute_alignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_minute_id')
                ->constrained('meeting_minutes')
                ->cascadeOnDelete();
            $table->foreignId('alignment_id')
                ->constrained('alignments')
                ->cascadeOnDelete();
            $table->string('remark');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_minute_alignments');
    }
};
