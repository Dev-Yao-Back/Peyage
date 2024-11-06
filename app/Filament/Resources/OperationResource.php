<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Client;
use App\Models\peseur;
use App\Models\Produit;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Operation;
use App\Models\Provenance;
use Filament\Tables\Table;
use App\Models\Destination;

use App\Models\Fournisseur;
use Illuminate\Support\Str;
use App\Models\Transporteur;
use Forms\Components\Button;
use App\Models\TypeOperation;
use App\Models\ClientOperateur;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Filters\TextFilter;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\OperationResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers\OperationRelationManager;

class OperationResource extends Resource
{
    protected static ?string $model = Operation::class;


    protected static ?string $navigationIcon = 'heroicon-o-arrows-pointing-in';
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
                    Wizard\Step::make('Information Planteur')
                    ->icon('heroicon-m-user-plus')
                    ->completedIcon('heroicon-m-hand-thumb-up')
                        ->schema([
                            Fieldset::make('Label')
                                ->schema([
                                    Forms\Components\Select::make('type_operation') // Utilisation de la clé étrangère
                                    ->label('Type d\'opération')
                                    ->options([
                                        'RECEPTION (Entrée)' => 'RECEPTION (Entrée)',
                                        'EXPEDITION (Sortie)' => 'EXPEDITION (Sortie)',
                                    ]) // Récupère les types depuis la base de données
                                    ->required()

                                     ->suffixIcon('heroicon-m-arrow-path-rounded-square')
                                     ->suffixIconColor('primary')
                                    ->default('RECEPTION (Entrée)')
                                    ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;'])
                                    ->placeholder('Sélectionnez un type d\'opération'),

                                Forms\Components\TextInput::make('code')
                                    ->required()
                                    ->suffixIcon('heroicon-m-wrench-screwdriver')
                                     ->suffixIconColor('primary')
                                    ->label('Code')
                                    ->default(mt_rand(10000, 99999))
                                    //->default(strtoupper(Str::random(5))) // Générer le code par défaut
                                    ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;']), // Style personnalisé

                                Forms\Components\TextInput::make('numero_vehcule') // Correction : 'numero_vehicule'
                                    ->required()
                                    ->placeholder('Matricule')
                                    ->mask(' AA-123-AA ')
                                    ->suffixIcon('heroicon-m-calculator')
                                    ->suffixIconColor('primary')
                                    ->label('Matricule du véhicule')
                                    ->extraAttributes(['style' => 'font-size: 48px; font-weight: bold;']), // Style personnalisé





                                    Forms\Components\Select::make('produit_id')
                                        ->relationship('produit', 'nom_produit') // 'produit' est le nom de la relation dans le modèle Operation
                                        ->label('Produit')
                                        ->required()
                                        ->suffixIcon('heroicon-m-square-3-stack-3d')
                                       ->placeholder('Selectionner la Produit')

                                        ->suffixIconColor('primary')
                                        ->searchable()
                                        ->preload()
                                        ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;'])
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                                            $produit = Produit::find($state);

                                            // Mise à jour des autres champs
                                            $set('description_produit', $produit?->description_produit ?? '');
                                            $set('prix_unitaire', $produit?->prix_unitaire ?? '');
                                            $set('unite', $produit?->unite?->nom ?? '');
                                        })
                                        ->required()

                                        ->createOptionForm([
                                            Fieldset::make('Enregistrer un nouveau Produit')
                                                ->schema([
                                                    Forms\Components\TextInput::make('nom_produit')
                                                        ->required()
                                                        ->label('Nom du produit'),
                                                    Forms\Components\TextInput::make('description_produit')
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
                                                        ->label('Prix unitaire de revente'),
                                                ])
                                                ->columns(4),
                                        ])

                                        ->required(),

                                    TextInput::make('prix_unitaire')
                                        ->label('Prix Par Kg')
                                        ->reactive()
                                        ->suffix('F FCA')
                                        ->disabled(),

                                        TextInput::make('unite')
                                        ->label('Unité de mesure')
                                        ->reactive()
                                        ->disabled(),



                                ])->columns(3),

