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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('creditor_account_id');
            $table->char('name', 120);
            $table->char('shop_name', 120);
            $table->longText('address')->nullable();
            $table->string('email')->nullable();
            $table->integer('credit_limit');
            $table->longText('credit_terms');
            $table->double('credit_opening_date');
            $table->double('credit_opening_amount');
            $table->integer('lead_time_day')->nullable();
            $table->float('lead_time_hour', 8, 2)->nullable();
            $table->integer('lead_time_minutes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
