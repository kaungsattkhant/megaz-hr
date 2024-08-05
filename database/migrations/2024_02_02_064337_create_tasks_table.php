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
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('area_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->string('type')->default('task');
            $table->decimal('kpi')->nullable();
            $table->string('name',45);
            $table->longText('description');
            $table->set('assigned_days', ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'])->nullable();
            $table->unsignedBigInteger('created_by');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->boolean('is_active')->default(1);
            $table->unsignedBigInteger('staff_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
