<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-filament-panels::form wire:submit="updateProfile">
            {{ $this->editPersonalDetailsForm }}
        </x-filament-panels::form>

        <x-filament-panels::form wire:submit="updatePassword">
            {{ $this->createNewPasswordForm }}
        </x-filament-panels::form>
    </div>
</x-filament-panels::page>
