<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        if (
            session('admin_username') !== 'admin'
        ) {
            abort(403);
        }

        return $next($request);
    }
}
