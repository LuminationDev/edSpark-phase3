<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyDownloadResource\Pages;
use App\Filament\Resources\SurveyDownloadResource\RelationManagers;
use App\Models\Question;
use App\Models\Survey;
use App\Models\SurveyDownload;
use App\Models\UserAnswer;
use App\Models\UserSurvey;
use App\Models\User;
use App\Models\UserSurveyDomain;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Str;

class SurveyDownloadResource extends Resource
{
    protected static ?string $model = UserSurvey::class;
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static ?string $navigationGroup = 'DMA Tool';
    protected static ?string $modelLabel = 'Result';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_name')
                    ->label('Name')
                    ->getStateUsing(function ($record): string {
                        $userId = $record->user_id;
                        $user = User::find($userId);
                        if (!empty($user)) {
                            return $user->full_name;
                        } else {
                            return '';
                        }
                    }),
                Tables\Columns\TextColumn::make('site_name')
                    ->label('Site Name')
                    ->getStateUsing(function ($record): string {
                        $userId = $record->user_id;
                        $user = User::find($userId);
                        if (!empty($user)) {
                            return $user->site->site_name;
                        } else {
                            return '';
                        }
                    }),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Completion date')
                    ->dateTime('j M y, h:i a')
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->whereIn('status', ['Complete', 'Superseded'])->orderBy('updated_at', 'desc');
            })
            ->filters([
                //
            ])
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('download_res')
                        ->label('Download results')
                        ->action(function (Collection $records) {
                            $start = microtime(true);
                            $selectedSurveys = $records;

                            if ($selectedSurveys->isEmpty()) {
                                $emptyResponse = [
                                    'success' => true,
                                    'message' => 'No completed surveys found',
                                    'data' => [],
                                    'code' => 0,
                                    'locale' => 'en',
                                ];
                                return response()->json($emptyResponse);
                            }

                            $csvData = [];

                            foreach ($selectedSurveys as $userSurvey) {
                                $user = User::find($userSurvey->user_id);
                                $userData = [
                                    $user->full_name,
                                    $user->site->site_name,
                                ];
                                $csvData[] = $userData;
                                $csvData[] = ['Domain', 'Element', 'Indicator', 'Phase', 'Answer', 'Question Text', 'Answer Text'];

                                $surveyDomains = UserSurveyDomain::where('user_survey_id', $userSurvey->id)
                                    ->whereIn('status', ['Complete', 'Superseded'])
                                    ->get();

                                foreach ($surveyDomains as $surveyDomain) {
                                    $userAnswers = UserAnswer::where('user_survey_domain_id', $surveyDomain->id)->get();

                                    foreach ($userAnswers as $userAnswer) {
                                        $question = Question::find($userAnswer->question_id);

                                        if ($question && $question->question) {
                                            $csvData[] = [
                                                $surveyDomain->domain,
                                                strip_tags($question->element_print),
                                                $question->indicator_print,
                                                $question->phase_description,
                                                $userAnswer->answer == 1 ? 'Yes' : 'No',
                                                str_replace("\n", "-", strip_tags($question->question)),
                                                $userAnswer->answer_text,
                                            ];
                                        }
                                    }
                                }
                                $csvData[] = ['---', '---', '---', '---', '---', '---', '---'];
                            }
                            $fileName = Str::random(10);

                            $csvFileName = 'survey_' . $fileName . '.csv';
                            $filePath = storage_path('app/public/surveys/' . $csvFileName);

                            $file = fopen($filePath, 'w');
                            foreach ($csvData as $row) {
                                fputcsv($file, $row);
                            }

                            fclose($file);
                            $time_elapsed_secs = microtime(true) - $start;
                            Log::info('Time took for csv export function ' . $time_elapsed_secs);
                            return response()->download($filePath, $csvFileName)->deleteFileAfterSend(false);

                        })
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveyDownloads::route('/'),
            'create' => Pages\CreateSurveyDownload::route('/create'),
            'edit' => Pages\EditSurveyDownload::route('/{record}/edit'),
        ];
    }
}
