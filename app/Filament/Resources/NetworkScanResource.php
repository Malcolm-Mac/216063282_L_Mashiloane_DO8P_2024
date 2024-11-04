<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NetworkScanResource\Pages;
use App\Filament\Resources\NetworkScanResource\RelationManagers;
use App\Models\NetworkScan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NetworkScanResource extends Resource
{
    protected static ?string $model = NetworkScan::class;
    protected static ?string $navigationGroup = 'Network Management';
    protected static ?string $navigationLabel = 'Network Scan';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Actions\Action::make('Scan Network')
                    ->action(fn() => (new \App\Console\Commands\ScanComputers())->handle())
                    ->label('Start Scan')
                    ->color('primary'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('scan_date')->label('Scan Date')->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Status')->sortable(),
                Tables\Columns\TextColumn::make('total_devices_found')->label('Total Devices Found')->sortable(),
                Tables\Columns\TextColumn::make('total_online_devices')->label('Total Online Devices')->sortable(),
                Tables\Columns\TextColumn::make('scan_duration')->label('Scan Duration (seconds)')->sortable(),
                Tables\Columns\TextColumn::make('network_range')->label('Network Range')->sortable(),
               /*  Tables\Columns\TextColumn::make('initiated_by')->label('Initiated By')->relationship('users', 'name'), */
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Updated At')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNetworkScans::route('/'),
            'create' => Pages\CreateNetworkScan::route('/create')
        ];
    }
}
