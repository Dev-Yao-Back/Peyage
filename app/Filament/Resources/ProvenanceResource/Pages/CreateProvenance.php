<?php

namespace App\Filament\Resources\ProvenanceResource\Pages;

use App\Filament\Resources\ProvenanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProvenance extends CreateRecord
{
    protected static string $resource = ProvenanceResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
