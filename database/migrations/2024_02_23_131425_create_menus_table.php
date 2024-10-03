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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('menu_category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('image_url');
            $table->string('image_path');
            $table->boolean('is_feature')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_prices');
        Schema::dropIfExists('menus');
    }
};
