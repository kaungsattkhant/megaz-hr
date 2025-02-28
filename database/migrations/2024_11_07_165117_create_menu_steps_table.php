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
            $table->foreignId('role_id');
            $table->double('duration');
            $table->double('order_time')->nullable();
            $table->double('expected_quantity')->nullable();
            $table->string('level');
            $table->string('type');
            $table->date('expired_at')->nullable();
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
