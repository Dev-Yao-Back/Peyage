<?php

namespace App\Filament\Widgets;
use Carbon\Carbon;
use App\Models\Operation;
use Illuminate\Support\Facades\DB;

use Filament\Widgets\ChartWidget;

class Order1Chart extends ChartWidget
{
    protected static ?string $heading = 'Statistiques des Opérations';

    protected function getData(): array
    {
        // Obtenir les opérations groupées par jour, semaine et mois
        $operationsParJour = Operation::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->whereDate('created_at', '>=', Carbon::now()->subDays(30))
            ->pluck('total', 'date')
            ->toArray();

        $operationsParSemaine = Operation::select(DB::raw('YEARWEEK(created_at) as week'), DB::raw('count(*) as total'))
            ->groupBy('week')
            ->whereDate('created_at', '>=', Carbon::now()->subWeeks(8))
            ->pluck('total', 'week')
            ->toArray();

        $operationsParMois = Operation::select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
            ->groupBy('month')
            ->whereYear('created_at', Carbon::now()->year)
            ->pluck('total', 'month')
            ->toArray();

        return [
            'labels' => ['Opérations par Jour', 'Opérations par Semaine', 'Opérations par Mois'],
            'datasets' => [
                [
                    'label' => 'Nombre d\'opérations',
                    'data' => [
                        array_sum($operationsParJour),
                        array_sum($operationsParSemaine),
                        array_sum($operationsParMois)
                    ],
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // Retourne le type du graphique (doughnut)
    }
}

