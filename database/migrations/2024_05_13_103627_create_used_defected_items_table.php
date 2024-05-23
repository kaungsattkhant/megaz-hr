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
        Schema::create('used_defected_items', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->foreignId('item_id')->constrained();
            $table->foreignId('uom_id')->constrained();
            $table->foreignId('uom_conversion_id')->constrained();
            $table->foreignId('inventory_id')->constrained();
            $table->string('type');
            $table->double('quantity');
            $table->string('remark')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_confirmed')->default(0);
            $table->unsignedBigInteger('confirmed_by')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('used_defected_items');
    }
};
