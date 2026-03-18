<?php

use App\Enums\OkrStageEnum;
use App\Enums\PDCAEnum;
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
        Schema::table('objective_staff', function (Blueprint $table) {
            $table->enum('stage',[OkrStageEnum::getValues()])->default(OkrStageEnum::NOTYET->value);
            $table->unsignedInteger('plan_by')->nullable();
            $table->dateTime('plan_at')->nullable();
            $table->unsignedInteger('do_by')->nullable();
            $table->dateTime('do_at')->nullable();
            $table->unsignedInteger('done_by')->nullable();
            $table->dateTime('done_at')->nullable();
            $table->unsignedInteger('check_by')->nullable();
            $table->dateTime('check_at')->nullable();
            $table->unsignedInteger('act_by')->nullable();
            $table->dateTime('act_at')->nullable();
            $table->dropColumn(['status', 'manager_checked_by', 'manager_checked_at','in_progress_at','in_progress_by','approved_at','approved_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objective_staff', function (Blueprint $table) {
            //
        });
    }
};
