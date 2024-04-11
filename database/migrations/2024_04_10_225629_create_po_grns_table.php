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
        Schema::create('po_grns', function (Blueprint $table) {
            $table->id();
            $table->char('invoice_no')->nullable();
            $table->char('invoice_amount')->default(0);
            $table->longText('remark')->nullable();
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('purchase_order_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_grns');
    }
};
