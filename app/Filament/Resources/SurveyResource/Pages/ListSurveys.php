<?php

namespace App\Filament\Resources\SurveyResource\Pages;

use App\Filament\Resources\SurveyResource;
use App\Models\Question;
use App\Models\User;
use App\Models\UserAnswer;
use App\Models\UserSurvey;
use App\Models\UserSurveyDomain;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Log;
use Str;

class ListSurveys extends ListRecords
{
    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
//            Actions\Action::make('download_result')
//                ->label('Download results')
//                ->action(function () {
//                    $start = microtime(true);
//                    $completedSurveys = UserSurvey::where('status', 'Complete')->get();
//
//                    if ($completedSurveys->isEmpty()) {
//                        $emptyResponse = [
//                            'success' => true,
//                            'message' => 'No completed surveys found',
//                            'data' => [],
//                            'code' => 0,
//                            'locale' => 'en',
//                        ];
//                        return response()->json($emptyResponse);
//                    }
//
//                    $csvData = [];
//
//                    foreach ($completedSurveys as $userSurvey) {
//                        $user = User::find($userSurvey->user_id);
//                        $userData = [
//                            $user->full_name,
//                            $user->site->site_name,
//                        ];
//                        $csvData[] = $userData;
//                        $csvData[] = ['Domain', 'Element', 'Question Text', 'Answer', 'Answer Text'];
//
//                        $surveyDomains = UserSurveyDomain::where('user_survey_id', $userSurvey->id)
//                            ->where('status', 'Complete')
//                            ->get();
//
//                        foreach ($surveyDomains as $surveyDomain) {
//                            $userAnswers = UserAnswer::where('user_survey_domain_id', $surveyDomain->id)->get();
//
//                            foreach ($userAnswers as $userAnswer) {
//                                $question = Question::find($userAnswer->question_id);
//
//                                if ($question && $question->question) {
//                                    $csvData[] = [
//                                        $surveyDomain->domain,
//                                        strip_tags($question->element_print),
//                                        $question->indicator_print,
//                                        $question->phase_description,
//                                        $userAnswer->answer == 1 ? 'Yes' : 'No',
//                                        str_replace("\n", "-", strip_tags($question->question)),
//                                        $userAnswer->answer_text,
//                                    ];
//                                }
//                            }
//                        }
//                        $csvData[] = ['--------------------------------------------------------------'];
//                    }
//                    $fileName = Str::random(10);
//
//                    $csvFileName = 'survey_' . $fileName . '.csv';
//                    $filePath = storage_path('app/public/surveys/' . $csvFileName);
//
//                    $file = fopen($filePath, 'w');
//                    foreach ($csvData as $row) {
//                        fputcsv($file, $row);
//                    }
//
//                    fclose($file);
//                    $time_elapsed_secs = microtime(true) - $start;
//                    Log::info('Time took for csv export function ' . $time_elapsed_secs);
//                    return response()->download($filePath, $csvFileName)->deleteFileAfterSend(false);
//
//                })
        ];
    }
}
