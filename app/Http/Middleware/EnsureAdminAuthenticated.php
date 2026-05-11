<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAuthenticated
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $expiresAt = session()->get(
            'admin_expires_at'
        );

        if (
            $expiresAt &&
            now()->greaterThan($expiresAt)
        ) {
            session()->forget([
                'admin_authenticated',
                'admin_id',
                'admin_expires_at',
            ]);

            return redirect()->route(
                'kiosk.home'
            );
        }

        if (! session()->get('admin_authenticated')) {
            return redirect()->route(
                'kiosk.home'
            );
        }

        return $next($request);
    }
}
