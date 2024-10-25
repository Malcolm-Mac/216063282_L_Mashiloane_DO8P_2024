<?php

namespace App\Filament\Resources;

use App\Domain\User\Actions\UpdateUserAction;
use App\Domain\User\Dto\UserData;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use App\Domain\User\Enums\RoleNames;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $loggedInUser = Auth::user();

        if ($loggedInUser->hasRole(RoleNames::MANAGER->value)) {
            return User::where('id', '<>', $loggedInUser->id)
                ->whereHas('roles', function ($query) {
                    return $query->where('name', RoleNames::MANAGER->value);
                });
        } else {
            return parent::getEloquentQuery()
                ->where('id', '<>', $loggedInUser->id);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('surname')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable()
            ])
            ->filters([
                SelectFilter::make('role')
                    ->relationship('roles', 'name')->visible(auth()->user()->hasRole(RoleNames::ADMIN->value))
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hiddenLabel()
                    ->tooltip('Edit')
                    ->fillForm(fn(User $record): array => [
                        'name' => $record->name,
                        'surname' => $record->surname,
                        'email' => $record->email,
                        'role' => $record->roles->first()->name ?? null
                    ])
                    ->form([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('surname')
                            ->required(),
                        TextInput::make('email')
                            ->required()
                            ->email()
                            ->unique(ignoreRecord: true),
                        Select::make('role')
                            ->options(function () {

                                $options = [
                                    RoleNames::MANAGER->value => RoleNames::MANAGER->value,
                                ];

                                if (auth()->user() && auth()->user()->hasRole(RoleNames::ADMIN->value)) {
                                    $options[RoleNames::ADMIN->value] = RoleNames::ADMIN->value;
                                    $options[RoleNames::MANAGER->value] = RoleNames::MANAGER->value;
                                }

                                return $options;
                            })
                            ->required()
                            ->reactive(),

                    ])->action(function (array $data) {
                        $data = UserData::from($data);
                        $user = User::where('email', $data->email)->first();
                        if (!$user) {
                            Notification::make()
                                ->title('User not found')
                                ->danger()
                                ->send();
                            return;
                        }
                        $action = app()->make(UpdateUserAction::class);
                        $action->execute($user, $data);

                        Notification::make()
                            ->title('Edited successfully')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->tooltip('Delete')
                    ->hiddenLabel()
                    ->visible(auth()->user()->hasRole(RoleNames::ADMIN->value)),
                Tables\Actions\RestoreAction::make()
                    ->hiddenLabel()
                    ->tooltip('Restore')
                    ->visible(auth()->user()->hasRole(RoleNames::ADMIN->value)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make()
                ])->visible(auth()->user()->hasRole(RoleNames::ADMIN->value)),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create')
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole(RoleNames::ADMIN->value) || Auth::user()->hasRole(RoleNames::MANAGER->value);
    }
}
