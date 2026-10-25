<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if (Auth::user()->agency) {
                return redirect()->route('agency.dashboard');
            }
        }
        return view('agency.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'بەخێربێیتەوە بۆ بەشی سەرپەرشتیاری (Admin)');
            }

            if (!Auth::user()->agency) {
                // If user doesn't have agency profile, create a default one
                Agency::create([
                    'user_id' => Auth::id(),
                    'name' => Auth::user()->name,
                    'slug' => (Str::slug(Auth::user()->name) ?: 'agency') . '-' . rand(100, 999),
                    'phone' => Auth::user()->phone ?? '07700000000',
                    'address' => 'سلێمانی',
                    'city' => 'سلێمانی',
                    'verified' => false,
                ]);
            }

            if (Auth::user()->agency && !Auth::user()->agency->verified) {
                return redirect()->route('agency.pending');
            }

            return redirect()->intended(route('agency.dashboard'))
                ->with('success', 'بەخێربێیتەوە! بەسەرکەوتوویی چوویە ژوورەوە بۆ پانێڵی نووسینگە.');
        }

        return back()->withErrors([
            'email' => 'ئیمەیڵ یان وشەی تێپەڕ هەڵەیە، تکایە دڵنیابەرەوە.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check() && Auth::user()->agency) {
            if (Auth::user()->agency->verified) {
                return redirect()->route('agency.dashboard');
            }
            return redirect()->route('agency.pending');
        }
        return view('agency.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'agency_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'agency',
            'phone' => $validated['phone'],
        ]);

        $slugBase = Str::slug($validated['agency_name']);
        $slug = ($slugBase ? $slugBase : 'agency') . '-' . rand(100, 999);

        $initialCredits = (int) \App\Models\SystemSetting::get('initial_free_credits', 2);

        $agency = Agency::create([
            'user_id' => $user->id,
            'name' => $validated['agency_name'],
            'slug' => $slug,
            'owner_name' => $validated['name'],
            'phone' => $validated['phone'],
            'whatsapp' => $validated['whatsapp'] ?? $validated['phone'],
            'address' => $validated['address'],
            'city' => 'سلێمانی',
            'verified' => false,
            'credits' => $initialCredits,
        ]);

        \App\Models\CreditTransaction::create([
            'agency_id' => $agency->id,
            'amount' => $initialCredits,
            'balance_after' => $initialCredits,
            'type' => 'initial_gift',
            'description' => 'دیاری دەستپێکی بێبەرامبەر بۆ نووسینگەی نوێ',
        ]);

        Auth::login($user);

        return redirect()->route('agency.pending')
            ->with('info', 'تکایە چاوەڕوان بە لە ماوەیەکی کەمدا لەلایەن سەرپەرشتیارەکانەوە نووسینگەکەت تۆمار دەکرێت.');
    }

    public function pendingApproval()
    {
        if (!Auth::check()) {
            return redirect()->route('agency.login');
        }

        $agency = Auth::user()->agency;

        if ($agency && $agency->verified) {
            return redirect()->route('agency.dashboard');
        }

        return view('agency.pending', compact('agency'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('agency.login')->with('success', 'بەسەرکەوتوویی لە هەژمارەکەت هاتیتە دەرەوە.');
    }
}
