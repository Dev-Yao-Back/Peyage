<?php

namespace App\Filament\Resources\FournisseurResource\Pages;

use App\Filament\Resources\FournisseurResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFournisseur extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige vers la liste des catégories
    }

    protected static string $resource = FournisseurResource::class;
}
