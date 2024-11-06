<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Campagne;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CampagneResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CampagneResource\RelationManagers;

class CampagneResource extends Resource
{
    protected static ?string $model = Campagne::class;
    protected static ?string $activeNavigationIcon = 'heroicon-s-cursor-arrow-rays';
    

    public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make([
                        Forms\Components\TextInput::make('nom')
                        ->required()
                            ->label('Nom de la campagne'),
                        Forms\Components\Textarea::make('description')
                            ->label('Description de la campagne'),
                        Forms\Components\DatePicker::make('date_debut')
                            ->required()
                            ->label('Date de début'),
                    ]),
                    Section::make([
                        Forms\Components\DatePicker::make('date_fin')
                        ->label('Date de fin'),
                    Forms\Components\TextInput::make('reduction')
                        ->numeric()
                        ->maxValue(100)
                        ->label('Pourcentage de réduction'),
                    Forms\Components\Select::make('entreprise_id')
                        ->relationship('entreprise', 'nom')
                        ->required()
                        ->label('Entreprise'),
                    ])
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
            Tables\Columns\TextColumn::make('description')
                ->sortable()
                ->limit(50),
            Tables\Columns\TextColumn::make('date_debut')
                ->sortable()
                ->date(),
            Tables\Columns\TextColumn::make('date_fin')
                ->sortable()
                ->date(),
            Tables\Columns\TextColumn::make('reduction')
                ->sortable()
                ->label('Réduction (%)'),
            Tables\Columns\TextColumn::make('entreprise.nom')
                ->label('Entreprise'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Créée le')
                ->sortable()
                ->date(),
            ])
            ->filters([
                SelectFilter::make('entreprise_id')
                    ->label('Entreprise')
                    ->relationship('entreprise', 'nom'),

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
            'index' => Pages\ListCampagnes::route('/'),
            'create' => Pages\CreateCampagne::route('/create'),
            'edit' => Pages\EditCampagne::route('/{record}/edit'),
        ];
    }
}