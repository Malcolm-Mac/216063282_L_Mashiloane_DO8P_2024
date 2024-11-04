<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManageNetworkScanResource\Pages;
use App\Filament\Resources\ManageNetworkScanResource\RelationManagers;
use App\Models\ManageNetworkScan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Pages\ManageRecords;


class ManageNetworkScanResource extends ManageRecords
{
    protected static ?string $model = NetworkScanResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected function getActions(): array
    {
        return [
            Actions\Action::make('scan')
                ->label('Scan Network')
                ->action(fn () => (new \App\Console\Commands\ScanComputers())->handle())
                ->color('success')
        ];
    }
}
