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
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('credit_terms');
            // Add the new columns
            $table->enum('credit_term_type', ['day', 'amount_limitation', 'exact_date'])->after('credit_limit');
            $table->integer('day')->nullable()->after('credit_term_type');
            $table->integer('amount_limitation')->nullable()->after('day');
            $table->json('exact_date')->nullable()->after('amount_limitation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            //
        });
    }
};
