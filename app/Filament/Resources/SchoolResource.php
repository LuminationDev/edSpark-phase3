<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolResource\Pages;
use App\Filament\Resources\SchoolResource\RelationManagers;
use App\Models\School;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Closure;

use Illuminate\Support\Facades\Auth;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;
    protected static ?string $modelLabel= "School";


    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'School Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Card::make()
                //     ->schema([
                //         Forms\Components\TextInput::make('site_id'),
                //         Forms\Components\TextInput::make('owner_id'),
                //         // Forms\Components\TextInput::make('allowEditIds')
                //         //     ->maxLength(255),
                //         Forms\Components\TextInput::make('name')
                //             ->required()
                //             ->maxLength(255),
                //         Forms\Components\Textarea::make('content_blocks'),
                //         Forms\Components\TextInput::make('logo')
                //             ->maxLength(255),
                //         Forms\Components\TextInput::make('cover_image')
                //             ->maxLength(255),
                //         Forms\Components\Textarea::make('tech_used'),
                //         Forms\Components\Textarea::make('pedagogical_approaches'),
                //         Forms\Components\Textarea::make('tech_landscape'),
                //     ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('site_id'),
                // Tables\Columns\TextColumn::make('allowEditIds'),
                Tables\Columns\TextColumn::make('name'),
                // Tables\Columns\TextColumn::make('content_blocks')
                    // ->limit(20),
                // Tables\Columns\TextColumn::make('logo'),
                // Tables\Columns\TextColumn::make('cover_image'),
                // Tables\Columns\TextColumn::make('tech_used'),
                // Tables\Columns\TextColumn::make('pedagogical_approaches'),
                // Tables\Columns\TextColumn::make('tech_landscape'),
                Tables\Columns\TextColumn::make('owner.full_name')->label('Owner'),
                Tables\Columns\ToggleColumn::make('isFeatured'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSchools::route('/'),
            'create' => Pages\CreateSchool::route('/create'),
            'edit' => Pages\EditSchool::route('/{record}/edit'),
        ];
    }

    protected function getTableRecordActionUsing(): ?Closure
    {
        return null;
    }

    public static function shouldRegisterNavigation(): bool
    {
        $allowed_array = ['Superadmin', 'Administrator','Moderator'];
        if (in_array(Auth::user()->role->role_name, $allowed_array)) {
            return true;
        }
        return false;
    }
}
