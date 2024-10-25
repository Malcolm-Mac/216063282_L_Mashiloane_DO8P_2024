<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplianceCheckResource\Pages;
use App\Filament\Resources\ComplianceCheckResource\RelationManagers;
use App\Models\ComplianceCheck;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComplianceCheckResource extends Resource
{
    protected static ?string $model = ComplianceCheck::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('computer_id')
                    ->relationship('computer', 'name')
                    ->required(),
                Forms\Components\TextInput::make('parameter')->required(),
                Forms\Components\Select::make('status')
                    ->options(['compliant' => 'Compliant', 'non-compliant' => 'Non-compliant'])->required(),
                Forms\Components\Textarea::make('details')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('computer.name')->label('Computer Name'),
                Tables\Columns\TextColumn::make('parameter'),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'success' => 'compliant',
                    'danger' => 'non-compliant',
                ]),
                Tables\Columns\TextColumn::make('details')->limit(50),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Run Compliance Check')
                    ->action(function (ComplianceCheck $record) {
                        Artisan::call('compliance:check', [
                            'computerId' => $record->computer_id,
                        ]);
                        return redirect()->route('filament.resources.compliance-checks.index');
                    }),
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
            'index' => Pages\ListComplianceChecks::route('/'),
            'create' => Pages\CreateComplianceCheck::route('/create'),
            'edit' => Pages\EditComplianceCheck::route('/{record}/edit'),
        ];
    }
}
