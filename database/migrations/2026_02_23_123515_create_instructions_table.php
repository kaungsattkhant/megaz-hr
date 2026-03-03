<?php

use App\Enums\PDCAEnum;
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
        Schema::create('instructions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_minute_id')->constrained('meeting_minutes')->cascadeOnDelete();            $table->unsignedInteger('objective_id');
            $table->double('okr_point')->nullable();
            $table->unsignedInteger('project_id');
            $table->enum('tag', [PDCAEnum::getValues()])->nullable();
            $table->unsignedInteger('assigned_to')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->longText('reamark')->nullable();
            $table->unsignedInteger('accountable_id')->nullable();
            $table->unsignedInteger('consulted_id')->nullable();
            $table->unsignedInteger('informed_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructions');
    }
};
