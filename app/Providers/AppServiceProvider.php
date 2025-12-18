<?php

namespace App\Providers;

use App\Events\ShipmentEventRecorded;
use App\Listeners\SendSmsOnShipmentEventRecorded;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Blade;
use App\View\Components\Can;
use App\View\Components\Cannot;
use Laravel\Cashier\Cashier;
use App\Models\User;


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
        Event::listen(
            ShipmentEventRecorded::class,
            SendSmsOnShipmentEventRecorded::class,
        );
        
        // Register custom Blade components
        Blade::component('can', Can::class);
        Blade::component('cannot', Cannot::class);
        Cashier::useCustomerModel(User::class);
    }
}
