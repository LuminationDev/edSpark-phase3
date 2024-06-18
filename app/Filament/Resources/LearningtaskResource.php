<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LearningtaskResource\Pages;

use App\Helpers\StatusHelpers;
use App\Models\Advice;
use Illuminate\Database\Eloquent\Builder;

class LearningtaskResource extends AdviceResource {
    protected static ?string $model = Advice::class;
    protected static ?string $modelLabel = 'Learning Tasks';
    protected static ?string $navigationLabel = 'Learning Tasks';

    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLearningtask::route('/'),

        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return Advice::whereHas('advice_types', function($query) {
            $query->where('advice_type_name', 'Learning Task');
        });
    }


}