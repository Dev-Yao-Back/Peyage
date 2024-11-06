<?php

namespace App\Filament\Resources\UnitesResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ProduitRelationManager extends RelationManager
{
    protected static string $relationship = 'produit';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Label')
                    ->schema([
                        Forms\Components\TextInput::make('nom_produit')
                            ->required()
                            ->label('Nom du produit'),
                        Forms\Components\Textarea::make('description_produit')
                            ->label('Description'),

                      Forms\Components\Select::make('unite_id')
                        ->relationship('unite', 'nom')
                        ->required()
                        ->label('Unite de mesure'),
                        Forms\Components\TextInput::make('prix_unitaire')
                            ->required()
                            ->numeric()
                            ->label('Prix unitaire'),
                        Forms\Components\TextInput::make('prix_unitaire_revente')
                            ->required()
                            ->numeric()
                            ->label('Prix_unitaire_revente'),


                    ])
                    ->columns(4),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nom')
            ->columns([
                Tables\Columns\TextColumn::make('nom_produit')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description_produit')
                    ->sortable()
                    ->limit(50),

                    Tables\Columns\TextColumn::make('unite.nom')
                    ->label('Unité de mesure'),
                Tables\Columns\TextColumn::make('prix_unitaire')
                    ->sortable()
                    ->label('Prix Unitaire'),
                    // ->money('€'),
                    Tables\Columns\TextColumn::make('prix_unitaire_revente')
                    ->sortable()
                    ->label('Prix Unitaire Revente'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}