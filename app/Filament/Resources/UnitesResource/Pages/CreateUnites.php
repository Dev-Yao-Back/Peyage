<?php

namespace App\Filament\Resources\UnitesResource\Pages;

use App\Filament\Resources\UnitesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUnites extends CreateRecord
{
    protected static string $resource = UnitesResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige vers la liste des catégories
    }
}
