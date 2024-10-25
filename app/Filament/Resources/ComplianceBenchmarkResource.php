<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplianceBenchmarkResource\Pages;
use App\Filament\Resources\ComplianceBenchmarkResource\RelationManagers;
use App\Models\ComplianceBenchmark;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComplianceBenchmarkResource extends Resource
{
    protected static ?string $model = ComplianceBenchmark::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('os')
                    ->options([
                        'Linux' => 'Linux',
                        'Windows' => 'Windows',
                        'macOS' => 'macOS',
                    ])
                    ->label('Operating System')
                    ->required(),

                // Input the parameter to check (e.g., RAM, Disk Space)
                Forms\Components\TextInput::make('parameter')
                    ->label('Parameter')
                    ->placeholder('E.g., RAM, Disk Space')
                    ->required(),

                // Select the condition (e.g., >=, <=, ==)
                Forms\Components\Select::make('condition')
                    ->options([
                        '>=' => '>=',
                        '<=' => '<=',
                        '==' => '==',
                    ])
                    ->label('Condition')
                    ->required(),

                // Input the value for the condition (e.g., 8GB, 100GB)
                Forms\Components\TextInput::make('value')
                    ->label('Benchmark Value')
                    ->placeholder('E.g., 8 for 8GB of RAM')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('os')->label('Operating System'),
                Tables\Columns\TextColumn::make('parameter')->label('Parameter'),
                Tables\Columns\TextColumn::make('condition')->label('Condition'),
                Tables\Columns\TextColumn::make('value')->label('Value'),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->date(),
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
            'index' => Pages\ListComplianceBenchmarks::route('/'),
            'create' => Pages\CreateComplianceBenchmark::route('/create'),
            'edit' => Pages\EditComplianceBenchmark::route('/{record}/edit'),
        ];
    }
}
