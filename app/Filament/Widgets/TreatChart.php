<?php

namespace App\Filament\Widgets;
use App\Models\Operation;
use Filament\Widgets\StatsOverviewWidget\Stat;


use Filament\Widgets\ChartWidget;

class TreatChart extends ChartWidget
{
    protected static ?string $heading = 'Statistique des Opérations ';

    protected function getData(): array
    {
          // Récupérer le nombre d'opérations pour chaque mois
    $operationsPerMonth = Operation::query()
    ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
    ->groupBy('month')
    ->orderBy('month')
    ->pluck('count', 'month')
    ->toArray();

// Créer un tableau avec les 12 mois, même si aucune opération n'a été effectuée
$monthlyOperations = [];
for ($i = 1; $i <= 12; $i++) {
    $monthlyOperations[] = $operationsPerMonth[$i] ?? 0;  // Ajouter 0 s'il n'y a pas de données pour ce mois
}

return [
    Stat::make('Nombre des Opreations', Operation::query()->count())
        ->description('Total Operation')
        ->descriptionIcon('heroicon-o-arrows-pointing-in')
        ->chart($monthlyOperations)
        ->color('success'),
];

    }

    protected function getType(): string
    {
        return 'line';
    }
}
