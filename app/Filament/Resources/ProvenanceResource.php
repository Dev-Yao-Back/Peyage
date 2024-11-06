<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Provenance;
use Filament\Tables\Table;
use App\Models\Destination;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProvenanceResource\Pages;
use App\Filament\Resources\ProvenanceResource\RelationManagers\OperationRelationManager;

class ProvenanceResource extends Resource
{
    protected static ?string $model = Provenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-chevron-double-down';
    protected static ?string $activeNavigationIcon = 'heroicon-s-cursor-arrow-rays';


    public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Label')
                    ->schema([
                        Forms\Components\TextInput::make('nom_provenance')
                            ->required()
                            ->label('Nom de la Destination'),

                            Forms\Components\TextInput::make('numero_provenance')
                            ->required()
                            ->label('code provenance')
                            ->default(mt_rand(1000, 9999)) // Vous pouvez ajouter une valeur par défaut aléatoire ici
                            ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;']) // Style similaire
                            ->reactive(),

                        Forms\Components\TextInput::make('adresse_provenance')
                            ->required()
                            ->label('Adresse'),
                        Forms\Components\TextInput::make('region_provenance')
                            ->required()
                            ->label('Région'),
                        Forms\Components\TextInput::make('ville_provenance')
                            ->required()
                            ->label('Ville'),
                    ])
                    ->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom_provenance')
                    ->sortable()
                    ->searchable(),
                    Tables\Columns\TextColumn::make('numero_provenance')
                    ->sortable()
                    ->searchable()
                    ->label('Code provenance'),


                Tables\Columns\TextColumn::make('adresse_provenance')
                    ->sortable()
                    ->searchable()
                    ->label('Adresse'),
                Tables\Columns\TextColumn::make('region_provenance')
                    ->sortable()
                    ->label('Région'),
                Tables\Columns\TextColumn::make('ville_provenance')
                    ->sortable()
                    ->label('Ville'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date de création')
                    ->sortable()
                    ->date(),
            ])
            ->filters([

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
            OperationRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProvenances::route('/'),
            'create' => Pages\CreateProvenance::route('/create'),
            'edit' => Pages\EditProvenance::route('/{record}/edit'),
        ];
    }
}