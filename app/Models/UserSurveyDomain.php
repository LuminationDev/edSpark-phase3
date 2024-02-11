<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserSurveyDomain extends Model
{
    use HasFactory;

    protected $table = 'user_survey_domains';
    protected $hidden = ['user_survey_id', 'created_at', 'updated_at', 'status'];

    protected $fillable = [
        'user_survey_id',
        'status',
        'domain',
        'chapter_count',
        'completed_chapter_count',
        'question_count',
        'completed_question_count',
        'next_question_id'
    ];


    public static function makeNew($userSurvey, $domain)
    {
        $questions = Question::where('survey_id', $userSurvey->survey_id)
            ->where('domain', $domain)
            ->get();
        $byChapter = $questions->groupBy('chapter');

        $userSurveyDomain = new UserSurveyDomain;
        $userSurveyDomain->user_survey_id = $userSurvey->id;
        $userSurveyDomain->status = 'In Progress';
        $userSurveyDomain->domain = $domain;
        $userSurveyDomain->chapter_count = $byChapter->count();
        $userSurveyDomain->completed_chapter_count = 0;
        $userSurveyDomain->question_count = $questions->count();
        $userSurveyDomain->completed_question_count = 0;
        $userSurveyDomain->next_question_id = $questions[0]->id;

        $userSurveyDomain->save();
        return $userSurveyDomain;
    }
}
