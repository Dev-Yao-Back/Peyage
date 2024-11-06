<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Unite;
use App\Models\Produit;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProduitResource\Pages;
use App\Filament\Resources\UnitesResource\RelationManagers\ProduitRelationManager;

class ProduitResource extends Resource
{


    protected static ?string $model = Produit::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
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

    public static function table(Table $table): Table
    {
        return $table
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
                    // ->money('€'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->label('Date de création')
                //     ->sortable()
                //     ->date(),


            ])
            ->filters([
                Filter::make('Prix inférieur à 50 €')
                    ->query(fn (Builder $query): Builder => $query->where('prix_unitaire', '<', 50))
                    ->toggle(),

                Filter::make('Avec Description')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('description_produit'))
                    ->toggle(),

                    SelectFilter::make('unite_id')
                    ->label('Filtrer par unité')
                    ->options(function () {
                        return \App\Models\Unite::query()->pluck('nom', 'id')->toArray();


                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('Imprimer')
                    ->label('Imprimer Fiche')
                    ->action(function (Produit $record) {
                        $pdf = new \Dompdf\Dompdf;

                        // Charger la vue avec les données du produit
                        $html = view('produit_pdf', ['produit' => $record])->render();

                        $pdf->loadHtml($html);
                        $pdf->setPaper('A4', 'portrait');
                        $pdf->render();

                        // Envoyer le PDF au navigateur
                        return response()->stream(function () use ($pdf) {
                            echo $pdf->output();
                        }, 200, [
                            'Content-Type' => 'application/pdf',
                            'Content-Disposition' => 'attachment; filename="fiche_produit_'.$record->id.'.pdf"',
                        ]);
                    })
                    ->icon('heroicon-o-printer'),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\Action::make('Télécharger en masse')
                        ->label('Télécharger Produits (PDF)')
                        ->action(function (array $records) {
                            // Logique pour télécharger plusieurs produits sélectionnés en PDF
                        })
                        ->icon('heroicon-o-arrow-down-tray'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProduitRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProduits::route('/'),
            'create' => Pages\CreateProduit::route('/create'),
            'edit' => Pages\EditProduit::route('/{record}/edit'),
        ];
    }
}