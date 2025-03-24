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
        Schema::create('invoice_accessories', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->double('accessory_price')->default(0);
            $table->unsignedInteger('accessory_id');
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->boolean('is_package')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_accessories');
    }
};
