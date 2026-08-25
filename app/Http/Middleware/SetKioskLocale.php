<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetKioskLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('kiosk_locale', 'en');

        if (in_array($locale, ['en', 'tl'], true)) {
            App::setLocale($locale);
        } else {
            App::setLocale('en');
        }

        return $next($request);
    }
}
