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
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 45);
            $table->string('code');
            $table->foreignId('category_id')->constrained();
            $table->unsignedInteger('item_type_id');
            $table->unsignedBigInteger('base_uom_id'); //base uom mean large unit
            $table->unsignedBigInteger('uom_id'); //inventory store unit
            $table->boolean('is_active')->default(1);
            $table->double('min_holding_base_uom_quantity');
            $table->double('min_holding_uom_quantity');
            $table->double('minimum_holding_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
