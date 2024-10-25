<?php

namespace App\Filament\Resources\ComplianceCheckResource\Pages;

use App\Filament\Resources\ComplianceCheckResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComplianceCheck extends EditRecord
{
    protected static string $resource = ComplianceCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
