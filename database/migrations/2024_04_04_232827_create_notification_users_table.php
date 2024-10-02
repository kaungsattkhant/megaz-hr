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
        Schema::create('notification_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('is_read')->default(0);
            $table->boolean('is_read_count')->default(0);
            $table->dateTime('read_at')->nullable();
            $table->string('title')->nullable();
            $table->string('preview')->nullable();
            $table->unsignedInteger('staff_id');
            $table->unsignedInteger('notification_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_users');
    }
};
