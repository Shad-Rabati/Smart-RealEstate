<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $admin = Auth::user();

        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database' => config('database.default'),
            'server_os' => PHP_OS,
            'app_env' => config('app.env'),
            'app_url' => config('app.url'),
        ];

        $settings = [
            'initial_free_credits' => SystemSetting::get('initial_free_credits', 2),
            'credits_per_property' => SystemSetting::get('credits_per_property', 1),
            'credit_price_usd' => SystemSetting::get('credit_price_usd', 2),
            'admin_phone' => SystemSetting::get('admin_phone', '07700000000'),
            'admin_whatsapp' => SystemSetting::get('admin_whatsapp', '07700000000'),
            'fastpay_number' => SystemSetting::get('fastpay_number', '07700000000'),
            'fib_account' => SystemSetting::get('fib_account', 'IQ00FIB0000000000000000'),
            'payment_instructions' => SystemSetting::get('payment_instructions', SystemSetting::defaultSettings()['payment_instructions']),
        ];

        return view('admin.settings.index', compact('admin', 'systemInfo', 'settings'));
    }

    public function updateCreditsSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'initial_free_credits' => 'required|integer|min:0|max:100',
            'credits_per_property' => 'required|integer|min:1|max:50',
            'credit_price_usd' => 'nullable|numeric|min:0',
            'admin_phone' => 'nullable|string|max:50',
            'admin_whatsapp' => 'nullable|string|max:50',
            'fastpay_number' => 'nullable|string|max:50',
            'fib_account' => 'nullable|string|max:100',
            'payment_instructions' => 'nullable|string|max:2000',
        ]);

        foreach ($validated as $key => $val) {
            SystemSetting::set($key, $val);
        }

        return back()->with('success', 'ڕێکخستنەکانی سیستەمی کریدت و پارەدان بە سەرکەوتوویی نوێکرانەوە.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.required' => 'تکایە وشەی نهێنی ئێستات بنووسە.',
            'current_password.current_password' => 'وشەی نهێنی ئێستات هەڵەیە.',
            'password.required' => 'تکایە وشەی نهێنی نوێ بنووسە.',
            'password.confirmed' => 'دووبارەکردنەوەی وشەی نهێنی نوێ یەکناگرێتەوە.',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success', 'وشەی نهێنی بە سەرکەوتوویی نوێکرایەوە.');
    }
}
