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
        Schema::create('resignations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resign_category_id')->constrained()->onDelete('cascade');
            $table->dateTime('resignation_date');
            $table->enum('status', ['received', 'confirmed', 'cancelled']);
            $table->longText('detail')->nullable();
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->string('image_path')->nullable();
            $table->string('image_url')->nullable();
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
        Schema::dropIfExists('resignations');
    }
};
