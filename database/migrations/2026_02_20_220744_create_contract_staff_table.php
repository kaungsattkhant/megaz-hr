<?php

use App\Enums\ContractStaffEnum;
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
        Schema::create('contract_staff', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('staff_id');
            $table->string('signed_document')->nullable();
            $table->dateTime('signed_at')->nullable();
            $table->unsignedInteger('confirmed_by')->nullable();
            $table->dateTime('confirmed_at')->nullable();   
            $table->unsignedInteger('cancelled_by')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->enum('status',ContractStaffEnum::getValues())->default(ContractStaffEnum::DRAFT->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_staff');
    }
};
