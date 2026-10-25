<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AgencyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Agency::withCount('properties')->with('user');

        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('verified')) {
            if ($request->input('verified') === '1') {
                $query->where('verified', true);
            } elseif ($request->input('verified') === '0') {
                $query->where('verified', false);
            }
        }

        $agencies = $query->latest()->paginate(12)->withQueryString();

        $counts = [
            'all' => Agency::count(),
            'verified' => Agency::where('verified', true)->count(),
            'unverified' => Agency::where('verified', false)->count(),
        ];

        return view('admin.agencies.index', compact('agencies', 'counts'));
    }

    public function create(): View
    {
        return view('admin.agencies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'address' => 'required|string|max:255',
            'city' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'verified' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('agencies', 'public');
        }

        $user = User::create([
            'name' => $validated['owner_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'agency',
            'phone' => $validated['phone'],
        ]);

        $slugBase = Str::slug($validated['name']);
        $slug = ($slugBase ? $slugBase : 'agency') . '-' . rand(100, 9999);

        $initialCredits = (int) \App\Models\SystemSetting::get('initial_free_credits', 2);

        $agency = Agency::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'slug' => $slug,
            'owner_name' => $validated['owner_name'],
            'phone' => $validated['phone'],
            'whatsapp' => $validated['whatsapp'] ?? $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'] ?? 'سلێمانی',
            'description' => $validated['description'] ?? null,
            'logo' => $logoPath,
            'verified' => $request->boolean('verified', true),
            'credits' => $initialCredits,
        ]);

        \App\Models\CreditTransaction::create([
            'agency_id' => $agency->id,
            'amount' => $initialCredits,
            'balance_after' => $initialCredits,
            'type' => 'initial_gift',
            'description' => 'دیاری دەستپێکی بێبەرامبەر بۆ نووسینگەی نوێ',
        ]);

        return redirect()->route('admin.agencies.index')
            ->with('success', "نووسینگەی \"{$agency->name}\" بە سەرکەوتوویی لەلایەن ئەدمینەوە زیادکرا.");
    }

    public function show(Agency $agency): View
    {
        $agency->load(['user', 'properties' => function ($q) {
            $q->latest();
        }]);

        return view('admin.agencies.show', compact('agency'));
    }

    public function toggleVerify(Agency $agency): RedirectResponse
    {
        $agency->verified = !$agency->verified;
        $agency->save();

        $status = $agency->verified ? 'باوەڕپێکراو کرا' : 'لە دۆخی باوەڕپێکراو دەرهێنرا';
        return back()->with('success', "نووسینگەی \"{$agency->name}\" بە سەرکەوتوویی {$status}.");
    }

    public function destroy(Agency $agency): RedirectResponse
    {
        $agencyName = $agency->name;
        
        // Delete agency's properties first
        $agency->properties()->delete();
        
        // Delete agency user account
        if ($agency->user) {
            $agency->user->delete();
        }
        
        $agency->delete();

        return redirect()->route('admin.agencies.index')
            ->with('success', "نووسینگەی \"{$agencyName}\" و سەرجەم خانووەکانی بە سەرکەوتوویی سڕانەوە.");
    }

    public function updateCredits(Request $request, Agency $agency): RedirectResponse
    {
        $validated = $request->validate([
            'mode' => 'required|in:add,set',
            'amount' => 'required|integer|min:0|max:10000',
            'note' => 'nullable|string|max:255',
        ]);

        $note = $validated['note'] ?: 'دەستکاریکردن لەلایەن سەرپەرشتیارەوە';

        if ($validated['mode'] === 'add') {
            $agency->addCredits($validated['amount'], $note, 'admin_topup');
            $msg = "بڕی {$validated['amount']} کریدت بۆ نووسینگەی \"{$agency->name}\" زیادکرا. باڵانسی نوێ: {$agency->credits}";
        } else {
            $diff = $validated['amount'] - $agency->credits;
            $agency->credits = $validated['amount'];
            $agency->save();

            \App\Models\CreditTransaction::create([
                'agency_id' => $agency->id,
                'amount' => $diff,
                'balance_after' => $agency->credits,
                'type' => 'admin_adjustment',
                'description' => $note . " (گۆڕینی باڵانس بۆ {$agency->credits})",
            ]);
            $msg = "باڵانسی نووسینگەی \"{$agency->name}\" گۆڕدرا بۆ {$agency->credits} کریدت.";
        }

        return back()->with('success', $msg);
    }
}
