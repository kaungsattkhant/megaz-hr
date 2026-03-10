<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationForm extends Model
{
    //
    protected $fillable = [
        'staff_id',
        'date',
        'personal_information',
        'career_object',
        'hobby',
        'value',
        'life_ambitions',
        'education_background',
        'skill_competence',
        'work_experience',
        'attitude_evaluation',
        'social_factor',
        'job_source',
        'why_applying_position',
        'brief_plan_for_success',
        'challenge_expectation_and_commitment',
        'expected_salary',
        'life_audit',
        'swot_analysis',
        'core_value',
        'parallel_feature',
        'future_plan',
        'first_three_month_future_plan',
        'must_do_daily_activity',
        'overcoming_obstacles',
        'future_review',
        'declaration',
        'signature'
    ];
}
