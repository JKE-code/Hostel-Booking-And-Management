<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors(['email' => 'Your account is deactivated. Please contact the administrator.']);
        }

        if (! in_array($user->role, $roles)) {
            // Master Admin has universal inspection access across all operational tiers
            if ($user->role === 'admin') {
                return $next($request);
            }

            // Friendly redirect based on their actual role if unauthorized for this area
            return match ($user->role) {
                'admin'    => redirect()->route('admin.dashboard')->with('error', 'Access denied to that section.'),
                'warden'   => redirect()->route('warden.dashboard')->with('error', 'Access denied to that section.'),
                'security' => redirect()->route('security.dashboard')->with('error', 'Access denied to that section.'),
                'student'  => redirect()->route('student.dashboard')->with('error', 'Access denied to that section.'),
                default    => abort(403, 'Unauthorized action.'),
            };
        }

        return $next($request);
    }
}
