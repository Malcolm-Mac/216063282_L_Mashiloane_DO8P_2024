<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplianceCheckResource\Pages;
use App\Filament\Resources\ComplianceCheckResource\RelationManagers;
use App\Models\ComplianceCheck;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
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
                Filter::make('compliant')
                    ->query(fn(Builder $query) => $query->where('status', 'compliant'))
                    ->label('Compliant'),
                Filter::make('non_compliant')
                    ->query(fn(Builder $query) => $query->where('status', 'non-compliant'))
                    ->label('Non-compliant'),
            ])
            ->actions([
                ActionGroup::make([
                Tables\Actions\Action::make('Trigger Linux Agent')
                    ->action(function () {
                        // Call the API to trigger the agent
                        self::triggerAgent('linux');
                    }),
                Tables\Actions\Action::make('Trigger macOS Agent')
                    ->action(function () {
                        // Call the API to trigger the agent
                        self::triggerAgent('macos');
                    }),
                Tables\Actions\Action::make('Trigger Windows Agent')
                    ->action(function () {
                        // Call the API to trigger the agent
                        self::triggerAgent('windows');
                    }),
                ])
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

    protected static function triggerAgent($os)
    {
        // Call the API to trigger the agent
        $response = Http::post(url('/api/agent/trigger/' . $os));
        
        // Handle the response as needed, e.g., show a success message or log it
        // You can use a Toast notification here if you want
        return $response->json();
    }
}
