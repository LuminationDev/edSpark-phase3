<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Survey;
use App\Models\User;
use App\Models\UserAnswer;
use App\Models\UserSurvey;
use App\Models\UserSurveyActionPlan;
use App\Models\UserSurveyDomain;
use App\Models\UserSurveyReflection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
            foreach ($survey_domains as &$survey_domain) {

                $survey_domain['results'] = $this->getScoresForSurvey($userSurvey, $survey_domain);
                $survey_domain['met_dependencies'] = $this->getMetDependencies($survey_domain);
            }
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
                $user_survey_domain = UserSurveyDomain::makeNew($userSurvey, $domain);
                $user_survey_domain['results'] = $this->getScoresForSurvey($userSurvey, $user_survey_domain);
                $user_survey_domain['met_dependencies'] = [];
                $survey_domains[] = $user_survey_domain;
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

    public function getUserActionPlan(Request $request): JsonResponse
    {
        $user = User::find(Auth::user()->id);
        $userSurvey = UserSurvey::where('user_id', $user->id)
            ->where('status', '<>', 'Abandoned')
            ->first();

        if ($userSurvey == null) {
            return $this->surveyNotFound();
        }

        $survey_domains = UserSurveyDomain::where('user_survey_id', $userSurvey->id)
            ->where('status', '<>', 'Abandoned')
            ->get();

        foreach ($survey_domains as $survey_domain) {
            $results[$survey_domain->domain] = UserSurveyActionPlan::where('user_survey_domain_id', $survey_domain->id)->get();
        }

        return response()->json(
            [
                'success' => true,
                'message' => 'OK',
                'code' => 0,
                'locale' => 'en',
                'data' => [
                    'action_plan' => $results
                ]
            ]
        );
    }

    public function saveUserActionPlan(Request $request, $user_domain_id): JsonResponse
    {
        $user = User::find(Auth::user()->id);
        $userSurvey = UserSurvey::where('user_id', $user->id)
            ->where('status', '<>', 'Abandoned')
            ->first();

        if ($userSurvey == null) {
            return $this->domainNotFound();
        }

        $userDomain = UserSurveyDomain::where('id', $user_domain_id)
            ->where('user_survey_id', $userSurvey->id)
            ->first();
        if ($userDomain == null) {
            return $this->domainNotFound();
        }

        $element = $request['element'];

        $domainQuestions = Question::where('survey_id', $userSurvey->survey_id)
            ->where('domain', $userDomain->domain)
            ->where('element_print', $element)
            ->get();

        Log::info(print_r($domainQuestions, true));
        if (empty($domainQuestions) || $domainQuestions->isEmpty()) {
            return $this->elementNotFound();
        }

        // check if an action plan for this element already exists
        $userActionPlan = UserSurveyActionPlan::where('user_survey_domain_id', $user_domain_id)
            ->where('element', $element)
            ->first();

        if ($userActionPlan == null) {
            $userActionPlan = new UserSurveyActionPlan();
            $userActionPlan->user_survey_domain_id = $user_domain_id;
            $userActionPlan->element = $element;
        }
        $userActionPlan->action = $request['action'];
        $userActionPlan->save();

        return response()->json(
            [
                'success' => true,
                'message' => 'Action plan saved successfully',
                'code' => 0,
                'locale' => 'en',
                'data' => (object)[]

            ]
        );
    }

    public function saveUserReflection(Request $request, $user_domain_id): JsonResponse
    {
        $user = User::find(Auth::user()->id);
        $userSurvey = UserSurvey::where('user_id', $user->id)
            ->where('status', '<>', 'Abandoned')
            ->first();

        if ($userSurvey == null) {
            return $this->domainNotFound();
        }

        $userDomain = UserSurveyDomain::where('id', $user_domain_id)
            ->where('user_survey_id', $userSurvey->id)
            ->first();
        if ($userDomain == null) {
            return $this->domainNotFound();
        }

        // check if a reflection already exists
        $userReflection = UserSurveyReflection::where('user_survey_domain_id', $user_domain_id)
            ->first();
        if ($userReflection == null) {
            $userReflection = new UserSurveyReflection();
            $userReflection->user_survey_domain_id = $user_domain_id;
        }
        $userReflection->reflection = $request['reflection'];
        $userReflection->save();

        return response()->json(
            [
                'success' => true,
                'message' => 'Reflection saved successfully',
                'code' => 0,
                'locale' => 'en',
                'data' => (object)[]
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
        $increaseCompletedElementCount = $request['increase_completed_element_count'];

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
            if ($increaseCompletedElementCount) {
                ++$currentUserSurveyDomain->completed_element_count;
            }
            if ($nextQuestionId == null) {
                $currentUserSurveyDomain->completed_question_count = $currentUserSurveyDomain->question_count;
            } else {
                // increase question count to the next question id
                // this accounts for the skipped questions due to pressing 'no'
                //note: this assumes that domain questions are ordered
                $currentUserSurveyDomain->completed_question_count = $currentUserSurveyDomain->completed_question_count + $nextQuestionId - $questionId;
            }
            $currentUserSurveyDomain->next_question_id = $nextQuestionId;
            if ($currentUserSurveyDomain->completed_question_count >= $currentUserSurveyDomain->question_count) {
                // there are no remaining questions
                $currentUserSurveyDomain->status = "Complete";
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

    /** Get the user scores for each indicator
     * This query gets all the questions for the domain
     * and the corresponding user answers
     * Gives a score of 0 - MAX(Phase) for each indicator
     *  The score is 0 if unanswered, or if the user said 0 to phase 1
     *  otherwise it is the phase of the highest phase question
     *  they answered yes to.
     */
    protected function getScoresForSurvey($user_survey, $user_survey_domain)
    {
        $highestScores = Question::selectRaw("
                MAX(CASE WHEN answer = '1'
                            THEN phase
                            ELSE 0
                            END) as value,
                            indicator_print as indicator, element_print as element")
            ->leftJoin('user_answers', function ($join) use ($user_survey_domain) {
                $join->on('questions.id', '=', 'user_answers.question_id')
                    ->where('user_answers.user_survey_domain_id', '=', $user_survey_domain->id);
            })
            ->where('questions.domain', '=', $user_survey_domain->domain)
            ->whereNotNull('indicator_print')
            ->groupBy('indicator_print', 'element_print');

        // Join questions based on highest score for the indicator.
        // Non-question items (before first question in each indicator)
        // have a score of 0 (element cover screen) or -1 (non-cover).
        // 0-score answers use the description from these items, so normalise to 0.
        return Question::selectRaw('questions.id, scores.value, scores.indicator, scores.element, questions.description')
            ->joinSub($highestScores, 'scores', function ($join) {
                $join->on('scores.indicator', '=', 'questions.indicator_print')
                    ->on('scores.element', '=', 'questions.element_print')
                    ->on('scores.value', '=', DB::raw('GREATEST(questions.phase, 0)'));
            })
            ->where('questions.survey_id', '=', $user_survey->survey_id)
            ->get()
            ->flatten(3);
    }

    /** Get the met dependencies for each question answered 'yes'
     * This query gets all the questions for the domain
     * and the corresponding user answers where the answer was yes
     * and returns the list of all generated_variables as a flat array.
     */
    protected function getMetDependencies($user_survey_domain)
    {
        $result = Question::select("generated_variable")
            ->leftJoin('user_answers', function ($join) use ($user_survey_domain) {
                $join->on('questions.id', '=', 'user_answers.question_id')
                    ->where('user_answers.user_survey_domain_id', '=', $user_survey_domain->id);
            })
            ->where('user_answers.answer', '=', '1')
            ->groupBy('generated_variable')
            ->get();
        return $result->pluck('generated_variable');
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

    private function elementNotFound()
    {
        return response()->json([
            'success' => false,
            'message' => 'Element not found',
            'data' => (object)[],
            'code' => 34,
            'locale' => 'en',
        ], 422);
    }
}
