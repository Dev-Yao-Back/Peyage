<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transporteur;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransporteurResource\Pages;
use App\Filament\Resources\TransporteurResource\RelationManagers;
use App\Filament\Resources\TransporteurResource\RelationManagers\OperationRelationManager;

class TransporteurResource extends Resource
{
    protected static ?string $model = Transporteur::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-end-on-rectangle';
    protected static ?string $activeNavigationIcon = 'heroicon-s-cursor-arrow-rays';


    public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make([
                        Forms\Components\TextInput::make('nom_transporteur')
                            ->required()
                            ->label('Nom du Transporteur'),
                        Forms\Components\TextInput::make('numero_transporteur')
                            ->required()
                            ->label('Code Transporteur')
                             ->default(mt_rand(1000, 9999)) // Vous pouvez ajouter une valeur par défaut aléatoire ici
                            ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;']) // Style similaire
                            ->reactive(),


                        Forms\Components\TextInput::make('telephone_transporteur')
                            ->required()
                            ->label('Téléphone'),
                    ]),
                    Section::make([
                        Forms\Components\TextInput::make('email_transporteur')
                        ->email()
                        ->required()
                        ->label('Email'),
                        Forms\Components\TextInput::make('adresse_transporteur')
                            ->required()
                            ->label('Adresse'),
                        Forms\Components\TextInput::make('type_transporteur')
                            ->required()
                            ->label('Type de Transporteur'),
                    ])
                ])->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom_transporteur')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('numero_transporteur')
                ->sortable()
                ->label('code Transporteur')
                ->searchable(),
            Tables\Columns\TextColumn::make('telephone_transporteur')
                ->sortable()
                ->label('Téléphone'),
            Tables\Columns\TextColumn::make('email_transporteur')
                ->label('Email'),
            Tables\Columns\TextColumn::make('adresse_transporteur')
                ->label('Adresse'),
            Tables\Columns\TextColumn::make('type_transporteur')
                ->label('Type de Transporteur'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Date de création')
                ->sortable()
                ->date(),
            ])
            ->filters([
                SelectFilter::make('type_transporteur')
                ->label('Filtrer par Type de Transporteur')
                ->options(function () {
                    return Transporteur::query()->pluck('type_transporteur', 'type_transporteur')->toArray();
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
                    ->action(function (Transporteur $record) {
                        // Logique pour afficher les détails du transporteur
                    })
                    ->icon('heroicon-o-eye'),

                Tables\Actions\Action::make('Télécharger PDF')
                    ->label('Télécharger (PDF)')
                    ->action(function (Transporteur $record) {
                        // Logique pour télécharger un PDF contenant les informations du transporteur
                    })
                    ->icon('heroicon-o-arrow-down-tray'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\Action::make('Télécharger en masse (PDF)')
                    ->label('Télécharger plusieurs transporteurs')
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
            'index' => Pages\ListTransporteurs::route('/'),
            'create' => Pages\CreateTransporteur::route('/create'),
            'edit' => Pages\EditTransporteur::route('/{record}/edit'),
        ];
    }
}