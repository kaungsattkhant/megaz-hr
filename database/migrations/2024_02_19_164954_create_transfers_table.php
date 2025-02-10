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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_id')->nullable();
            $table->unsignedBigInteger('source_inventory_id');
            $table->unsignedBigInteger('destination_inventory_id');
            $table->double('transfer_quantity')->default(0);
            $table->double('quantity')->default(0);
            $table->foreignId('item_id')->constrained();
            $table->foreignId('uom_id')->constrained();
            $table->foreignId('uom_conversion_id')->constrained();
            $table->dateTime('date');
            $table->unsignedBigInteger('created_by');
            $table->dateTime('confirmed_at')->nullable();
            $table->unsignedBigInteger('confirmed_by')->nullable();
            $table->string('status')->default('pending'); // pending/confirmed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
