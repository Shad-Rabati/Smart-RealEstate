<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocaleMiddleware;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Switch application locale.
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (in_array($locale, SetLocaleMiddleware::SUPPORTED_LOCALES, true)) {
            session(['locale' => $locale]);
            cookie()->queue(cookie('app_locale', $locale, 60 * 24 * 365));
        }

        return redirect()->back();
    }
}
