<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Illuminate\Support\Facades\URL;
use App\Filament\Resources\ComplianceCheckResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        Filament::registerResources([
            ComplianceCheckResource::class,
        ]);
        Filament::serving(function () {

            Filament::registerUserMenuItems([
                'logout' => MenuItem::make()->url(route('logout')),
            ]);

        });
    }
}
