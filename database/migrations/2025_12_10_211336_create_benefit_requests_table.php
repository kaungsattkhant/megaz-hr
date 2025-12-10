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
        Schema::create('benefit_requests', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_time');
            $table->unsignedInteger('benefit_id');
            $table->unsignedInteger('staff_id');
            $table->string('image')->nullable();
            $table->enum('status', ['received','confirmed','cancelled']);
            $table->unsignedInteger('confirmed_by')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->unsignedInteger('cancelled_by')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->unsignedInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('benefit_requests');
    }
};
