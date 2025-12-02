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
        Schema::create('budget_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_priority_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->double('amount');
            // main account
            $table->nullableMorphs('main_account');
            // sub account
            $table->nullableMorphs('sub_account');
            $table->string('status')->default('pending');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_accounts');
    }
};
