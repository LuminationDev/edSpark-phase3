<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteResource\Pages;
use App\Filament\Resources\QuoteResource\RelationManagers;
use App\Models\Quote;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Classroom Catalogue';

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
                Tables\Columns\TextColumn::make('user.full_name')->label('User'),
                Tables\Columns\TextColumn::make('version.version')->label('Version'),
                Tables\Columns\TextColumn::make('quote_ref')->label('Quote Reference'),
                Tables\Columns\TextColumn::make('total_price_ex_gst')->label('Total Price (ex GST)')->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Status')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->sortable()->dateTime(),            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('download')
                    ->url(fn ($record): string => $record->pdf_url)
                 ->openUrlInNewTab()
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->orderBy('created_at', 'DESC');
            })
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
            'index' => Pages\ListQuotes::route('/'),
            'create' => Pages\CreateQuote::route('/create'),
            'edit' => Pages\EditQuote::route('/{record}/edit'),
        ];
    }
}
