<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            Auth::logout();
        }

        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'تکایە ئیمەیڵ بنووسە.',
            'email.email' => 'تکایە ئیمەیڵێکی دروست بنووسە.',
            'password.required' => 'تکایە وشەی تێپەڕ بنووسە.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (! $user->isAdmin()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'ئەم هەژمارە دەسەڵاتی سەرپەرشتیاری (Admin) نییە.',
                ])->withInput($request->only('email'));
            }

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'بەخێربێیتەوە بۆ بەشی سەرپەرشتیاری (Admin)');
        }

        return back()->withErrors([
            'email' => 'ئیمەیڵ یان وشەی نهێنی هەڵەیە.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('agency.login')
            ->with('success', 'بە سەرکەوتوویی لە هەژمارەکەت دەرچوویت.');
    }
}
