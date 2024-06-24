<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSurvey extends Model
{
    use HasFactory;

    protected $table = 'user_surveys';

    public static array $STATUS_TYPES = ['In Progress', 'Abandoned', 'Complete', 'Superseded'];

    protected $fillable = [
        'user_id',
        'survey_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function makeNew($survey, $userId): UserSurvey
    {
        $userSurvey = new UserSurvey();
        $userSurvey->survey_id = $survey->version;
        $userSurvey->user_id = $userId;
        $userSurvey->status = 'In Progress';
        $userSurvey->save();
        return $userSurvey;
    }

    public function abandon(): void
    {
        if ($this['status'] === 'Complete') {
            $this['status'] = 'Superseded';
        } else {
            $this['status'] = 'Abandoned';
        }
        $surveyDomains = UserSurveyDomain::where('user_survey_id', $this->id)
            ->get();
        foreach ($surveyDomains as $surveyDomain) {
            if ($surveyDomain) {
                // abandon all domains
                if ($surveyDomain['status'] === 'Complete') {
                    $surveyDomain['status'] = 'Superseded';
                } else {
                    $surveyDomain['status'] = 'Abandoned';
                }
                $surveyDomain->save();
            }
        }
        $this->save();

    }

}
