<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('admin*')) {
                return route('admin.login');
            }
            return route('agency.login');
        });
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisitorMiddleware::class,
        ]);
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'agency.verified' => \App\Http\Middleware\EnsureAgencyIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() === 419) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'کاتەکەت بەسەرچوو، تکایە پەڕەکە نوێبکەرەوە.',
                    ], 419);
                }

                $target = $request->header('referer') ?: ($request->is('admin*') ? route('admin.login') : route('agency.login'));

                return redirect()->to($target)
                    ->withInput($request->except(['password', 'password_confirmation', '_token']))
                    ->with('error', 'کاتەکەت بەسەرچوو، پەڕەکە نوێکرایەوە. تکایە دووبارە هەوڵبدەرەوە.');
            }
        });
    })->create();
