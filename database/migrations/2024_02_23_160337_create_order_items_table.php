<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements(column: 'id');
            $table->dateTime('date');
            $table->unsignedBigInteger('menu_id');
            $table->integer('quantity');
            $table->foreignId('area_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('original_price');
            $table->double('sub_total_price')->default(0);
            $table->integer("discount_value")->default(0);
            $table->integer('price');
            $table->unsignedBigInteger('order_id');
            $table->string('status')->default('not yet');
            $table->boolean('is_complete')->default(value: 0);
            $table->dateTime('progressed_at')->nullable();
            $table->integer('progressed_by')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->integer('confirmed_by')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->integer('cancelled_by')->nullable();
            $table->dateTime('kitchen_cancelled_at')->nullable();
            $table->integer('kitchen_cancelled_by')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->integer('completed_by')->nullable();
            $table->dateTime('placed_at')->nullable();
            $table->integer('placed_by')->nullable();
            $table->foreignId('menu_service_discount_id')->nullable()->foreignId()->constrained()->onDelete('cascade');
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
