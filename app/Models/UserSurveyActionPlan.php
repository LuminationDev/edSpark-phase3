<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserSurveyActionPlan extends Model
{
    use HasFactory;

    protected $table = 'user_survey_action_plans';
    protected $hidden = [
        'user_survey_domain_id',
        'id',
        'updated_at',
        'created_at',
    ];
    protected $fillable = [
        'user_survey_domain_id',
        'action',
        'element',
        'selected',
    ];
}