                                static::getClientRepeater()

                        ]),

                    Wizard\Step::make(' Informations Opération')
                        ->icon('heroicon-m-bars-4')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->schema([
                            Fieldset::make('Label')
                                ->schema([
                                    Section::make('Transporteur')
                                        ->schema([
                                            Forms\Components\Select::make('transporteur_id')
                                                ->relationship('transporteur', 'nom_transporteur') // 'transporteur' est la relation dans le modèle Operation
                                                ->label('Transporteur')
                                                ->suffixIcon('heroicon-m-arrow-right-end-on-rectangle')
                                                ->placeholder('Selectionner le Tansporteur')
                                                 ->suffixIconColor('primary')
                                                ->required()
                                                ->searchable(['nom_transporteur', 'numero_transporteur'])

                                                ->preload()
                                               ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;'])
                                                ->reactive()
                                                ->afterStateUpdated(function ($state, callable $set) {
                                                    // Récupérer le transporteur sélectionné
                                                    $transporteur = \App\Models\Transporteur::find($state);

                                                    if ($transporteur) {
                                                        // Si le transporteur existe, mettre à jour les champs liés
                                                        $set('numero_transporteur', $transporteur->numero_transporteur);
                                                        $set('telephone_transporteur', $transporteur->telephone_transporteur);
                                                    } else {
                                                        // Si aucun transporteur sélectionné, réinitialiser les champs liés
                                                        $set('numero_transporteur', null);
                                                        $set('telephone_transporteur', null);
                                                    }
                                                })
                                                ->required()
                                                ->createOptionForm([
                                                    Split::make([
                                                        Section::make([
                                                            Forms\Components\TextInput::make('nom_transporteur')
                                                                ->required()
                                                                 ->placeholder('bala mona zembre')
                                                                ->suffixIcon('heroicon-m-user')

                                                                ->suffixIconColor('primary')
                                                                ->label('Nom Complet '),
                                                            Forms\Components\TextInput::make('numero_transporteur')
                                                                ->required()
                                                                ->suffixIcon('heroicon-m-key')
                                                                 ->suffixIconColor('primary')
                                                                ->default(mt_rand(10000, 99999))
                                                                ->label('Code  '),
                                                            Forms\Components\TextInput::make('telephone_transporteur')
                                                                ->required()
                                                                ->tel()
                                                                ->mask('99-99-99-99-99')
                                                                ->placeholder('09-99-99-99-99')
                                                                ->suffixIcon('heroicon-m-phone')
                                                                ->prefix('(+255)')
                                                                ->suffixIconColor('success')
                                                                ->label('Téléphone'),
                                                        ]),
                                                        Section::make([
                                                            Forms\Components\TextInput::make('email_transporeur')  // Correction : 'email_transporeur' -> 'email_transporteur'
                                                                ->email()
                                                                ->required()
                                                                ->suffixIcon('heroicon-m-envelope')
                                                                ->placeholder('bala@gmail.com')
                                                                 ->suffixIconColor('primary')
                                                                ->label('Email'),
                                                            Forms\Components\TextInput::make('adresse_transporteur')
                                                                ->required()
                                                                ->suffixIcon('heroicon-m-building-library')
                                                                ->placeholder("Lieu d'habitation")
                                                                 ->suffixIconColor('primary')
                                                                ->label('Adresse'),
                                                            Forms\Components\TextInput::make('type_transporteur')
                                                                ->required()
                                                                ->suffixIcon('heroicon-m-arrow-trending-down')
                                                                ->placeholder("National/International")
                                                                 ->suffixIconColor('primary')
                                                                ->label('Type de Transporteur'),
                                                        ]),
                                                    ])->columnSpan('full'),
                                                ])
                                                ->required(),

                                                Forms\Components\TextInput::make('numero_transporteur')

                                                ->label('Code Transporteur')
                                                // Vous pouvez ajouter une valeur par défaut aléatoire ici
                                                ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;']) // Style similaire
                                                ->reactive()
                                                ->suffixIcon('heroicon-m-key')

                                                 ->suffixIconColor('primary')
                                                ->disabled(),


                                            TextInput::make('telephone_transporteur')

                                                ->label('Téléphone ')  // Correction : 'Téléphone_transporteur' -> 'Téléphone du Transporteur'
                                                ->reactive()
                                                ->suffixIcon('heroicon-m-phone')
                                                 ->suffixIconColor('success')
                                                ->disabled(),




                                            Forms\Components\Select::make('provenance_id')
                                                ->relationship('provenance', 'nom_provenance') // 'provenance' est la relation dans le modèle Operation
                                                ->label('Provenance')
                                                ->searchable(['nom_provenance', 'numero_provenance'])
                                                ->suffixIcon('heroicon-m-chevron-double-down')
                                                ->placeholder("Selectionner la Provenance")
                                                ->suffixIconColor('primary')

                                                ->preload()
                                                ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;'])

                                                ->required()
                                                ->reactive()
                                                ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                    $provenance = Provenance::find($state);

                                                    $set('numero_provenance', $provenance?->numero_provenance ?? '');
                                                    $set('ville_provenance', $provenance?->ville_provenance ?? '');


                                                })
                                                ->required()

                                                ->createOptionForm([
                                                    Split::make([
                                                        Section::make([
                                                             Forms\Components\TextInput::make('nom_provenance')
                                                                ->required()
                                                                ->suffixIcon('heroicon-m-chevron-double-down')
                                                                ->placeholder("Dabou")
                                                                ->suffixIconColor('primary')
                                                                ->label('Nom de la Provenance'),
                                                            Forms\Components\TextInput::make('adresse_provenance')
                                                                ->required()
                                                                ->suffixIcon('heroicon-m-building-library')
                                                                ->placeholder("Lieu d'habitation")
                                                                 ->suffixIconColor('primary')
                                                                ->label('Adresse'),
                                                            Forms\Components\TextInput::make('numero_provenance')
                                                                ->required()
                                                                ->suffixIcon('heroicon-m-key')
                                                                ->suffixIconColor('primary')
                                                                ->default(mt_rand(10000, 99999))

                                                                ->label('code'),
                                                        ]),
                                                        Section::make([

                                                            Forms\Components\TextInput::make('region_provenance')
                                                                ->required()
                                                                ->suffixIcon('heroicon-m-building-office-2')
                                                                ->suffixIconColor('primary')
                                                                ->label('Région'),
                                                            Forms\Components\TextInput::make('ville_provenance')
                                                                ->required()
                                                                ->suffixIcon('heroicon-m-home-modern')
                                                                ->suffixIconColor('primary')
                                                                ->label('Ville'),
                                                        ]),
                                                    ])->columnSpan('full'),
                                                ])
                                                ->required(),

                                            // Champs autocomplétés et désactivés
                                           // Champs autocomplétés et désactivés
                                         TextInput::make('numero_provenance')

                                          ->label('Code Provenance')
                                          ->suffixIcon('heroicon-m-key')

                                         ->suffixIconColor('primary')

                                           ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;']) // Style personnalisé
                                            ->reactive()
                                              ->disabled(), // Désactivé car rempli automatiquement après sélection
                                           TextInput::make('ville_provenance')

                                            ->disabled()
                                            ->suffixIcon('heroicon-m-home-modern')
                                            ->suffixIconColor('primary')
                                            ->reactive()

                                            ->label('Ville'),


                                    // Disposition des champs sur 4 colonnes
                                    // Disposition des champs sur 4 colonnes


                                            Forms\Components\Select::make('fournisseur_id')
                                                ->relationship('fournisseur', 'nom_fournisseur') // Récupération des noms de fournisseurs avec leurs IDs
                                                ->label('Fournisseur')
                                                ->searchable(['nom_provenance', 'numero_provenance'])
                                                ->suffixIcon('heroicon-m-shopping-cart')
                                               ->suffixIconColor('primary')
                                                ->placeholder("Selectionner le Fournisseur")
                                                ->searchable(['nom_fournisseur', 'numero_fournisseur','telephone_fournisseur'])

                                                ->preload()
                                               ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;'])

                                                ->reactive()
                                                ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                    // Récupère le fournisseur sélectionné en fonction de l'ID
                                                    $fournisseur = Fournisseur::find($state);

                                                    // Mise à jour des autres champs après sélection du fournisseur
                                                    $set('numero_fournisseur', $fournisseur?->numero_fournisseur ?? '');

                                                    $set('telephone_fournisseur', $fournisseur?->telephone_fournisseur ?? '');


                                                })
                                                ->createOptionForm([
                                                    Split::make([
                                                        Section::make([
                                                            Forms\Components\TextInput::make('nom_fournisseur')
                                                                ->required()
                                                                ->suffixIcon('heroicon-m-shopping-cart')
                                                                ->suffixIconColor('primary')
                                                                 ->placeholder("Bala bele yant")
                                                                ->label('Nom Complet du Fournisseur'),
                                                            Forms\Components\TextInput::make('numero_fournisseur')
                                                                ->required()
                                                                ->suffixIcon('heroicon-m-key')
                                                                ->suffixIconColor('primary')
                                                                ->default(mt_rand(10000, 99999))

                                                                ->label('Code du Fournisseur'),
                                                            Forms\Components\TextInput::make('adresse_fournisseur')
                                                                ->required()
                                                                 ->suffixIcon('heroicon-m-building-library')
                                                                ->placeholder("Lieu d'habitation")
                                                                 ->suffixIconColor('primary')
                                                                ->label('Adresse'),
                                                        ]),
                                                        Section::make([

                                                            Forms\Components\TextInput::make('telephone_fournisseur')
                                                                ->required()
                                                                ->suffixIcon('heroicon-m-phone')
                                                                ->suffixIconColor('success')
                                                                ->label('Téléphone'),
                                                                  TextInput::make('email_fournisseur')
                                                                    ->required()
                                                                     ->suffixIcon('heroicon-m-envelope')
                                                                ->placeholder('bala@gmail.com')
                                                                 ->suffixIconColor('primary')

                                                                    ->label('Email'),


                                                                TextInput::make('capacite_production_mensuelle')
                                                                    ->required()
                                                                    ->suffixIcon('heroicon-m-numbered-list')
                                                                    ->placeholder('poids livré par mois')
                                                                     ->suffixIconColor('primary')
                                                                         ->numeric()
                                                                          ->step(10)
                                                                    ->label('Capacité de Production Mensuelle (tonnes)'),


                                                        ]),
                                                    ])->columnSpan('full'),
                                                ])
                                                ->required(),

                                            // Champs autocomplétés et désactivés
                                            TextInput::make('numero_fournisseur')

                                                ->label('Code')
                                                ->suffixIcon('heroicon-m-key')

                                                 ->suffixIconColor('primary')

                                                ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;']) // Style personnalisé
                                                 ->reactive()
                                                   ->disabled(), // Désactivé car rempli automatiquement après sélection



                                            TextInput::make('telephone_fournisseur')

                                                ->label('Téléphone')
                                                ->reactive()
                                                ->suffixIcon('heroicon-m-phone')
                                                 ->suffixIconColor('success')
                                                ->disabled(),




                                            Forms\Components\Select::make('peseur_id')
                                                ->relationship('peseur', 'nom') // 'destination' est la relation dans le modèle Operation
                                                ->label('Le peseur')
                                                ->suffixIcon('heroicon-m-user')
                                                ->suffixIconColor('primary')
                                                ->placeholder("Selectionner le Peseur")
                                                ->searchable(['nom', 'numero'])

                                                ->preload()
                                               ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;'])

                                                ->required()
                                                ->reactive()
                                                ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                    $peseur = peseur::find($state);

                                                    $set('numero', $peseur?->numero ?? '');
                                                    $set('telephone', $peseur?->telephone ?? '');


                                                })
                                                ->required()

                                                ->createOptionForm([
                                                    Split::make([
                                                        Section::make([
                                                              Forms\Components\TextInput::make('nom')
                                                                ->required()
                                                                ->suffixIcon('heroicon-m-user')
                                                                ->suffixIconColor('primary')
                                                                ->label('Nom '),
                                                            Forms\Components\TextInput::make('adresse')
                                                                ->required()
                                                                ->suffixIcon('heroicon-m-building-library')
                                                                ->placeholder("Lieu d'habitation")
                                                                 ->suffixIconColor('primary')
                                                                ->label('Adresse'),
                                                            Forms\Components\TextInput::make('numero')
                                                                ->required()
                                                                ->suffixIcon('heroicon-m-key')
                                                                 ->suffixIconColor('primary')
                                                                ->default(mt_rand(10000, 99999))

                                                                ->label('Code '),
                                                        ]),
                                                        Section::make([

                                                            Forms\Components\TextInput::make('email')  // Correction : 'email_transporeur' -> 'email_transporteur'
                                                                ->email()

                                                                ->suffixIcon('heroicon-m-envelope')
                                                                ->placeholder('bala@gmail.com')
                                                                 ->suffixIconColor('primary')
                                                                ->label('Email'),
                                                            Forms\Components\TextInput::make('telephone')
                                                                ->required()
                                                                ->tel()
                                                                ->mask('99-99-99-99-99')
                                                                ->placeholder('09-99-99-99-99')
                                                                ->suffixIcon('heroicon-m-phone')
                                                                ->prefix('(+255)')
                                                                ->suffixIconColor('success')
                                                                ->label('telephone'),
                                                            Forms\Components\TextInput::make('note')
                                                                ->required()
                                                                ->label('description'),
                                                        ]),
                                                    ])->columnSpan('full'),
                                                ])
                                                ->required(),

                                            TextInput::make('numero')

                                                ->label('Code')
                                                ->reactive()
                                                ->suffixIcon('heroicon-m-key')
                                                 ->suffixIconColor('primary')
                                                ->disabled(),
                                            TextInput::make('telephone')

                                                ->label('Téléphone')
                                                ->suffixIcon('heroicon-m-phone')
                                                 ->suffixIconColor('success')
                                                ->reactive()
                                                ->disabled(),// Désactivé car rempli automatiquement après sélection

                                         // Désactivé car rempli automatiquement après sélection
                                        ])->columns(3),
                                        ])
                                        ->columns(3),


                        ]),

                    // Étape pour le peseur
                    Wizard\Step::make('Pesée 1')
                         ->completedIcon('heroicon-m-hand-thumb-up')
                        ->icon('heroicon-m-scale')
                        ->schema([
                            Section::make('Recuperation')
                            ->headerActions([
                                Action::make('Capter le Poids')
                                    ->action(function ($get, $set) {
                                        // Récupère la valeur du champ `recuperation`
                                        $recuperationValue = $get('recuperation');

                                        // Vérifie que la valeur est définie avant de la mettre dans `poids1`
                                        if (!empty($recuperationValue)) {
                                            $set('poids1', $recuperationValue);

                                            // Définir la date et l'heure actuelles pour le premier poids
                                            $set('datepoids1', now()->format('Y-m-d'));
                                            $set('heurepoids1', now()->format('H:i'));
                                        }
                                    }),
                            ])
                            ->schema([
                                Forms\Components\TextInput::make('recuperation')
                                    ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;'])
                                    ->suffixIcon('heroicon-m-scale')
                                    ->placeholder("Poids du  Vehicule + le Produit ")
                                    ->suffixIconColor('primary')
                                    ->suffix('Recupération du Poids du Véhicule Plein en (Kg)')
                                    ->label('Poids 1')
                            ]),

                        Fieldset::make('Label')
                            ->schema([
                                Forms\Components\TextInput::make('poids1')
                                    ->required()
                                    ->label('Poids 1')
                                     ->suffixIcon('heroicon-m-scale')
                                    ->placeholder("Poids du  Vehicule + le Produit ")
                                    ->suffixIconColor('primary')
                                    ->suffix('Kg')
                                    ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;']),


                                Forms\Components\DatePicker::make('datepoids1')
                                    ->suffixIcon('heroicon-m-calendar-days')
                                    ->suffixIconColor('primary')

                                    ->label('Date du premier poids'),


                                Forms\Components\TimePicker::make('heurepoids1')
                                    ->suffixIcon('heroicon-m-clock')
                                    ->suffixIconColor('primary')

                                    ->label('Heure du premier poids')

                            ])
                            ->columns(3),

                            // Fieldset::make('Label')
                            //     ->schema([
                            //         Forms\Components\TextInput::make('poids2')
                            //             ->required()
                            //             ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;'])
                            //             ->suffix('Kg')
                            //             ->label('Poids 2')
                            //             ->reactive()
                            //             ->afterStateUpdated(function ($state, Forms\Set $set, $get) {
                            //                 if (! empty($state)) {
                                                // Définir automatiquement 'datepoids2' si non défini
                                                // if (empty($get('datepoids2'))) {
                                                //     $set('datepoids2', now()->format('Y-m-d'));
                                                // }
                                                // // Définir automatiquement 'heurepoids2' si non défini
                                                // if (empty($get('heurepoids2'))) {
                                                //     $set('heurepoids2', now()->format('H:i'));
                                                // }
                                                // Calculer 'poidsnet' si 'poids1' est défini
                                //                 if (! empty($get('poids1'))) {
                                //                     $set('poidsnet', $get('poids1') - $state);
                                //                 }
                                //             }
                                //         }),
                                //     Forms\Components\DatePicker::make('datepoids2')
                                //         ->label('Date du second poids'),
                                //     Forms\Components\TimePicker::make('heurepoids2')
                                //         ->label('Heure du second poids')
                                // ])
                                // ->columns(3),

                            // Fieldset::make('Label')
                            //     ->schema([
                            //         Forms\Components\TextInput::make('poidsnet')
                            //             ->required()
                            //             ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;'])
                            //             ->suffix('Kilogramme (Kg)')
                            //             ->label('Poids net en Kilogramme'),
                            //        Forms\Components\TextInput::make('montant_paye')
                            //             ->required()
                            //             ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;'])
                            //             ->suffix('Montant a payer')
                            //             ->label('montant')
                            //     ])
                            //     ->columns(3),
                        ]),
                ])->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                SelectFilter::make('transporteur_id')
                ->relationship('transporteur', 'nom_transporteur')
                ->label('Transporteur'),

                SelectFilter::make('fournisseur_id')
                    ->relationship('fournisseur', 'nom_fournisseur')
                    ->label('Fournisseur'),

                SelectFilter::make('produit_id')
                    ->relationship('produit', 'nom_produit')
                    ->label('Produit'),

                SelectFilter::make('peseur_id')
                    ->relationship('peseur', 'nom')
                    ->label('Peseur'),

                SelectFilter::make('provenance_id')
                    ->relationship('provenance', 'nom_provenance')
                    ->label('Provenance'),



                 Filter::make('recent_operations')
                ->label('Opérations De la Semaine')
                ->query(function ($query) {
                    return $query->where('created_at', '>=', now()->subDays(7));
                })
                ->toggle()
                ->default(false), // Facultatif : active le filtre par défaut

                //  Filter::make('recent_operations')
                // ->label('Opérations Du Mois')
                // ->query(function ($query) {
                //     return $query->where('created_at', '>=', now()->subDays(30));
                // })
                // ->toggle()
                // ->default(true),

            ])
            ->actions([



                Tables\Actions\Action::make('Imprimer')
                    ->label('Imprimer')

                    // Ajouter la confirmation avant d'exécuter l'action
                    ->requiresConfirmation()

                    // Personnaliser l'apparence et le contenu du modal
                    ->modalHeading('Détails de l\'Opération')



                    // Afficher la vue personnalisée avec les détails de l'opération dans le modal
                    ->modalContent(function (Operation $record) {
                        return view('operation_pdf', ['operation' => $record]);
                    })

                    // Ajuster la largeur du modal
                    ->modalWidth('lg')
                  // Masquer si le statut est 'Vehicule En attente'

                    // Lorsque l'utilisateur confirme, l'action d'impression se déclenche
                    ->action(function (Operation $record) {
                        $pdf = new \Dompdf\Dompdf;

                        // Charger la vue avec les données de l'opération
                        $html = view('operation_pdf', ['operation' => $record])->render();

                        $pdf->loadHtml($html);
                        $pdf->setPaper('A4', 'portrait');
                        $pdf->render();

                        // Envoyer le PDF au navigateur
                        return response()->stream(function () use ($pdf) {
                            echo $pdf->output();
                        }, 200, [
                            'Content-Type' => 'application/pdf',
                            'Content-Disposition' => 'attachment; filename="fiche_operation_'.$record->id.'.pdf"',
                        ]);
                    })
                    ->icon('heroicon-o-printer')
                    ->hidden(fn (Operation $record) => $record->statut !== 'Operation Complète'),



                    Tables\Actions\Action::make('Pesée 2')
                    ->label('Pesée 2') // Étiquette du bouton
                    ->modalHeading('Détails de la Deuxième Pesée')
                    ->mountUsing(function ($form, $record) {
                        // Charger les données dans le formulaire à partir du record
                        $form->fill([
                            'nom_fournisseur' => $record->fournisseur->nom_fournisseur,
                            'numero_fournisseur' => $record->fournisseur->numero_fournisseur,

                            'nom_produit' => $record->produit->nom_produit,
                            'prix_unitaire' => $record->produit->prix_unitaire,



                            'client' => $record->client_operateur->first()?->nom ?? 'Nom non disponible',

                            //'numero_client' => $record->client->numero_client,

                            'nom_transporteur' => $record->transporteur->nom_transporteur,
                            'numero_transporteur' => $record->transporteur->numero_transporteur,

                            'numero_provenance' => $record->provenance->numero_provenance,
                            'nom_provenance' => $record->provenance->nom_provenance,

                             'numero_vehcule' => $record->numero_vehcule,
                             'code' => $record->code,
                             'poids1' => $record->poids1,
                             'heurepoids1' => $record->heurepoids1,

                           // Ajoutez cette ligne pour le code du véhicule
                        ]);


                    })

                    ->form([

                        Fieldset::make('Information Operation 1')

                        ->schema([
                            Forms\Components\TextInput::make('numero_vehcule')
                            ->label('Code du Véhicule')
                            ->suffixIcon('heroicon-m-calculator')
                            ->suffixIconColor('primary')
                            ->disabled(),
                            Forms\Components\TextInput::make('code ')
                            ->label('Code Operation')
                            ->suffixIcon('heroicon-m-wrench-screwdriver')
                            ->suffixIconColor('primary')
                            ->disabled(),
                            Forms\Components\TextInput::make('poids1')
                            ->label('Poids 1')
                            ->suffix('(Kg)')
                             ->suffixIcon('heroicon-m-scale')
                            ->suffixIconColor('primary')

                            ->disabled(),
                            Forms\Components\TextInput::make('heurepoids1')
                            ->suffixIcon('heroicon-m-clock')

                            ->suffixIconColor('primary')
                            ->label(' Heure de Pesée Poids 1')

                            ->disabled(),

                        ]) ->columns(4),
                        Fieldset::make('Information Pesée 1 ')

                        ->schema([
                         Forms\Components\TextInput::make('nom_produit')
                            ->label('Produit')
                            ->suffixIconColor('primary')
                            ->suffixIcon('heroicon-m-square-3-stack-3d')
                            ->disabled(),
                         Forms\Components\TextInput::make('prix_unitaire')
                            ->label(' Prix ')
                            ->disabled(),
                        Forms\Components\TextInput::make('nom_fournisseur')
                            ->label(' Nom Fournisseur')
                            ->suffixIcon('heroicon-m-shopping-cart')
                            ->suffixIconColor('primary')
                            ->disabled(), // Lecture seule
                       Forms\Components\TextInput::make('numero_fournisseur')
                            ->label(' Code Fournisseur')
                            ->suffixIcon('heroicon-m-key')
                            ->suffixIconColor('primary')
                            ->disabled(), // Lecture seule
                        Forms\Components\TextInput::make('nom_provenance')
                            ->label('Provenance')
                            ->suffixIcon('heroicon-m-arrow-right-end-on-rectangle')
                             ->suffixIconColor('primary')
                            ->disabled(),

                        Forms\Components\TextInput::make('numero_provenance')
                            ->label(' Code Provenance')
                            ->suffixIcon('heroicon-m-key')
                            ->suffixIconColor('primary')
                            ->disabled(),
                        Forms\Components\TextInput::make('nom_transporteur')
                            ->label(' Nom Transporteur')
                            ->suffixIcon('heroicon-m-arrow-right-end-on-rectangle')
                             ->placeholder('Selectionner le Tansporteur')
                            ->suffixIconColor('primary')
                            ->disabled(),

                        Forms\Components\TextInput::make('numero_transporteur')
                            ->label('Code Transporteur')
                             ->suffixIcon('heroicon-m-key')
                            ->suffixIconColor('primary')
                            ->disabled(),

                        ]) ->columns(4),
                        Section::make('Recuperation')
                        ->headerActions([
                            Action::make('Capter le Poids')
                            ->action(function ($get, $set) {
                                // Récupère la valeur du champ `recuperation`
                                $recuperationValue = $get('recuperation');

                                if (!empty($recuperationValue)) {
                                    // Définit la valeur de `poids2`
                                    $set('poids2', $recuperationValue);

                                    // Définit la date et l'heure actuelles
                                    $set('datepoids2', now()->format('Y-m-d'));
                                    $set('heurepoids2', now()->format('H:i'));

                                    // Récupère `poids1` et effectue le calcul du poids net
                                    $poids1 = $get('poids1');
                                    if (!empty($poids1)) {
                                        $poidsnet = $poids1 - $recuperationValue;
                                        $set('poidsnet', $poidsnet);
                                        $set('poidsnet_tonne', $poidsnet / 1000);

                                        // Calcul du montant en fonction du prix unitaire
                                        $prix_unitaire = $get('prix_unitaire');
                                        if (!empty($prix_unitaire)) {
                                            $set('montant_paye', $poidsnet * $prix_unitaire);
                                        }
                                    }
                                }
                            }),
                        ])
                        ->schema([
                            Forms\Components\TextInput::make('recuperation')
                                ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;'])
                                 ->suffixIcon('heroicon-m-scale')

                                 ->suffixIconColor('primary')
                                ->suffix('Recupération du Poids du Véhicule vide en (Kg)')
                                ->label('Poids 2')
                        ]),

                    Fieldset::make('Label')
                        ->schema([
                            Forms\Components\TextInput::make('poids2')
                                ->required()
                                ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;'])
                                ->suffix('Kg')
                                ->suffixIcon('heroicon-m-scale')

                                ->suffixIconColor('primary')
                                ->label('Poids 2')
                                ->reactive(),


                            Forms\Components\DatePicker::make('datepoids2')
                              ->reactive()
                               ->suffixIcon('heroicon-m-calendar-days')
                                ->suffixIconColor('primary')
                                ->label('Date du second poids'),

                            Forms\Components\TimePicker::make('heurepoids2')
                              ->reactive()
                              ->suffixIcon('heroicon-m-clock')
                              ->suffixIconColor('primary')
                                ->label('Heure du second poids'),

                            TextInput::make('poidsnet')
                                ->required()
                                ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;'])
                                ->suffix(' (Kg)')
                                 ->suffixIcon('heroicon-m-scale')

                                 ->suffixIconColor('primary')
                                ->label('Poids net en Kilogrammes')
                                ->reactive(),

                            TextInput::make('poidsnet_tonne')
                                ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;'])
                                ->suffix('Tonnes')
                                 ->suffixIcon('heroicon-m-scale')

                                ->suffixIconColor('primary')
                                ->label('Poids net en Tonnes')
                                ->disabled()
                                ->reactive(),

                            Forms\Components\TextInput::make('montant_paye')
                                ->required()
                                ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;'])
                                ->suffix('F FCA')
                                ->label('Montant')
                                ->reactive()
                        ])
                        ->columns(3),




                        // Ajoutez d'autres champs de formulaire si nécessaire
                    ])
                    ->action(function (Operation $record, array $data): void {

                        // Logique pour gérer la mise à jour de l'auteur
                        // Exemple: $data contient les données du formulaire
                        $record->update([
                            'deuxieme_pesee' => $data['deuxieme_pesee'] ?? null,
                            'poids2' => $data['poids2'] ?? null,
                            'datepoids2' => $data['datepoids2'] ?? null,
                            'heurepoids2' => $data['heurepoids2'] ?? null,
                            'poidsnet' => $data['poidsnet'] ?? null,
                            'montant_paye' => $data['montant_paye'] ?? null,
                            'statut' => 'Operation Complète',


                        ]);



                    })
                    ->slideOver()// Ouvre le formulaire dans un slide-over
                    ->hidden(fn (Operation $record) => $record->statut !== 'En Attente')

                    ->icon('heroicon-o-scale'),






      ActionGroup::make([
        Tables\Actions\ViewAction::make(),

        Tables\Actions\DeleteAction::make(),
      ]),




            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\Action::make('Télécharger en masse (PDF)')
                        ->label('Télécharger plusieurs opérations')
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
            'index' => Pages\ListOperations::route('/'),
            'create' => Pages\CreateOperation::route('/create'),
            'edit' => Pages\EditOperation::route('/{record}/edit'),
        ];

        return $table
        ->defaultGroup('statut');


    }



    public static function getClientRepeater(): Repeater
    {
        return Repeater::make('Client_Operateur')
            ->relationship()
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->label('Planteur')
                    ->options(Client::query()->pluck('nom_client', 'id'))
                    ->required()
                     ->suffixIcon('heroicon-m-user-group')
                    ->suffixIconColor('primary')
                    ->placeholder("Selectionner le Client")
                    ->reactive()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $client = Client::find($state);

                        if ($client) {
                            $set('numero_client', $client->numero_client);
                            $set('telephone_client', $client->telephone_client);


                        } else {
                            $set('numero_client', null);
                            $set('telephone_client', null);

                        }
                    })
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan(4)
                    ->searchable(['nom_client', 'numero_client'])

                    ->createOptionForm([
                        Split::make([
                            Section::make([
                                TextInput::make('nom_client')
                                ->required()
                                   ->suffixIcon('heroicon-m-user-group')
                                ->suffixIconColor('primary')
                                ->placeholder("bala bele yant")
                                ->label('Nom Complet'),

                                TextInput::make('numero_client')
                                    ->required()
                                    ->default(mt_rand(10000, 99999))
                                     ->suffixIcon('heroicon-m-key')
                                    ->suffixIconColor('primary')
                                    ->label('Code Client'),

                                TextInput::make('adresse_client')
                                    ->label('Adresse')
                                    ->suffixIcon('heroicon-m-home-modern')
                                    ->placeholder("Lieu d'habitation")
                                     ->suffixIconColor('primary'),
                            ]),
                            Section::make([
                                TextInput::make('telephone_client')
                                 ->tel()
                                ->mask('99-99-99-99-99')
                                ->placeholder('09-99-99-99-99')
                                ->suffixIcon('heroicon-m-phone')
                                ->prefix('(+255)')
                                ->suffixIconColor('success')
                                ->label('Téléphone'),

                            TextInput::make('email_client')
                                ->email()
                                  ->suffixIcon('heroicon-m-envelope')
                                  ->placeholder('bala@gmail.com')
                                  ->suffixIconColor('primary')
                                ->label('Email'),
                            Textarea::make('note_client')

                                ->label('description'),

                            ])
                        ])->columnSpan('full')
                    ])
                    ->createOptionUsing(function (array $data) {
                        // Logique pour créer un nouveau client dans la base de données
                        $client = Client::create([
                            'nom_client' => $data['nom_client'],
                            'numero_client' => $data['numero_client'],
                            'note_client' => $data['note_client'],

                            'adresse_client' => $data['adresse_client'],
                            'telephone_client' => $data['telephone_client'],

                            'email_client' => $data['email_client'],
                        ]);

                        return $client->id; // Retourne l'ID du nouveau client créé
                    }),


                Forms\Components\TextInput::make('numero_client')
                    ->label('Code Planteur')
                    ->disabled()
                     ->suffixIcon('heroicon-m-key')
                    ->suffixIconColor('primary')
                    ->columnSpan(4),

                Forms\Components\TextInput::make('telephone_client')
                    ->label('Telephone')
                    ->disabled()
                     ->suffixIcon('heroicon-m-phone')
                    ->prefix('(+255)')
                    ->suffixIconColor('success')
                    ->columnSpan(4),


            ])
            ->extraItemActions([
                \Filament\Forms\Components\Actions\Action::make('openClient')
                    ->tooltip('Ouvrir Planetur Selectionné')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(function (array $arguments, Repeater $component): ?string {
                        $itemData = $component->getRawItemState($arguments['item']);

                        $client = Client::find($itemData['client_id']);

                        if (! $client) {
                            return null;
                        }

                        return ClientResource::getUrl('edit', ['record' => $client]);
                    }, shouldOpenInNewTab: true)
                    ->hidden(fn (array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['client_id'])),
            ])
            ->defaultItems(1)
            ->hiddenLabel()
            ->columns(12)
            ->columnSpan('full')
            ->required();
    }











}