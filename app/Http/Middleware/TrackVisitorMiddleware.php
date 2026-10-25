<?php

namespace App\Http\Middleware;

use App\Models\SiteVisit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitorMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('GET') && !$request->ajax() && $response->getStatusCode() === 200) {
            try {
                SiteVisit::record($request);
            } catch (\Throwable $e) {
                // Never fail user request if tracking fails
            }
        }

        return $response;
    }
}
