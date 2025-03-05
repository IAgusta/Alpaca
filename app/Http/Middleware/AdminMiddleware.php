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

        // Check if the user is authenticated and has the role of admin or teach
        if (Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'teach')) {
            return $next($request);
        }
        return redirect('/dashboard');
    }
}
