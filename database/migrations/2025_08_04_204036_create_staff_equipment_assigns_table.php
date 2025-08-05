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
        Schema::create('staff_equipment_assigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_equipment_id')->constrained('staff_equipment')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->foreignId('uom_id')->constrained('uoms')->onDelete('cascade');
            $table->integer('uom_quantity');
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
        Schema::dropIfExists('staff_equipment_assigns');
    }
};
