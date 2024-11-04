<?php

namespace App\Filament\Resources\NetworkScanResource\Pages;

use App\Filament\Resources\NetworkScanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNetworkScans extends ListRecords
{
    protected static string $resource = NetworkScanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Scan Network')
                ->action(fn() => (new \App\Console\Commands\ScanComputers())->handle())
                ->label('Start Scan')
                ->color('primary'),
        ];
    }
}
