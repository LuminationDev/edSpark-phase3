<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserAnswer extends Model
{
    use HasFactory;

    protected $table = 'user_answers';
    public $timestamps = false;

    protected $fillable = [
        'question_id',
        'user_survey_domain_id',
        'answer',
        'answer_text'
    ];

}
