<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Fournisseur;
use Filament\Resources\Resource;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FournisseurResource\Pages;
use App\Filament\Resources\FournisseurResource\RelationManagers;
use App\Filament\Resources\FournisseurResource\RelationManagers\OperationRelationManager;

class FournisseurResource extends Resource
{

    protected static ?string $model = Fournisseur::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
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
                        TextInput::make('nom_fournisseur')
                        ->required()
                        ->label('Nom'),

                        TextInput::make('numero_fournisseur')
                            ->required()
                            ->label('Code fournisseur')
                            ->default(mt_rand(1000, 9999))
                            //->default(strtoupper(Str::random(5))) // Générer le code par défaut
                            ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;']),

                        Textarea::make('adresse_fournisseur')
                            ->label('Adresse'),
                    ]),
                    Section::make([
                        TextInput::make('telephone_fournisseur')
                        ->tel()
                        ->label('Téléphone'),

                    TextInput::make('email_fournisseur')
                        ->email()
                        ->label('Email'),

                    TextInput::make('capacite_production_mensuelle')
                        ->label('Capacité de production mensuelle (tonnes)')

                        ->numeric()

                        ->nullable(),
                    ])
                ])->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom_fournisseur')
                ->sortable()
                ->searchable()
                ->label('Nom'),

            TextColumn::make('numero_fournisseur')
                ->sortable()
                ->searchable()
                ->label('Code Fournisseur'),

            TextColumn::make('adresse_fournisseur')
                ->sortable()
                ->searchable()
                ->label('Adresse'),

            TextColumn::make('telephone_fournisseur')
                ->sortable()
                ->searchable()
                ->label('Téléphone'),

            TextColumn::make('email_fournisseur')
                ->sortable()
                ->searchable()
                ->label('Email'),

            TextColumn::make('capacite_production_mensuelle')
                ->sortable()
                ->label('Capacité Production (tonnes)'),
            ])
            ->filters([

                Tables\Filters\Filter::make('Capacité de production')
                ->form([
                    Forms\Components\TextInput::make('capacite_production_min')
                        ->label('Min (tonnes)'),
                    Forms\Components\TextInput::make('capacite_production_max')
                        ->label('Max (tonnes)'),
                ])
                ->query(function (Builder $query, array $data) {
                    return $query
                        ->when($data['capacite_production_min'], fn ($query) => $query->where('capacite_production_mensuelle', '>=', $data['capacite_production_min']))
                        ->when($data['capacite_production_max'], fn ($query) => $query->where('capacite_production_mensuelle', '<=', $data['capacite_production_max']));
                }),

            Tables\Filters\SelectFilter::make('adresse_fournisseur')
                ->options(function () {
                    return Fournisseur::query()->pluck('adresse_fournisseur', 'adresse_fournisseur')->toArray();
                })
                ->label('Adresse'),

                TernaryFilter::make('telephone_fournisseur')
                ->label('Avec ou sans téléphone')
                ->queries(
                    true: fn (Builder $query): Builder => $query->whereNotNull('telephone_fournisseur'),
                    false: fn (Builder $query): Builder => $query->whereNull('telephone_fournisseur'),
                ),
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
            OperationRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFournisseurs::route('/'),
            'create' => Pages\CreateFournisseur::route('/create'),
            'edit' => Pages\EditFournisseur::route('/{record}/edit'),
        ];
    }
}