<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChallengesResource\Pages;
use App\Filament\Resources\ChallengesResource\RelationManagers;
use App\Models\Challenge;
use App\Models\Challenges;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChallengesResource extends Resource
{
    protected static ?string $model = Challenge::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                TextColumn::make('id'),
                TextColumn::make('name'),
                TextColumn::make('description'),
                TextColumn::make('base_goal')->label('Objectif'),
                TextColumn::make('increment_goal')->label('Incrémentation'),
                TextColumn::make('trash.name')->label('Déchet'),
                TextColumn::make('type.name')->label('Type'),
                TextColumn::make('points')->label('Points')->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListChallenges::route('/'),
            'create' => Pages\CreateChallenges::route('/create'),
            'edit' => Pages\EditChallenges::route('/{record}/edit'),
        ];
    }
}
