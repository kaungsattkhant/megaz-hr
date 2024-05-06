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
        Schema::create('fixed_asset_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('fixed_asset_id')->nullable();
            $table->dateTime('date');
            $table->string('name');
            $table->string('description');
            $table->double('total_price');
            $table->double('remaining_price');
            $table->double('depreciation_amount');
            $table->double('total_duration');
            $table->double('remaining_duration');
            $table->date('start_date');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('bought_by')->nullable();
            $table->boolean('is_bought')->default(0);
            $table->unsignedBigInteger('manager_check_id')->nullable();
            $table->dateTime('manager_check_time')->nullable();
            $table->dateTime('md_check_time')->nullable();
            $table->boolean('is_md_checked')->default(0);
            $table->string('status')->default('created');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_asset_purchases');
    }
};
