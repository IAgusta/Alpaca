<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OnlyAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Allow ONLY admin role
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Redirect unauthorized users
        abort(403, 'Unauthorized access');
    }
}
