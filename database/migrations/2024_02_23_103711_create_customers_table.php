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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gender_id')->constrained()->onDelete('cascade');
            $table->foreignId('township_id')->constrained()->onDelete('cascade');
            $table->string('image_url')->nullable();
            $table->string('image_path')->nullable();
            $table->string('password');
            $table->string('name',45);
            $table->string('phone_number')->unique();
            $table->date('birthdate');
            $table->string('email')->unique()->nullable();
            $table->string('address');
            $table->double('rentation')->default(0);
            $table->string('otp');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
