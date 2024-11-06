<?php

namespace App\Filament\Resources\UnitesResource\Pages;

use App\Filament\Resources\UnitesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnites extends ListRecords
{
    protected static string $resource = UnitesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
