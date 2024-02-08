<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedbackResource\Pages;
use App\Filament\Resources\FeedbackResource\RelationManagers;
use App\Helpers\RoleHelpers;
use App\Models\Question;
use App\Models\Survey;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'DMA Tool';
    protected static ?string $modelLabel = 'Version';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\FileUpload::make('json_file')
                        ->acceptedFileTypes(['application/json'])
                        ->storeFiles(false)
                        ->rules([
                            fn(): Closure => function (string $attribute, $value, Closure $fail) {
                                $errmsg = self::validateFile($value);
                                if ($errmsg !== null) {
                                    $fail($errmsg);
                                }
                            },
                        ])
                        ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('version')
                    ->label('Version')
                    ->limit(25)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Is Active')
                    ->disabled(fn($record) => $record->is_active)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime()
                    ->label('Created at'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime()
                    ->label('Last Updated'),
            ])
            ->defaultSort('version', 'desc');
//            ->actions([
//                Tables\Actions\EditAction::make(),
//            ])
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
            'index' => Pages\ListSurveys::route('/'),
            'create' => Pages\NewSurvey::route('/create'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return RoleHelpers::has_minimum_privilege('moderator');
    }



    /**
     *
     * Validate that an inputted JSON file is a valid survey.
     * @param TemporaryUploadedFile $file
     * @return string|null: error message or null for valid file
     */
    private static function validateFile(TemporaryUploadedFile $file): ?string
    {
        try {
            $contents = $file->get();
            if ($contents === null) {
                // invalid file
                return 'File is invalid';
            }
            if (!json_validate($contents)) {
                // invalid json
                return 'JSON is invalid';
            }

            $json = json_decode($contents, true);
            if ($json === null) {
                // no json in file
                return 'No JSON present';
            }

            if (!array_key_exists('questions', $json) || !is_array($json['questions'])) {
                // expected an array
                return 'Expected an object containing a \'questions\' array ';
            }
            if (count($json['questions']) === 0) {
                return 'Survey is empty - questions are required';
            }

            //validate questions
            foreach ($json['questions'] as &$question) {
                $invalid = Question::isValidQuestion($question);
                if($invalid != null)
                {
                    return $invalid;
                }
            }

            unset($question);

        } catch (\Exception $e) { /*ignore and fail*/
            Log::error($e);
            return 'Exception parsing file: ' . $e->getMessage();
        }
        return null;
    }

}
