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
        Schema::create('pack_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pack_id');
            $table->foreignId('item_id');
            $table->foreignId('menu_id');
            $table->foreignId('uom_id');
            $table->double('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pack_menus');
    }
};
