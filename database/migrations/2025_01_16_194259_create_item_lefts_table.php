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
        Schema::create('item_lefts', function (Blueprint $table) {
            $table->id();
            $table->integer('base_uom_id');
            $table->integer('base_uom_quantity');
            $table->foreignId('uom_id');
            $table->integer('uom_quantity');
            $table->integer('quantity');
            $table->double('amount');
            $table->integer('created_by');

            $table->integer('item_leftable_id');
            $table->string('item_leftable_type');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_lefts');
    }
};
