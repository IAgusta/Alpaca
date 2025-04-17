<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class TrackLastActiveAt
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $now = Carbon::now();
            $lastCheck = Session::get('last_seen_check');

            if (!$lastCheck || $now->diffInMinutes(Carbon::parse($lastCheck)) >= 5) {
                $user->update(['last_seen' => $now]);
                Session::put('last_seen_check', $now);
            }
        }

        return $next($request);
    }
}
