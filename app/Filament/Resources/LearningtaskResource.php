<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LearningtaskResource\Pages;

use App\Helpers\EdsparkFormComponents;
use App\Helpers\RoleHelpers;
use App\Helpers\StatusHelpers;
use App\Helpers\UserRole;
use App\Models\Advice;
use App\Models\Advicetype;
use App\Models\Label;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms;

class LearningtaskResource extends AdviceResource
{
    protected static ?string $model = Advice::class;
    protected static ?string $modelLabel = 'Learning Task';
    protected static ?string $navigationLabel = 'Learning Tasks';

    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';


    public static function form(Form $form): Form
    {
        $groupedLabels = Label::all()->groupBy('type');

        $labelColumns = [];
        // categories to include inside advice creation page
        $categoriesToInclude = ['category', 'learning', 'capability', 'year'];

        foreach ($groupedLabels as $category => $labels) {
            if (in_array($category, $categoriesToInclude)) {
                $labelColumns[] =
                    Forms\Components\Section::make(ucfirst($category))
                        ->schema([
                            Forms\Components\CheckboxList::make("labels")
                                ->label("")
                                ->extraAttributes(['class' => 'text-primary-600'])
                                ->options($labels->pluck('value', 'id')->toArray())
                                ->relationship('labels', 'value', function ($query) use ($category) {
                                    $query->where('type', $category);
                                })
                                ->columns(3)
                        ]);
            }
        }
        return $form->schema([
            Forms\Components\Card::make()->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('excerpt')
                    ->label('Tagline')
                    ->placeholder('150 characters or less')
                    ->maxLength(150),
                EdsparkFormComponents::createContentComponent('uploads/content/advice'),
                EdsparkFormComponents::createCoverImageComponent('uploads/advice', 'edSpark-advice-'),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('advice_type')
                            ->label('Advice type')
                            ->relationship('advice_types', 'advice_type_name')
                            ->required()
                            ->columns(3)
                            ->default(function () {
                                return Advicetype::where('advice_type_name', 'Learning Task')->first()->id;
                            }),
                        ...$labelColumns
                    ]),

                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Select::make('author_id')
                        ->relationship(name: 'author', titleAttribute: 'display_name')
                        ->disabled(fn() => !RoleHelpers::has_minimum_privilege(UserRole::MODERATOR))
                        ->required()
                        ->searchable(),
                    Forms\Components\Select::make('status')
                        ->options(StatusHelpers::getStatusList())
                        ->label('Status')
                        ->required(),
                ]),
                Forms\Components\TagsInput::make('tags')
                    ->placeholder('Add or create tags')
                    ->helperText('Press enter after each tag')

            ]),
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Builder::make('extra_content')
                        ->blocks([
                            Forms\Components\Builder\Block::make('templates')
                                ->schema([
                                    Forms\Components\Select::make('template')
                                        ->label('Choose a template')
                                        ->reactive()
                                        ->options(static::getTemplates()),
                                    ...static::getTemplateSchemas()
                                ]),

                        ])
                        ->label('Extra content')
                ])
        ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLearningtask::route('/'),
            'create' => Pages\CreateLearningtask::route('/create'),
            'edit' => Pages\EditLearningtask::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return Advice::whereHas('advice_types', function ($query) {
            $query->where('advice_type_name', 'Learning Task');
        });
    }


}