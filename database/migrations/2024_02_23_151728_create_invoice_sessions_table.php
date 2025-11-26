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
        Schema::create('invoice_sessions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->double('total_session')->default(0);
            $table->double('total_session_duration')->default(0);
            $table->double('total_session_price')->default(0);
            $table->double('session_unit_price')->default(0);
            $table->double('discount_session')->default(0);
            $table->double('discount_session_price')->default(0);
            $table->unsignedInteger('discount_id')->nullable();
            $table->integer('change_room_order')->default(1);
            $table->unsignedInteger('invoice_id')->foreignId();
            $table->unsignedInteger('entity_id')->foreignId();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_sessions');
    }
};
