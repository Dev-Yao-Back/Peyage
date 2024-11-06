<?php

namespace App\Filament\Resources\ProvenanceResource\Pages;

use App\Filament\Resources\ProvenanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProvenance extends EditRecord
{
    protected static string $resource = ProvenanceResource::class;

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
