<?php

namespace App\Providers;

use App\Services\KioskCreditService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        View::composer('kiosk.*', function ($view) {
            $view->with(
                'kioskCreditBalance',
                app(KioskCreditService::class)->balance()
            );
        });
    }
}
