<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Destination;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DestinationResource\Pages;
use App\Filament\Resources\DestinationResource\RelationManagers;

class DestinationResource extends Resource
{
    protected static ?string $model = Destination::class;

    protected static ?string $navigationIcon = 'heroicon-o-chevron-double-up';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Label')
                    ->schema([

                        Forms\Components\TextInput::make('numero_destination')
                        ->required()
                        ->label('code destination')
                        ->default(mt_rand(1000, 9999))
                        //->default(strtoupper(Str::random(5))) // Générer le code par défaut
                        ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;']),
                        Forms\Components\TextInput::make('nom_destination')
                        ->required()
                        ->label('Nom de la Destination'),
                        Forms\Components\TextInput::make('adresse_destination')
                            ->required()
                            ->label('Adresse'),
                        Forms\Components\TextInput::make('region_destination')
                            ->required()
                            ->label('Région'),
                        Forms\Components\TextInput::make('ville_destination')
                            ->required()
                            ->label('Ville'),
                    ])
                    ->columns(4)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero_destination')
                ->sortable()
                ->label('Code Destination'),
                Tables\Columns\TextColumn::make('nom_destination')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('adresse_destination')
                    ->sortable()
                    ->searchable()
                    ->label('Adresse'),
                Tables\Columns\TextColumn::make('region_destination')
                    ->sortable()
                    ->label('Région'),
                Tables\Columns\TextColumn::make('ville_destination')
                    ->sortable()
                    ->label('Ville'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date de création')
                    ->sortable()
                    ->date(),
            ])
            ->filters([
                SelectFilter::make('region')
                ->label('Filtrer par Région')
                ->options(function () {
                    return Destination::query()->pluck('region_destination', 'region_destination')->toArray();
                }),

            SelectFilter::make('ville_destination')
                ->label('Filtrer par Ville')
                ->options(function () {
                    return Destination::query()->pluck('ville_destination', 'ville_destination')->toArray();
                }),

            Filter::make('Créé après')
                ->query(fn (Builder $query): Builder => $query->whereDate('created_at', '>', now()->subDays(30)))
                ->label('Créé il y a moins de 30 jours')
                ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Afficher Détails')
                ->label('Voir Détails')
                ->action(function (Destination $record) {
                    // Logique pour afficher les détails de la destination
                })
                ->icon('heroicon-o-eye'),

            Tables\Actions\Action::make('Télécharger PDF')
                ->label('Télécharger (PDF)')
                ->action(function (Destination $record) {
                    // Logique pour télécharger un PDF contenant les informations de la destination
                })
                ->icon('heroicon-o-arrow-down-tray'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                     Tables\Actions\Action::make('Télécharger en masse (PDF)')
                    ->label('Télécharger plusieurs destinations')
                    ->action(function (array $records) {
                        // Logique pour télécharger plusieurs fiches en PDF
                    })
                    ->icon('heroicon-o-arrow-down-tray'),
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
            'index' => Pages\ListDestinations::route('/'),
            'create' => Pages\CreateDestination::route('/create'),
            'edit' => Pages\EditDestination::route('/{record}/edit'),
        ];
    }
}