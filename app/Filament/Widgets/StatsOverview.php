<?php

namespace App\Filament\Widgets;

use App\Models\Campagne;
use App\Models\Destination;
use App\Models\Client;
use App\Models\Entreprise;
use App\Models\Fournisseur;
use App\Models\Operation;
use App\Models\Produit;
use App\Models\Provenance;
use App\Models\Transporteur;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Récupération des poids et conversion en kg
        $poids1Data = Operation::query()->pluck('poids1')->toArray();
        $poids2Data = Operation::query()->pluck('poids2')->toArray();
        $poidsNetData = Operation::query()->pluck('poidsnet')->toArray();

        $poids1Data = array_map(fn($poids) => floatval($poids), $poids1Data); // Conversion en kg
        $poids2Data = array_map(fn($poids) => floatval($poids), $poids2Data); // Conversion en kg
        $poidsNetData = array_map(fn($poidsNet) => floatval($poidsNet), $poidsNetData); // Conversion en kg

        // Totaux
        $totalPoids1 = array_sum($poids1Data);
        $totalPoids2 = array_sum($poids2Data);
        $totalPoidsNet = array_sum($poidsNetData);

        // Calcul du poids net par jour, semaine, ,mois et annee
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        $poidsNetToday = Operation::whereDate('created_at', $today)->sum('poidsnet'); // en kg
        $poidsNetThisWeek = Operation::where('created_at', '>=', $thisWeek)->sum('poidsnet'); // en kg
        $poidsNetThisMonth = Operation::where('created_at', '>=', $thisMonth)->sum('poidsnet'); // en kg
        $poidsNetThisYear = Operation::where('created_at', '>=', $thisYear)->sum('poidsnet'); // en kg

          // Calcul du nombre d'opérations pour les périodes
          $operationsToday = Operation::whereDate('created_at', $today)->count();
          $operationsThisWeek = Operation::where('created_at', '>=', $thisWeek)->count();
          $operationsThisYear = Operation::where('created_at', '>=', $thisYear)->count();



           // Par jour
        $dailyRevente = Produit::whereDate('created_at', Carbon::today())->sum('prix_unitaire_revente');
        $dailyPaid = Operation::whereDate('created_at', Carbon::today())->sum('montant_paye');
        $dailyChiffreAffaire = $dailyRevente - $dailyPaid;

        // Par semaine
        $weeklyRevente = Produit::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('prix_unitaire_revente');
        $weeklyPaid = Operation::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('montant_paye');
        $weeklyChiffreAffaire = $weeklyRevente - $weeklyPaid;

        // Par mois
        $monthlyRevente = Produit::whereMonth('created_at', Carbon::now()->month)->sum('prix_unitaire_revente');
        $monthlyPaid = Operation::whereMonth('created_at', Carbon::now()->month)->sum('montant_paye');
        $monthlyChiffreAffaire = $monthlyRevente - $monthlyPaid;

        // Par année
        $yearlyRevente = Produit::whereYear('created_at', Carbon::now()->year)->sum('prix_unitaire_revente');
        $yearlyPaid = Operation::whereYear('created_at', Carbon::now()->year)->sum('montant_paye');
        $yearlyChiffreAffaire = $yearlyRevente - $yearlyPaid;

        return [


  // Statistiques par jour
  Stat::make('Montant Total de Revente (Aujourd\'hui)', $dailyRevente)
  ->description('Montant total des reventes du jour')
  ->descriptionIcon('heroicon-o-currency-dollar')
  ->color('success'),

Stat::make('Montant Total Payé (Aujourd\'hui)', $dailyPaid)
  ->description('Montant total payé du jour')
  ->descriptionIcon('heroicon-o-currency-dollar')
  ->color('info'),

Stat::make('Chiffre d\'Affaire (Aujourd\'hui)', $dailyChiffreAffaire)
  ->description('Chiffre d\'affaire du jour')
  ->descriptionIcon('heroicon-o-chart-bar')
  ->color('warning'),

// Statistiques par semaine
Stat::make('Montant Total de Revente (Cette semaine)', $weeklyRevente)
  ->description('Montant total des reventes de la semaine')
  ->descriptionIcon('heroicon-o-currency-dollar')
  ->color('success'),

Stat::make('Montant Total Payé (Cette semaine)', $weeklyPaid)
  ->description('Montant total payé de la semaine')
  ->descriptionIcon('heroicon-o-currency-dollar')
  ->color('info'),

Stat::make('Chiffre d\'Affaire (Cette semaine)', $weeklyChiffreAffaire)
  ->description('Chiffre d\'affaire de la semaine')
  ->descriptionIcon('heroicon-o-chart-bar')
  ->color('warning'),

// Statistiques par mois
Stat::make('Montant Total de Revente (Ce mois-ci)', $monthlyRevente)
  ->description('Montant total des reventes du mois')
  ->descriptionIcon('heroicon-o-currency-dollar')
  ->color('success'),

Stat::make('Montant Total Payé (Ce mois-ci)', $monthlyPaid)
  ->description('Montant total payé du mois')
  ->descriptionIcon('heroicon-o-currency-dollar')
  ->color('info'),

Stat::make('Chiffre d\'Affaire (Ce mois-ci)', $monthlyChiffreAffaire)
  ->description('Chiffre d\'affaire du mois')
  ->descriptionIcon('heroicon-o-chart-bar')
  ->color('warning'),

// Statistiques par année
Stat::make('Montant Total de Revente (Cette année)', $yearlyRevente)
  ->description('Montant total des reventes de l\'année')
  ->descriptionIcon('heroicon-o-currency-dollar')
  ->color('success'),

Stat::make('Montant Total Payé (Cette année)', $yearlyPaid)
  ->description('Montant total payé de l\'année')
  ->descriptionIcon('heroicon-o-currency-dollar')
  ->color('info'),

Stat::make('Chiffre d\'Affaire (Cette année)', $yearlyChiffreAffaire)
  ->description('Chiffre d\'affaire de l\'année')
  ->descriptionIcon('heroicon-o-chart-bar')
  ->color('warning'),

//nombre de client



            // Statistiques des poids
            Stat::make('Poids Total 1 (en kg)', $totalPoids1)
                ->description('Poids total des opérations pour poids 1 (en kg)')
                ->descriptionIcon('heroicon-o-scale')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),

            Stat::make('Poids Total 2 (en kg)', $totalPoids2)
                ->description('Poids total des opérations pour poids 2 (en kg)')
                ->descriptionIcon('heroicon-o-scale')
                ->color('info'),

            Stat::make('Poids Total Net (en kg)', $totalPoidsNet)
                ->description('Poids net total des opérations (en kg)')
                ->descriptionIcon('heroicon-o-scale')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            // Bilan du poids net par jour, semaine et mois
            Stat::make('Poids Net Aujourd\'hui (en kg)', $poidsNetToday)
                ->description('Poids net total des opérations pour aujourd\'hui (en kg)')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary'),

            Stat::make('Poids Net Cette Semaine (en kg)', $poidsNetThisWeek)
                ->description('Poids net total des opérations pour cette semaine (en kg)')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('dan'),

            Stat::make('Poids Net Ce Mois (en kg)', $poidsNetThisMonth)
                ->description('Poids net total des opérations pour ce mois (en kg)')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('success'),

                  // Bilan du nombre d'opérations par jour, semaine et année
            Stat::make('Nombre d\'Opérations Aujourd\'hui', $operationsToday)
            ->description('Nombre total d\'opérations pour aujourd\'hui')
            ->descriptionIcon('heroicon-o-document')
            ->color('primary'),

        Stat::make('Nombre d\'Opérations Cette Semaine', $operationsThisWeek)
            ->description('Nombre total d\'opérations pour cette semaine')
            ->descriptionIcon('heroicon-o-document')
            ->color('info'),

        Stat::make('Nombre d\'Opérations Cette Année', $operationsThisYear)
            ->description('Nombre total d\'opérations pour cette année')
            ->descriptionIcon('heroicon-o-document')
            ->color('success'),

            // Autres statistiques déjà présentes
            Stat::make('Nombre des Campagnes', Campagne::query()->count())
                ->description('Total Campagnes')
                ->descriptionIcon('heroicon-o-computer-desktop')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('Nombre de Destinations', Destination::query()->count())
                ->description('Total Destinations')
                ->descriptionIcon('heroicon-o-chevron-double-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info'),

            Stat::make('Nombre d\'Entreprises', Entreprise::query()->count())
                ->description('Total Entreprises')
                ->descriptionIcon('heroicon-o-building-storefront')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),

                Stat::make('Nombre de Paysans', Client::query()->count())
                ->description('Totaal Paysans')
                ->descriptionIcon('heroicon-o-user')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),


            Stat::make('Nombre des Fournisseurs', Fournisseur::query()->count())
                ->description('Total Fournisseurs')
                ->descriptionIcon('heroicon-o-shopping-cart')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),

            Stat::make('Nombre des Opérations', Operation::query()->count())
                ->description('Total Opérations')
                ->descriptionIcon('heroicon-o-arrows-pointing-in')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),




            Stat::make('Nombre des Produits', Produit::query()->count())
                ->description('Total Produits')
                ->descriptionIcon('heroicon-o-square-3-stack-3d')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('Nombre des Provenances', Provenance::query()->count())
                ->description('Total Provenances')
                ->descriptionIcon('heroicon-o-chevron-double-down')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),

            Stat::make('Nombre des Chauffeurs', Transporteur::query()->count())
                ->description('Total Chauffeurs')
                ->descriptionIcon('heroicon-o-truck')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('Nombre des Utilisateurs', User::query()->count())
                ->description('Total Utilisateurs')
                ->descriptionIcon('heroicon-o-user-group')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),
        ];
    }
}