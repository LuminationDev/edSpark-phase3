<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolmoderationResource\Pages;
use App\Filament\Resources\SchoolmoderationResource\RelationManagers;
use App\Models\Schoolmoderation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class SchoolmoderationResource extends Resource
{
    protected static ?string $model = Schoolmoderation::class;
    protected static ?string $modelLabel = 'School Moderation';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Moderation';
    protected static ?string $navigationLabel = 'School Moderation';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('School Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->options([
                                'Published' => 'Published',
                                'Unpublished' => 'Unpublished',
                                'Draft' => 'Draft',
                                'Pending' => 'Pending'
                            ])
                            ->label('Status')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('site_id'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('owner.full_name')->label('Owner'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('preview')
                    ->url(fn ( $record): string => 'http://localhost:8000/schools/'. $record->name . '?preview=true')
                    ->openUrlInNewTab()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSchoolmoderations::route('/'),
            'create' => Pages\CreateSchoolmoderation::route('/create'),
            'edit' => Pages\EditSchoolmoderation::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', 'Pending');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()->where('status', 'pending')->count();
        if ($count > 0) {
            return $count;
        } else {
            return '';
        }
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
