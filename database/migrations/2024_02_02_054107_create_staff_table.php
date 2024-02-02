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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('name',45);
            $table->string('phone_number',45);
            $table->string('password',225);
            $table->string('nrc_no',45);
            $table->string('address',45);
            $table->unsignedBigInteger('gender_id');
            $table->unsignedBigInteger('department_id');
            $table->tinyInteger('is_verified');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
