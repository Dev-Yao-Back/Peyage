<?php

namespace App\Filament\Resources\CampagneResource\Pages;

use App\Filament\Resources\CampagneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCampagne extends EditRecord
{
    protected static string $resource = CampagneResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige vers la liste des catégories
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
