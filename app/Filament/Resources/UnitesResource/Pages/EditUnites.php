<?php

namespace App\Filament\Resources\UnitesResource\Pages;

use App\Filament\Resources\UnitesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnites extends EditRecord
{
    protected static string $resource = UnitesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
