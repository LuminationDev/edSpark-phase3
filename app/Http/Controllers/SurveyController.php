<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Survey;
use App\Models\User;
use App\Models\UserAnswer;
use App\Models\UserSurvey;
use App\Models\UserSurveyDomain;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SurveyController extends Controller
{

    public function getUserSurvey(Request $request): JsonResponse
    {
        $user = User::find(Auth::user()->id);
        // check if they have an active survey
        $userSurvey = UserSurvey::where('user_id', $user->id)
            ->where('status', '<>', 'Abandoned')
            ->first();

        if ($userSurvey != null) {
            $survey_domains = UserSurveyDomain::where('user_survey_id', $userSurvey->id)
                ->where('status', '<>', 'Abandoned')
                ->get();
        } else {
            Log::info('Creating new survey for user');
            // need to create an active survey before wrangling
            $survey = Survey::where('is_active', true)
                ->first();
            if ($survey == null) {
                return $this->surveyNotFound();
            }

            $userSurvey = UserSurvey::makeNew($survey, $user->id);
            // make the user_survey_domains
            foreach (Question::$DOMAINS as $domain) {
                $survey_domains[] = UserSurveyDomain::makeNew($userSurvey, $domain);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'OK',
            'code' => 0,
            'locale' => 'en',
            'data' => [
                //n.b. misnomer - this is actually the user_survey_id
                'survey_id' => $userSurvey->id,
                'survey_domains' => $survey_domains
            ],
        ]);
    }

    public function getSurveyQuestionsForDomain(Request $request, $user_domain_id): JsonResponse
    {
        Log::info('Received request ' . ' / ' . $user_domain_id);
        $userDomain = UserSurveyDomain::find($user_domain_id);
        if ($userDomain == null) {
            return $this->domainNotFound();
        }

        $userSurvey = UserSurvey::find($userDomain->user_survey_id);
        if (!$userSurvey) {
            return $this->surveyNotFound();
        }

        $domainQuestions = Question::where('survey_id', $userSurvey->survey_id)
            ->where('domain', $userDomain->domain)
            ->get();

        return response()->json(
            [
                'success' => true,
                'message' => 'OK',
                'code' => 0,
                'locale' => 'en',
                'data' => [
                    'domain_questions' => $domainQuestions,
                ],
            ]
        );
    }

    public function saveUserAnswerToQuestion(Request $request): JsonResponse
    {
        $user = User::find(Auth::user()->id);

        $user_domain_id = $request['domain_id'];
        $questionId = $request['question_id'];
        $answer = $request['answer'];
        $answerText = $request['answer_text'];
        $nextQuestionId = $request['next_question_id'];
        $increaseCompletedChapterCount = $request['increase_completed_chapter_count'];

        $userSurvey = UserSurvey::where('user_id', $user->id)
            ->where('status', '<>', 'Abandoned')
            ->first();

        if ($userSurvey == null) {
            return $this->surveyNotFound();
        }

        if (Question::find($questionId) == null) {
            return $this->questionNotFound();
        }

        $currentUserSurveyDomain = UserSurveyDomain::find($user_domain_id);
        if ($currentUserSurveyDomain) {
            if ($increaseCompletedChapterCount) {
                ++$currentUserSurveyDomain->completed_chapter_count;
            }
            // increase question count to the next question id
            // this accounts for the skipped questions due to pressing 'no'
            $currentUserSurveyDomain->completed_question_count = min(
                $currentUserSurveyDomain->completed_question_count + $nextQuestionId - $questionId,
                $currentUserSurveyDomain->question_count);
            $currentUserSurveyDomain->next_question_id = $nextQuestionId;
            if ($currentUserSurveyDomain->completed_question_count >= $currentUserSurveyDomain->question_count) {
                // there are no remaining questions
                $currentUserSurveyDomain->status = "Complete";
                $currentUserSurveyDomain->next_question_id = null;
                $currentUserSurveyDomain->save();

                // if there are no other domains In Progress -> set the survey to completed
                $numInProgress = UserSurveyDomain::where('user_survey_id', $userSurvey->survey_id)
                    ->where('status', 'In Progress')
                    ->count();
                if ($numInProgress == 0) {
                    $userSurvey->status = 'Complete';
                    $userSurvey->save();
                }
            } else {
                $currentUserSurveyDomain->save();
            }
        }

        // create answer and save
        $userAnswer = new UserAnswer();
        $userAnswer->question_id = $questionId;
        $userAnswer->user_survey_domain_id = $user_domain_id;
        // strval to satisfy enum
        $userAnswer->answer = strval($answer);
        $userAnswer->answer_text = $answerText;
        $userAnswer->save();

        return response()->json([
            'success' => true,
            'message' => 'Answer saved successfully',
            'data' => (object)[],
            'code' => 0,
            'locale' => 'en',
        ]);
    }

    public function resetUserSurvey(Request $request): JsonResponse
    {
        $user = User::find(Auth::user()->id);
        $userSurvey = UserSurvey::where('user_id', $user->id)
            ->where('status', '<>', 'Abandoned')
            ->first();

        if ($userSurvey) {
            $userSurvey->abandon();
        }

        return response()->json([
            'success' => true,
            'message' => 'Survey reset successfully',
            'data' => (object)[],
            'code' => 0,
            'locale' => 'en',
        ]);
    }

    public function resetUserSurveyDomain(Request $request, $domain_id): JsonResponse
    {
        $currentUserSurveyDomain = UserSurveyDomain::find($domain_id);
        if (!$currentUserSurveyDomain) {
            return $this->domainNotFound();
        }
        // mark the old domain as abandoned
        $currentUserSurveyDomain['status'] = 'Abandoned';
        $currentUserSurveyDomain->save();

        //make a new user survey domain
        $user = User::find(Auth::user()->id);
        $userSurvey = UserSurvey::where('user_id', $user->id)
            ->where('status', '<>', 'Abandoned')
            ->first();
        if ($userSurvey) {
            UserSurveyDomain::makeNew($userSurvey, $currentUserSurveyDomain->domain);
        }

        return response()->json([
            'success' => true,
            'message' => 'Domain reset successfully',
            'data' => (object)[],
            'code' => 0,
            'locale' => 'en',
        ]);
    }

    public function domainNotFound(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Domain not found',
            'data' => (object)[],
            'code' => 31,
            'locale' => 'en',
        ], 422);
    }

    public function surveyNotFound(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Survey ID not found',
            'data' => (object)[],
            'code' => 30,
            'locale' => 'en',
        ], 422);
    }

    public function questionNotFound(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Question ID not found',
            'data' => (object)[],
            'code' => 34,
            'locale' => 'en',
        ], 422);
    }
}
