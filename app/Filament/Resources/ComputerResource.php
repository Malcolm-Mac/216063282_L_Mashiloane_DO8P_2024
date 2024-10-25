<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComputerResource\Pages;
use App\Filament\Resources\ComputerResource\RelationManagers;
use App\Models\Computer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComputerResource extends Resource
{
    protected static ?string $model = Computer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Hostname')
                    ->required(),

                Forms\Components\TextInput::make('ip_address')
                    ->label('IP Address')
                    ->unique(ignoreRecord:true)
                    ->required()
                    ->ip(),

                Forms\Components\Select::make('os')
                    ->options([
                        'Linux' => 'Linux',
                        'Windows' => 'Windows',
                        'macOS' => 'macOS',
                    ])
                    ->label('Operating System')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Hostname'),
                Tables\Columns\TextColumn::make('ip_address')->label('IP Address'),
                Tables\Columns\TextColumn::make('os')->label('Operating System'),
                Tables\Columns\TextColumn::make('latest_compliance_status')
                    ->label('Compliance Status')
                    ->getStateUsing(fn (Computer $record) => $record->latest_compliance_status)
                    ->formatStateUsing(function ($state) {
                        return ucfirst($state); // Capitalize the state
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            'Compliant' => 'success',
                            'Non-compliant' => 'danger',
                            default => 'secondary',
                        };
                    }),
                    Tables\Columns\TextColumn::make('created_at')->label('Checked At')->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListComputers::route('/'),
            'create' => Pages\CreateComputer::route('/create'),
            'edit' => Pages\EditComputer::route('/{record}/edit'),
        ];
    }
}
