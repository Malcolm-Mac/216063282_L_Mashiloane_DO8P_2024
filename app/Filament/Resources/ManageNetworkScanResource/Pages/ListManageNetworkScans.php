<?php

namespace App\Filament\Resources\ManageNetworkScanResource\Pages;

use App\Filament\Resources\ManageNetworkScanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManageNetworkScans extends ListRecords
{
    protected static string $resource = ManageNetworkScanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
