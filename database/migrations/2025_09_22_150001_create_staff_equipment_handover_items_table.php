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
        Schema::create('staff_equipment_handover_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_equipment_handover_id')->constrained('staff_equipment_handovers')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('uom_id')->constrained('uoms')->cascadeOnDelete();
            $table->unsignedBigInteger('uom_quantity');
            $table->double('quantity');
            $table->enum('uom_type', ['base_uom', 'uom']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_equipment_handover_items');
    }
};
