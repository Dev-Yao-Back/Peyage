<?php

namespace App\Filament\Resources\TransporteurResource\Pages;

use App\Filament\Resources\TransporteurResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransporteur extends EditRecord
{
    protected static string $resource = TransporteurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
