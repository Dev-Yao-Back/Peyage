<?php

namespace App\Filament\Resources\TransporteurResource\Pages;

use App\Filament\Resources\TransporteurResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransporteur extends CreateRecord


{
    protected static string $resource = TransporteurResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige vers la liste des catégories
    }
}
