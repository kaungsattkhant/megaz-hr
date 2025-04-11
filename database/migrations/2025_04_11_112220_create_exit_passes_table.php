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
        Schema::create('exit_passes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exit_category_id')->constrained('exit_categories');
            $table->foreignId('staff_id');
            $table->longText('detail')->nullable();
            $table->dateTime('exit_date_time');
            $table->dateTime('arrival_date_time');
            $table->enum('status', ['received', 'confirmed', 'arrival_received', 'arrival_confirmed']);
            $table->dateTime('arrival_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exit_passes');
    }
};
