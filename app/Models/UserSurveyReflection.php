<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserSurveyReflection extends Model
{
    use HasFactory;

    protected $table = 'user_survey_reflections';

    protected $fillable = [
        'user_survey_domain_id',
        'reflection',
    ];

}
