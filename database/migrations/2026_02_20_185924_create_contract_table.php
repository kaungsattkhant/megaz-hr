<?php

use App\Enums\ContractTypeEnum;
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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->enum('type',[ContractTypeEnum::getValues()]);
            $table->unsignedInteger('company_authorizer_id')->nullable();
            $table->unsignedInteger('witness_id')->nullable();
            $table->longText('text')->nullable();
            $table->unsignedInteger('contract_category_id');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
