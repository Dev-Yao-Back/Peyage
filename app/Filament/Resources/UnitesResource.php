<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Unite;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\UnitesResource\Pages;
use App\Filament\Resources\UnitesResource\RelationManagers\ProduitRelationManager;

class UnitesResource extends Resource

{

    public static function getRelations(): array
{
    return [
        ProduitRelationManager::class,
    ];
}
    protected static ?string $model = Unite::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?string $activeNavigationIcon = 'heroicon-s-cursor-arrow-rays';


    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('nom')
                    ->label('Libellé')
                    ->required(),

                    TextInput::make('description')
                    ->label('Description')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->label('Libelle'),

                Tables\Columns\TextColumn::make('description')
                    ->label('description'),


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



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnites::route('/'),
            'create' => Pages\CreateUnites::route('/create'),
            'edit' => Pages\EditUnites::route('/{record}/edit'),
        ];
    }

}
