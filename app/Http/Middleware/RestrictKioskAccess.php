<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictKioskAccess
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $allowedRoutes = [
            'kiosk.mobile-upload',
            'kiosk.mobile-store',
        ];

        if (
            in_array(
                $request->route()?->getName(),
                $allowedRoutes,
                true
            )
        ) {
            return $next($request);
        }

        $localIps = [
            '127.0.0.1',
            '::1',
        ];

        if (! in_array($request->ip(), $localIps, true)) {
            abort(403);
        }

        return $next($request);
    }
}
