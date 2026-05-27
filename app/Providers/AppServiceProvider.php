<?php

namespace App\Providers;

use App\Models\Company;
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
        View::composer('*', function ($view) {
            $company = Company::query()->latest()->first();

            $view->with(
                'globalKioskName',
                $company?->kiosk_name ?? 'Piso Print'
            );

            $view->with(
                'globalCompany',
                $company
            );

            $view->with(
                'kioskCreditBalance',
                app(KioskCreditService::class)->balance()
            );
        });
    }
}
