<?php

namespace App\Filament\Resources\NetworkScanResource\Pages;

use App\Filament\Resources\NetworkScanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNetworkScan extends EditRecord
{
    protected static string $resource = NetworkScanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
