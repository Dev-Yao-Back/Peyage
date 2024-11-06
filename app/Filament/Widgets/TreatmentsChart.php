<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Produit;
use Carbon\Carbon;
class TreatmentsChart extends ChartWidget
{
    protected static ?string $heading = 'Statistique des Produits Pesés';
    protected function getData(): array
    {
        // Initialiser un tableau pour les totaux mensuels
        $monthlyWeights = array_fill(0, 12, 0); // Tableau pour les 12 mois

        // Récupérer tous les produits avec leurs poids
        $produits = Produit::query()->get(); // Assurez-vous que les colonnes existent

        // Parcourir les produits et additionner les poids par mois
        foreach ($produits as $produit) {
            $month = Carbon::parse($produit->created_at)->month - 1; // Mois (0-11)
            $poids1 = floatval($produit->poids1) / 1000; // Conversion en kg
            $poids2 = floatval($produit->poids2) / 1000; // Conversion en kg
            $monthlyWeights[$month] += ($poids1 + $poids2); // Additionner les poids
        }

        return [
            'datasets' => [
                [
                    'label' => 'Poids des Produits (en kg)',
                    'data' => $monthlyWeights,
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
