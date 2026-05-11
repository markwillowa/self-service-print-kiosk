<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureKioskRegistered
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $registered = Organization::query()
            ->where('is_registered', true)
            ->exists();

        if (! $registered) {
            if (! $request->routeIs('registration.*')) {
                return redirect()->route(
                    'registration.index'
                );
            }
        }

        return $next($request);
    }
}
