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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->char('name');
            $table->decimal('cost',15,2);
            $table->integer('useful_life'); //month
            $table->dateTime('purchase_date');
            $table->unsignedInteger('third_account_id');  //fix_asset account
            $table->unsignedInteger('third_depreciation_account_id'); //third_depreciation_account
            $table->unsignedInteger('cash_account_id');
            $table->unsignedInteger('asset_item_id');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
