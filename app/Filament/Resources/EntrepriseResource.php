<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Entreprise;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EntrepriseResource\Pages;
use App\Filament\Resources\EntrepriseResource\RelationManagers;

class EntrepriseResource extends Resource
{
    protected static ?string $model = Entreprise::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $activeNavigationIcon = 'heroicon-s-cursor-arrow-rays';
    


    public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Information Entreprise')
                    ->icon('heroicon-m-building-library')
                    ->completedIcon('heroicon-m-hand-thumb-up')
                        ->schema([

                            Split::make([
                                Section::make([
                                    Forms\Components\TextInput::make('nom')
                                        ->required()
                                        ->label('Nom de l\'entreprise'),
                                    Forms\Components\TextInput::make('adresse')
                                        ->label('Adresse'),
                                    Forms\Components\FileUpload::make('logo')
                                        ->label('Logo de l\'entreprise'),
                                    Forms\Components\TextInput::make('ville')
                                        ->label('Ville'),

                                ]),
                                Section::make([
                                    Forms\Components\TextInput::make('region')
                                        ->label('Région'),
                                    Forms\Components\TextInput::make('telephone')
                                        ->tel()
                                        ->label('Téléphone'),
                                    Forms\Components\TextInput::make('email')
                                        ->email()
                                        ->label('Email'),
                                    Forms\Components\TextInput::make('site_web')
                                        ->url()
                                        ->label('Site Web'),
                                    Forms\Components\DatePicker::make('date_creation')
                                        ->label('Date de création'),

                                ])
                            ])
                            ->columnSpan('full'),
                            Fieldset::make('Role')
                            ->schema([
                                Section::make([
                                    Forms\Components\TextInput::make('nombre_voies')
                                    ->label('Nombre de voies'),
                                    Forms\Components\TextInput::make('horaires_ouverture')
                                        ->label('Horaires d\'ouverture'),
                                    Forms\Components\TextInput::make('responsable_gestion')
                                        ->label('Responsable de la gestion'),
                                    Forms\Components\Textarea::make('types_paiement_acceptes')

                                        ->label('Types de paiement acceptés'),

                                ])
                                ->columns(4),
                            ]),

                        // Information de l'Arbitre
                        Fieldset::make('Information de l\'Arbitre')
                                // Afficher uniquement si le rôle est Arbitre // Show only for Arbitre

                            ->schema([
                                Forms\Components\TextInput::make('entretien_maintenance')
                                    ->label('Société d\'entretien'),
                                Forms\Components\TextInput::make('statut_juridique')
                                    ->label('Statut juridique'),
                                Forms\Components\TextInput::make('coordonnees_gps')
                                    ->label('Coordonnées GPS'),
                                Forms\Components\TextInput::make('capacite')
                                        ->label('Capacité journalière (véhicules/jour)'),
                            ])
                            ->columns(4),

                        ])
                        ->columns(2),





                ])->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('ville')
                    ->sortable()
                    ->label('Ville'),
                Tables\Columns\TextColumn::make('region')
                    ->sortable()
                    ->label('Région'),
                Tables\Columns\TextColumn::make('capacite')
                    ->sortable()
                    ->label('Capacité (véhicules/jour)'),
                Tables\Columns\TextColumn::make('nombre_voies')
                    ->label('Nombre de voies'),
                Tables\Columns\TextColumn::make('horaires_ouverture')
                    ->label('Horaires d\'ouverture'),
                Tables\Columns\TextColumn::make('responsable_gestion')
                    ->label('Responsable de la gestion'),
                Tables\Columns\TextColumn::make('statut_juridique')
                    ->label('Statut juridique'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créée le')
                    ->sortable()
                    ->date(),
            ])
            ->filters([
                SelectFilter::make('region')
                ->label('Filtrer par Région')
                ->options(function () {
                    return Entreprise::query()->pluck('region', 'region')->toArray();
                }),

            SelectFilter::make('ville')
                ->label('Filtrer par Ville')
                ->options(function () {
                    return Entreprise::query()->pluck('ville', 'ville')->toArray();
                }),

            Filter::make('Créée récemment')
                ->query(fn (Builder $query): Builder => $query->whereDate('created_at', '>', now()->subDays(30)))
                ->label('Créée il y a moins de 30 jours')
                ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Voir Détails')
                ->label('Voir Détails')
                ->action(function (Entreprise $record) {
                    // Logique pour afficher les détails de l'entreprise
                })
                ->icon('heroicon-o-eye'),

            Tables\Actions\Action::make('Télécharger PDF')
                ->label('Télécharger (PDF)')
                ->action(function (Entreprise $record) {
                    // Logique pour télécharger un PDF contenant les informations de l'entreprise
                })
                ->icon('heroicon-o-arrow-down-tray'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\Action::make('Télécharger en masse (PDF)')
                    ->label('Télécharger plusieurs entreprises')
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
            'index' => Pages\ListEntreprises::route('/'),
            'create' => Pages\CreateEntreprise::route('/create'),
            'edit' => Pages\EditEntreprise::route('/{record}/edit'),
        ];
    }
}