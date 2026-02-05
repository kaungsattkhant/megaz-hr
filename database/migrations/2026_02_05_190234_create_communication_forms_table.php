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
        Schema::create('communication_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->date('date');
            $table->longText('personal_information')->nullable();
            $table->longText('career_object')->nullable();
            $table->longText('hobby')->nullable();
            $table->longText('value')->nullable();
            $table->longText('life_ambitions')->nullable();
            $table->longText('education_background')->nullable();
            $table->longText('skill_competence')->nullable();
            $table->longText('work_experience')->nullable();
            $table->longText('attitude_evaluation')->nullable();
            $table->longText('social_factor')->nullable();
            $table->longText('job_source')->nullable();
            $table->longText('why_applying_position')->nullable();
            $table->longText('brief_plan_for_success')->nullable();
            $table->longText('challenge_expectation_and_commitment')->nullable();
            $table->longText('expected_salary')->nullable();
            $table->longText('life_audit')->nullable();
            $table->longText('swot_analysis')->nullable();
            $table->longText('core_value')->nullable();
            $table->longText('parallel_feature')->nullable();
            $table->longText('future_plan')->nullable();
            $table->longText('first_three_month_future_plan')->nullable();
            $table->longText('must_do_daily_activity')->nullable();
            $table->longText('overcoming_obstacles')->nullable();
            $table->longText('future_review')->nullable();
            $table->longText('declaration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communication_forms');
    }
};
