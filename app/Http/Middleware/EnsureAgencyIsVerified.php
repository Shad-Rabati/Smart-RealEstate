<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAgencyIsVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('agency.login');
        }

        $user = Auth::user();

        // Admins bypass
        if ($user->isAdmin()) {
            return $next($request);
        }

        if ($user->isAgency() && $user->agency) {
            if (!$user->agency->verified) {
                // If accessing pending page or logout, allow
                if ($request->routeIs('agency.pending') || $request->routeIs('agency.logout')) {
                    return $next($request);
                }

                return redirect()->route('agency.pending')
                    ->with('warning', 'نووسینگەکەت هێشتا پەسەند نەکراوە لەلایەن سەرپەرشتیارەوە.');
            }
        }

        return $next($request);
    }
}
