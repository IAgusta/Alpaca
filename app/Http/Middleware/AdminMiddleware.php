<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Debugging: Log the current user details
        Log::info('AdminMiddleware Check', ['user' => Auth::user()]);
    
        // Check if the user is authenticated and has the role of owner, admin, or trainer
        if (Auth::check() && (Auth::user()->role === 'owner' || Auth::user()->role === 'admin' || Auth::user()->role === 'trainer')) {
            return $next($request);
        }
    
        // Redirect or abort if the user is not authorized
        abort(403, 'Unauthorized');
    }
}
