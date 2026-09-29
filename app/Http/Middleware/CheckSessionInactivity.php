<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionInactivity
{
    /**
     * Maximum inactivity duration allowed before timeout: 15 minutes (900 seconds).
     */
    protected int $maxIdleTime = 15 * 60;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = $request->session()->get('last_activity_time');
            $currentTime = time();

            if ($lastActivity && ($currentTime - $lastActivity > $this->maxIdleTime)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('error', 'Sorry, your session timed out due to 15 minutes of inactivity. Please sign in again.');
            }

            // Update timestamp on active user interaction
            $request->session()->put('last_activity_time', $currentTime);
        }

        return $next($request);
    }
}
