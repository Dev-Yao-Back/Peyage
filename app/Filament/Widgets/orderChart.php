<?php

namespace App\Filament\Widgets;
use Carbon\Carbon;
use App\Models\Operation;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;

use Filament\Widgets\ChartWidget;
class OrderChart extends ChartWidget
{
    protected static ?string $heading = 'Statistiques du Chiffre d\'Affaires';

    protected function getData(): array
    {
        // Chiffre d'affaires par jour
        $chiffreAffaireParJour = Operation::select(
            DB::raw('DATE(operations.created_at) as date'), // Ajout du préfixe `operations`
            DB::raw('SUM(produits.prix_unitaire_revente * operations.poidsnet) as total') // Ajout des préfixes pour chaque table
        )
        ->join('produits', 'operations.produit_id', '=', 'produits.id')
        ->groupBy('date')
        ->whereDate('operations.created_at', '>=', Carbon::now()->subDays(30)) // Ajout du préfixe `operations`
        ->pluck('total', 'date')
        ->toArray();


        // Chiffre d'affaires par semaine
        // Chiffre d'affaires par semaine
$chiffreAffaireParSemaine = Operation::select(
    DB::raw('YEARWEEK(operations.created_at) as week'), // Ajout du préfixe `operations`
    DB::raw('SUM(produits.prix_unitaire_revente * operations.poidsnet) as total') // Préfixes pour `produits` et `operations`
)
->join('produits', 'operations.produit_id', '=', 'produits.id')
->groupBy('week')
->whereDate('operations.created_at', '>=', Carbon::now()->subWeeks(8)) // Préfixe pour `operations`
->pluck('total', 'week')
->toArray();

// Chiffre d'affaires par mois
$chiffreAffaireParMois = Operation::select(
    DB::raw('MONTH(operations.created_at) as month'), // Ajout du préfixe `operations`
    DB::raw('SUM(produits.prix_unitaire_revente * operations.poidsnet) as total') // Préfixes pour `produits` et `operations`
)
->join('produits', 'operations.produit_id', '=', 'produits.id')
->groupBy('month')
->whereYear('operations.created_at', Carbon::now()->year) // Préfixe pour `operations`
->pluck('total', 'month')
->toArray();

// Chiffre d'affaires par année
$chiffreAffaireParAnnee = Operation::select(
    DB::raw('YEAR(operations.created_at) as year'), // Ajout du préfixe `operations`
    DB::raw('SUM(produits.prix_unitaire_revente * operations.poidsnet) as total') // Préfixes pour `produits` et `operations`
)
->join('produits', 'operations.produit_id', '=', 'produits.id')
->groupBy('year')
->pluck('total', 'year')
->toArray();


        return [
            'labels' => ['Chiffre d\'Affaires par Jour', 'Chiffre d\'Affaires par Semaine', 'Chiffre d\'Affaires par Mois', 'Chiffre d\'Affaires par Année'],
            'datasets' => [
                [
                    'label' => 'Chiffre d\'Affaires',
                    'data' => [
                        array_sum($chiffreAffaireParJour),
                        array_sum($chiffreAffaireParSemaine),
                        array_sum($chiffreAffaireParMois),
                        array_sum($chiffreAffaireParAnnee)
                    ],
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // Retourne le type du graphique (doughnut)
    }
}
