<?php

namespace App\Http\Controllers;

use App\Models\Question;
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
// TODO: test this file


    //todo: survey ID may be the user survey id?
    public function getSurveyQuestionsForDomain(Request $request, $surveyId, $domain): JsonResponse
    {
        Log::info('Received request ' . $surveyId . ' / ' . $domain);
        if (!in_array($domain, Question::$DOMAINS, true)) {
            return response()->json([
                'locale' => 'en',
                'success' => false,
                'message' => 'Domain not found',
                'code' => 31,
                'data' => (object)[]
            ], 422);
        }
        $domainQuestions = Question::where('survey_id', $surveyId)
            ->where('domain', $domain)
            ->first();
        if ($domainQuestions == null) {
            return response()->json([
                'locale' => 'en',
                'success' => false,
                'message' => 'Survey ID not found',
                'code' => 30,
                'data' => (object)[]
            ], 422);
        }
        return response()->json($domainQuestions);
    }

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
            $userSurvey = UserSurvey::makeNew($user->id);
            // make the user_survey_domains
            foreach (Question::$DOMAINS as $domain) {
                $survey_domains[] = UserSurveyDomain::makeNew($userSurvey, $domain);
            }
        }

        return response()->json([
            'locale' => 'en',
            'success' => true,
            'code' => 0,
            'message' => 'OK',
            'data' => [
                //n.b. misnomer - this is actually the user_survey_id
                'survey_id' => $userSurvey->id,
                'survey_domains' => $survey_domains
            ]
        ]);
    }

    public function saveUserAnswerToQuestion(Request $request, $surveyId): JsonResponse
    {
        $user = User::find(Auth::user()->id);

        $domain = $request['domain'];
        $questionId = $request['question_id'];
        $answer = $request['answer'];
        $answerText = $request['answer_text'];
        $nextQuestionId = $request['next_question_id'];
        $increaseCompletedChapterCount = $request['increase_completed_chapter_count'];


        $userSurvey = UserSurvey::where('survey_id', $surveyId)
            ->where('user_id', $user->id)
            ->where('status', '<>', 'Abandoned');

        if ($userSurvey == null) {
            return response()->json([
                'locale' => 'en',
                'success' => false,
                'message' => 'Survey ID not found',
                'code' => 30,
                'data' => (object)[]
            ], 422);
        }
        if (!in_array($domain, Question::$DOMAINS, true)) {
            return response()->json([
                'locale' => 'en',
                'success' => false,
                'message' => 'Domain not found',
                'code' => 31,
                'data' => (object)[]

            ], 422);
        }
        if (Question::find($questionId) == null) {
            return response()->json([
                'locale' => 'en',
                'success' => false,
                'message' => 'Question ID not found',
                'code' => 34,
                'data' => (object)[]
            ], 422);

        }

        // get the corresponding survey domain
        $currentUserSurveyDomain = UserSurveyDomain::where('user_survey_id', $userSurvey->id)
            ->where('domain', $domain)
            ->first();
        if ($currentUserSurveyDomain) {
            if ($increaseCompletedChapterCount) {
                ++$currentUserSurveyDomain->completed_chapter_count;
            }
            //todo: Under some circumstances we need to set the domain to complete here
            //todo: if we finish the survey as well we need to set that to complete here too

            //increase question count to the next question id
            $currentUserSurveyDomain->completed_question_count += $nextQuestionId - $questionId;

            $currentUserSurveyDomain->next_question_id = $nextQuestionId;
            $currentUserSurveyDomain->save();
        }

        // create answer and save
        $userAnswer = new UserAnswer();
        $userAnswer->question_id = $questionId;
        $userAnswer->user_survey_domain_id = null; //todo
        $userAnswer->answer = $answer;
        $userAnswer->answer_text = $answerText;
        $userAnswer->save();


        return response()->json([
            'code' => 0,
            'message' => 'Answer saved successfully'
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
            'code' => 0,
            'message' => 'Survey reset successfully'
        ]);
    }


    public function resetUserSurveyDomain(Request $request, $domain): JsonResponse
    {
        $user = User::find(Auth::user()->id);
        if (!in_array($domain, Question::$DOMAINS, true)) {
            return response()->json([
                'message' => 'Domain not found',
                'code' => 31
            ], 422);
        }
        $userSurvey = UserSurvey::where('user_id', $user->id)
            ->where('status', '<>', 'Abandoned')
            ->first();
        if ($userSurvey) {
            // mark the old domain as abandoned
            $currentUserSurveyDomain = UserSurveyDomain::where('user_survey_id', $userSurvey->id)
                ->where('domain', $domain)
                ->first();
            if ($currentUserSurveyDomain) {
                $currentUserSurveyDomain['status'] = 'Abandoned';
                $currentUserSurveyDomain->save();
            }
        }
        UserSurveyDomain::makeNew($userSurvey, $domain);


        return response()->json([
            'code' => 0,
            'message' => 'Domain reset successfully'
        ]);

    }

}
