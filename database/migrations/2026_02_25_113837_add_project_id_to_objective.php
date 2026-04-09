<?php

use App\Enums\PriorityEnum;
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
        Schema::table('objectives', function (Blueprint $table) {
            $table->unsignedInteger('project_id')->nullable();
            $table->enum('priority', PriorityEnum::getValues())->default(PriorityEnum::NOT_URGENT_NOT_IMPORTANT->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objectives', function (Blueprint $table) {
            //
        });
    }
};
