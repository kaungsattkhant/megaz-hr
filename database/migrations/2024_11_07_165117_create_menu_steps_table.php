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
        Schema::create('menu_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id');
            $table->foreignId('staff_id');
            $table->integer('staff_quantity');
            $table->double('duration');
            $table->double('order_time');
            $table->double('expected_quantity');
            $table->string('level');
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_steps');
    }
};
