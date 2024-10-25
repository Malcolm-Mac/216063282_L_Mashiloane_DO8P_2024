<?php

namespace App\Filament\Pages;

use Exception;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Js;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.edit-profile';

    protected static bool $shouldRegisterNavigation = false;
    public ?array $profileData = [];
    public ?array $passwordData = [];
    public function mount(): void
    {
        $this->fillForms();
    }
    protected function getForms(): array
    {
        return [
            'editPersonalDetailsForm',
            'createNewPasswordForm'
        ];
    }
    public function editPersonalDetailsForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Profile Information')
                ->description("Update your account's profile information and email address.")
                ->footerActions([
                    Action::make('back')
                    ->label(__('filament-panels::pages/auth/edit-profile.actions.cancel.label'))
                    ->alpineClickHandler('document.referrer ? window.history.back() : (window.location.href = ' . Js::from(filament()->getUrl()) . ')')
                    ->color('gray'),
                    Action::make('updateProfileAction')
                    ->label(__('filament-panels::pages/auth/edit-profile.form.actions.save.label'))
                    ->submit('editPersonalDetailsForm')
                ])
                ->description('Update your account\'s profile information and email address.')
                ->schema([
                    TextInput::make('name')
                    ->autofocus()
                    ->required(),
                TextInput::make('surname')
                    ->autofocus()
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                    TextInput::make('phone_number')
                    ->required()
                ])
            ])
            ->statePath('profileData')
            ->model($this->getUser());
    }

    public function createNewPasswordForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Update Password')
                ->description('Ensure your account is using long, random password to stay secure.')
                ->footerActions([
                    Action::make('back')
                    ->label(__('filament-panels::pages/auth/edit-profile.actions.cancel.label'))
                    ->alpineClickHandler('document.referrer ? window.history.back() : (window.location.href = ' . Js::from(filament()->getUrl()) . ')')
                    ->color('gray'),
                    Action::make('updatePasswordAction')
                    ->label(__('filament-panels::pages/auth/edit-profile.form.actions.save.label'))
                    ->submit('createNewPasswordForm')
                ])
                ->description('Ensure your account is using long, random password to stay secure.')
                ->schema([
                    TextInput::make('password')
                    ->label('New Password')
                    ->password()
                    ->autofocus()
                    ->required()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->rule(Password::default())
                    ->autocomplete('new-password')
                    ->dehydrated(fn ($state): bool => filled($state))
                    ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                    ->same('passwordConfirmation'),
                TextInput::make('passwordConfirmation')
                    ->password()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->required()
                    ->dehydrated(false)
                ])
            ])
            ->statePath('passwordData')
            ->model($this->getUser());
    }

    protected function getUser(): Authenticatable & Model
    {
        $user = Filament::auth()->user();
        if (! $user instanceof Model) {
        throw new Exception('The authenticated user object must be an Eloquent model to allow the profile page to update it.');
        }
        return $user;
    }

    protected function fillForms(): void
    {
        $data = $this->getUser()->attributesToArray();
        $this->editPersonalDetailsForm->fill($data);
        $this->createNewPasswordForm->fill($data);
    }

    public function updateProfile(): void
    {
        $data = $this->editPersonalDetailsForm->getState();
        $this->handleRecordUpdate($this->getUser(), $data);
        $this->sendSuccessNotification();
    }
    public function updatePassword(): void
    {
        $data = $this->createNewPasswordForm->getState();
        $this->handleRecordUpdate($this->getUser(), $data);
        if (request()->hasSession() && array_key_exists('password', $data)) {
            request()->session()->put(['password_hash_' . Filament::getAuthGuard() => $data['password']]);
        }
        $this->createNewPasswordForm->fill();
        $this->sendSuccessNotification();
    }
    private function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);
        return $record;
    }

    private function sendSuccessNotification(): void
    {
        Notification::make()
                ->success()
                ->title("Profile updated successfully!")
                ->send();
    }
}
