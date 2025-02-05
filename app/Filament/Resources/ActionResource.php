<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActionResource\Pages;
use App\Filament\Resources\ActionResource\RelationManagers;
use App\Models\Action;
use App\Models\Challenge;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Components\Tab;

class ActionResource extends Resource
{
    protected static ?string $model = Action::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('status')
                    ->options([
                        'accepted' => 'Accepter',
                        'pending' => 'En Cours',
                        'refused' => 'Annuler',
                    ])
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Utilisateur')
                    ->required(),
                Select::make('trash_id')
                    ->relationship('trash', 'name')
                    ->label('Déchet')
                    ->required(),
                Select::make('challenge_id')
                    ->label('Défi')
                    ->options(Challenge::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->required(),
                // Forms\Components\BelongsToSelect::make('challenge_id')
                //     ->relationship('challenge', 'points')
                //     ->required(),
                Forms\Components\FileUpload::make('image_action')
                    ->image()
                    ->maxSize(1024),
                Forms\Components\FileUpload::make('image_throw')
                    ->image()
                    ->maxSize(1024),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('status'),
                TextColumn::make('user.name')->label('Utilisateur'),
                TextColumn::make('trash.name')->label('Déchet'),
                TextColumn::make('quantity')->label('Quantité'),
                TextColumn::make('challenge.points')->label('Points')->badge(),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'accepted' => 'success',
                        'pending' => 'warning',
                        'refused' => 'danger',
                    }),
                ImageColumn::make('image_action')
                    ->disk('public')
                    ->visibility('public')
                    ->height(100),
                ImageColumn::make('image_throw')
                    ->disk('public')
                    ->visibility('public')
                    ->height(100),
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
            'index' => Pages\ListActions::route('/'),
            'create' => Pages\CreateAction::route('/create'),
            'edit' => Pages\EditAction::route('/{record}/edit'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'En cours' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'pending')),
            'Validé' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', "accepted")),
        ];
    }
}
