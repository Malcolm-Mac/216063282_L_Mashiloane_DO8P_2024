<?php

namespace App\Filament\Resources\ComplianceBenchmarkResource\Pages;

use App\Filament\Resources\ComplianceBenchmarkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComplianceBenchmark extends EditRecord
{
    protected static string $resource = ComplianceBenchmarkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
