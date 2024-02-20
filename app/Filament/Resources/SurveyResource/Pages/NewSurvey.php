<?php

namespace App\Filament\Resources\SurveyResource\Pages;

use App\Filament\Resources\SurveyResource;
use App\Models\Question;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class NewSurvey extends CreateRecord
{
    protected static string $resource = SurveyResource::class;
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['is_active'] = false;
        return parent::mutateFormDataBeforeCreate($data);
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Create the main record.
        $record = parent::handleRecordCreation($data);
        $savedQuestions = [];
        try {
            // we assume the json_file is valid by this point.
            $contents = $data['json_file']->get();
            $json = json_decode($contents, true);
            foreach ($json['questions'] as &$jsonQuestion) {

                $dbQuestion = new Question;
                // foreign key mapping
                $dbQuestion->survey_id = $record['version'];

                // common
                $dbQuestion->domain = $jsonQuestion['domain'];
                $dbQuestion->element = $jsonQuestion['element'];
                $dbQuestion->generated_variable = $jsonQuestion['generated_variable'];
                $dbQuestion->question = $jsonQuestion['question'];

                if ($jsonQuestion['domain'] == 'triage') {
                    // always set phase to 1 for a triage question
                    $dbQuestion->phase = 1;
                } else {

                    if (empty($jsonQuestion['question']) && empty($jsonQuestion['question_example'])) {
                        // phase needs to be manually set
                        if (empty($jsonQuestion['element_description'])) {
                            $dbQuestion->phase = -1;
                        } else {
                            $dbQuestion->phase = 0;
                        }
                    } else {
                        $dbQuestion->phase = $jsonQuestion['phase'];
                    }
                    $dbQuestion->domain_print = $jsonQuestion['domain_print'];
                    $dbQuestion->advice = $jsonQuestion['advice'];
                    $dbQuestion->description = $jsonQuestion['description'];
                    $dbQuestion->question_example = $jsonQuestion['question_example'];
                    $dbQuestion->variable_suffix = $jsonQuestion['variable_suffix'];
                    $dbQuestion->dependencies = $jsonQuestion['dependencies'];
                    $dbQuestion->phase_description = $jsonQuestion['phase_description'];
                    $dbQuestion->indicator = $jsonQuestion['indicator'];
                    $dbQuestion->indicator_print = $jsonQuestion['indicator_print'];
                    $dbQuestion->element_print = $jsonQuestion['element_print'];
                    $dbQuestion->element_description = $jsonQuestion['element_description'];
                }

                $dbQuestion->save();
                $savedQuestions[] = $dbQuestion;
            }
        } catch (\Exception $e) {
            Log::error('Error saving question: ' . $jsonQuestion);
            Log::error($e);
            // something went wrong -> clean up
            $record->delete();
            foreach ($savedQuestions as $question) {
                $question->deleteQuietly();
            }
            Notification::make()
                ->warning()
                ->title('Error saving questions for survey')
                ->body('Invalid survey question: ' . $jsonQuestion)
                ->send();
            $this->halt();
        }
        return $record;
    }
}
