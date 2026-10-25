<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Supported application locales.
     */
    public const SUPPORTED_LOCALES = ['ku', 'en', 'ar'];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale', $request->cookie('app_locale', config('app.locale', 'ku')));

        if (!in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = config('app.locale', 'ku');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
