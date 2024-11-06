<?php

namespace App\Filament\Resources\TransporteurResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Operation;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class OperationRelationManager extends RelationManager
{
    protected static string $relationship = 'operation';
    public function canCreate(): bool
    {
        return false; // Empêche l'affichage du bouton de création
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                Tables\Columns\TextColumn::make('type_operation')
                ->sortable()
                ->color('gray')
                ->icon('heroicon-s-cursor-arrow-ripple')

                ->size('lg')
                ->toggleable(isToggledHiddenByDefault: true)

                ->weight('bold')
                ->icon('heroicon-s-arrow-path-rounded-square')
                ->label('Type d\'opération'),


                Tables\Columns\BadgeColumn::make('statut')
                ->label('Status')
                ->colors([
                    'success' => 'Operation Complète',
                    'danger' => 'En Attente',
                ])

                ->searchable()

                ->size('lg')
                ->weight('bold')
                ->icon('heroicon-s-cursor-arrow-ripple')

                ->formatStateUsing(function ($state) {
                    return match ($state) {
                        'Operation Complète' => 'Operation Complète',
                        'En Attente' => 'En Attente',
                        default => 'Unknown',
                    };
                }),



            Tables\Columns\TextColumn::make('numero_vehcule')
                     ->icon('heroicon-s-truck')
                  //->iconPosition('after')
                  ->size('lg')
                 // ->fontFamily('Helvetica')
                  ->weight('bold')
                  //->color('success')
                ->toggleable(isToggledHiddenByDefault: true)
                ->label('Numéro du véhicule'),
            Tables\Columns\TextColumn::make('code')
                     ->icon('heroicon-s-wrench-screwdriver')
               ->weight('bold')
                ->searchable(),
            Tables\Columns\TextColumn::make('poidsnet')

                ->suffix('    Kg')
                ->color('success')
                ->size('lg')
                ->weight('bold')
                ->icon('heroicon-s-scale')



                ->label('Poids net'),
             Tables\Columns\TextColumn::make('poids1')

                ->suffix('    Kg')

                ->color('gray')
                ->size('lg')
                ->weight('bold')
                ->icon('heroicon-s-scale')


                ->toggleable(isToggledHiddenByDefault: true)

                ->label('Poids 1'),
            Tables\Columns\TextColumn::make('datepoids1')
                ->date()
                //->since()
                ->icon('heroicon-s-calendar-days')

                ->toggleable(isToggledHiddenByDefault: true)

                ->label(' date Poids 1'),
            Tables\Columns\TextColumn::make('heurepoids1')
                ->icon('heroicon-s-clock')
                ->toggleable(isToggledHiddenByDefault: true)
                ->label(' Heure Poids 1'),
            Tables\Columns\TextColumn::make('poids2')

                ->toggleable(isToggledHiddenByDefault: true)
                ->icon('heroicon-s-scale')
                 ->color('primary')
                ->size('lg')
                ->weight('bold')
                ->suffix('    Kg')
                ->label('Poids 2'),
            Tables\Columns\TextColumn::make('datepoids2')

                ->icon('heroicon-s-calendar-days')

                ->toggleable(isToggledHiddenByDefault: true)

                ->label(' date Poids 2'),
            Tables\Columns\TextColumn::make('heurepoids2')

                ->toggleable(isToggledHiddenByDefault: true)
                ->icon('heroicon-s-clock')
                ->label(' Heure Poids 2'),



            Tables\Columns\TextColumn::make('transporteur.nom_transporteur')
                ->toggleable(isToggledHiddenByDefault: true)
                ->icon('heroicon-s-arrow-right-end-on-rectangle')
                ->weight('bold')
                ->label(' Nom Transporteur'),

            Tables\Columns\TextColumn::make('transporteur.numero_transporteur')
                ->toggleable(isToggledHiddenByDefault: true)
                ->icon('heroicon-s-key')
                ->weight('bold')
                ->label(' Code Transporteur'),
            Tables\Columns\TextColumn::make('transporteur.telephone_transporteur')
                ->toggleable(isToggledHiddenByDefault: true)
                ->icon('heroicon-s-phone-arrow-up-right')
                ->weight('bold')
                ->label(' Telephone Transporteur'),

            Tables\Columns\TextColumn::make('fournisseur.nom_fournisseur')
                ->icon('heroicon-s-shopping-cart')
                ->weight('bold')
                ->toggleable(isToggledHiddenByDefault: true)

                ->label('Fournisseur'),

                Tables\Columns\TextColumn::make('fournisseur.numero_fournisseur')
                ->icon('heroicon-s-key')
                ->toggleable(isToggledHiddenByDefault: true)

                ->weight('bold')
                ->label('Code Fournisseur'),

            Tables\Columns\TextColumn::make('fournisseur.telephone_fournisseur')
                ->icon('heroicon-s-phone-arrow-up-right')
                ->toggleable(isToggledHiddenByDefault: true)


                ->weight('bold')
                ->label('Telephone Fournisseur'),

            Tables\Columns\TextColumn::make('produit.nom_produit')
               ->icon('heroicon-s-square-3-stack-3d')
                ->weight('bold')
                ->toggleable(isToggledHiddenByDefault: true)

                ->label('Produit'),

            Tables\Columns\TextColumn::make('produit.prix_unitaire')
               ->icon('heroicon-s-currency-euro')
                ->weight('bold')
                ->toggleable(isToggledHiddenByDefault: true)

                ->suffix(' F FCA')
                ->label(' Prix Unitaire Produit'),
            Tables\Columns\TextColumn::make('provenance.nom_provenance')
                 ->icon('heroicon-s-chevron-double-down')
                ->weight('bold')
                ->toggleable(isToggledHiddenByDefault: true)

                ->label('Provenance'),

            Tables\Columns\TextColumn::make('provenance.numero_provenance')
                ->icon('heroicon-s-key')
                ->toggleable(isToggledHiddenByDefault: true)

               ->weight('bold')
               ->label('Provenance'),



            Tables\Columns\TextColumn::make('client_operateur.client.nom_client')
                ->icon('heroicon-s-user-group')
               ->weight('bold')
               ->toggleable(isToggledHiddenByDefault: true)
                ->label('Planteurs Associés '),

            Tables\Columns\TextColumn::make('client_operateur.client.numero_client')
                ->icon('heroicon-s-key')
                ->toggleable(isToggledHiddenByDefault: true)

               ->weight('bold')
                ->label(' Code Planteurs Associés '),

            Tables\Columns\TextColumn::make('client_operateur.client.telephone_client')
            ->icon('heroicon-s-phone-arrow-up-right')

               ->weight('bold')
               ->toggleable(isToggledHiddenByDefault: true)

                ->label(' Telephone Planteurs Associés '),

            Tables\Columns\TextColumn::make('peseur.nom')
                ->icon('heroicon-s-users')
               ->weight('bold')
               ->toggleable(isToggledHiddenByDefault: true)
                ->label(' Nom Peseur'),

            Tables\Columns\TextColumn::make('peseur.numero')
                ->icon('heroicon-s-key')
               ->weight('bold')
               ->toggleable(isToggledHiddenByDefault: true)
                ->label(' Code Peseur'),
             Tables\Columns\TextColumn::make('peseur.telephone')
             ->icon('heroicon-s-phone-arrow-up-right')

               ->weight('bold')
               ->toggleable(isToggledHiddenByDefault: true)
                ->label(' Telephone Peseur'),


            Tables\Columns\TextColumn::make('montant_paye')
                ->toggleable(isToggledHiddenByDefault: true)
                ->icon('heroicon-s-currency-euro')
                ->color('info')
               ->size('lg')
               ->weight('bold')
               ->suffix('    F CFA')
                ->label('Montant à Payé'),
            ])
            ->heading('Liste des Opération Effectuées ')
            ->description("Fiche d'Opération")
            ->emptyStateHeading("Pas d'Opération enregistrer")
            ->emptyStateDescription("Dommage, Pas enocre d'Opération pour votre Université")
            ->emptyStateIcon('heroicon-o-finger-print')
            // ->query(Operation::query()->orderBy('created_at', 'desc'))
             ->query(Operation::query()
            ->orderBy('statut') // Trier par statut
            ->orderBy('updated_at', 'desc'))

            ->defaultGroup('statut')
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
