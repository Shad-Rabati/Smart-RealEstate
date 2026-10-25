<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Property;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    private function getAgency()
    {
        $user = Auth::user();
        if (!$user || !$user->agency) {
            abort(403, 'تەنها نووسینگەکان بۆیان هەیە بچنە ناو ئەم بەشەوە.');
        }
        return $user->agency;
    }

    public function index(Request $request)
    {
        $agency = $this->getAgency();
        $query = $agency->properties()->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('neighborhood', 'like', "%{$q}%");
            });
        }

        $properties = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => $agency->properties()->count(),
            'available' => $agency->availableProperties()->count(),
            'rented' => $agency->rentedProperties()->count(),
            'reserved' => $agency->reservedProperties()->count(),
            'total_views' => $agency->properties()->sum('views_count'),
        ];

        return view('agency.dashboard.index', compact('agency', 'properties', 'stats'));
    }

    public function create()
    {
        $agency = $this->getAgency();
        $requiredCredits = (int) SystemSetting::get('credits_per_property', 1);

        if (!$agency->hasCreditsForProperty()) {
            return redirect()->route('agency.credits')
                ->with('error', "باڵانسی کریدتت پێویستە بۆ دانانی خانووی نوێ! بۆ هەر خانوو {$requiredCredits} کریدت پێویستە. تکایە سەرەتا باڵانسی کریدتت نوێ بکەرەوە.");
        }

        $neighborhoods = HomeController::$neighborhoods;
        return view('agency.dashboard.create', compact('agency', 'neighborhoods', 'requiredCredits'));
    }

    public function store(Request $request)
    {
        $agency = $this->getAgency();
        $requiredCredits = (int) SystemSetting::get('credits_per_property', 1);

        if (!$agency->hasCreditsForProperty()) {
            return redirect()->route('agency.credits')
                ->with('error', "باڵانسی کریدتت بەس نییە بۆ دانانی خانووی نوێ! تکایە کریدت بکڕە.");
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'property_type' => 'required|in:house,apartment,villa,floor,commercial',
            'status' => 'required|in:available,reserved,rented',
            'rent_price' => 'required|numeric|min:0',
            'price_currency' => 'required|in:USD,IQD',
            'rent_period' => 'required|in:monthly,yearly',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'area_sqm' => 'required|integer|min:1',
            'neighborhood' => 'required|string|max:100',
            'street_address' => 'nullable|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'is_furnished' => 'nullable|boolean',
            'floor_number' => 'nullable|integer',
            'has_parking' => 'nullable|boolean',
            'has_elevator' => 'nullable|boolean',
            'has_generator' => 'nullable|boolean',
            'primary_image' => 'nullable|image|max:5120',
            'primary_image_url' => 'nullable|url',
            'video_url' => 'nullable|url|max:500',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
        ]);

        $imagePath = null;
        if ($request->hasFile('primary_image')) {
            $imagePath = $request->file('primary_image')->store('properties', 'public');
        } elseif ($request->filled('primary_image_url')) {
            $imagePath = $request->primary_image_url;
        } else {
            // Default placeholder
            $imagePath = 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=80';
        }

        $property = Property::create([
            'agency_id' => $agency->id,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . rand(1000, 9999),
            'property_type' => $validated['property_type'],
            'status' => $validated['status'],
            'rent_price' => $validated['rent_price'],
            'price_currency' => $validated['price_currency'],
            'rent_period' => $validated['rent_period'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'area_sqm' => $validated['area_sqm'],
            'neighborhood' => $validated['neighborhood'],
            'street_address' => $validated['street_address'] ?? null,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'is_furnished' => $request->boolean('is_furnished'),
            'floor_number' => $validated['floor_number'] ?? null,
            'has_parking' => $request->boolean('has_parking'),
            'has_elevator' => $request->boolean('has_elevator'),
            'has_generator' => $request->boolean('has_generator', true),
            'primary_image' => $imagePath,
            'video_url' => $validated['video_url'] ?? null,
            'description' => $validated['description'] ?? null,
            'features' => $request->input('features', []),
        ]);

        // Deduct credits and log transaction
        $agency->deductCredits(
            $requiredCredits,
            "دانانی خانووی: " . Str::limit($property->title, 40),
            'property_publish'
        );

        return redirect()->route('agency.dashboard')
            ->with('success', "خانووەکە بە سەرکەوتوویی زیادکرا ({$requiredCredits} کریدت بەکارهێنرا). باڵانسی ماوە: {$agency->credits} کریدت.");
    }

    public function edit(Property $property)
    {
        $agency = $this->getAgency();
        if ($property->agency_id !== $agency->id) {
            abort(403);
        }

        $neighborhoods = HomeController::$neighborhoods;
        return view('agency.dashboard.edit', compact('agency', 'property', 'neighborhoods'));
    }

    public function update(Request $request, Property $property)
    {
        $agency = $this->getAgency();
        if ($property->agency_id !== $agency->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'property_type' => 'required|in:house,apartment,villa,floor,commercial',
            'status' => 'required|in:available,reserved,rented',
            'rent_price' => 'required|numeric|min:0',
            'price_currency' => 'required|in:USD,IQD',
            'rent_period' => 'required|in:monthly,yearly',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'area_sqm' => 'required|integer|min:1',
            'neighborhood' => 'required|string|max:100',
            'street_address' => 'nullable|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'is_furnished' => 'nullable|boolean',
            'floor_number' => 'nullable|integer',
            'has_parking' => 'nullable|boolean',
            'has_elevator' => 'nullable|boolean',
            'has_generator' => 'nullable|boolean',
            'primary_image' => 'nullable|image|max:5120',
            'primary_image_url' => 'nullable|url',
            'video_url' => 'nullable|url|max:500',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
        ]);

        $imagePath = $property->primary_image;
        if ($request->hasFile('primary_image')) {
            $imagePath = $request->file('primary_image')->store('properties', 'public');
        } elseif ($request->filled('primary_image_url')) {
            $imagePath = $request->primary_image_url;
        }

        $property->update([
            'title' => $validated['title'],
            'property_type' => $validated['property_type'],
            'status' => $validated['status'],
            'rent_price' => $validated['rent_price'],
            'price_currency' => $validated['price_currency'],
            'rent_period' => $validated['rent_period'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'area_sqm' => $validated['area_sqm'],
            'neighborhood' => $validated['neighborhood'],
            'street_address' => $validated['street_address'] ?? null,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'is_furnished' => $request->boolean('is_furnished'),
            'floor_number' => $validated['floor_number'] ?? null,
            'has_parking' => $request->boolean('has_parking'),
            'has_elevator' => $request->boolean('has_elevator'),
            'has_generator' => $request->boolean('has_generator'),
            'primary_image' => $imagePath,
            'video_url' => $validated['video_url'] ?? null,
            'description' => $validated['description'] ?? null,
            'features' => $request->input('features', []),
        ]);

        return redirect()->route('agency.dashboard')
            ->with('success', 'زانیارییەکانی خانوو بە سەرکەوتوویی نوێکرانەوە.');
    }

    /**
     * Quick status toggle (available <-> rented <-> reserved)
     */
    public function updateStatus(Request $request, Property $property)
    {
        $agency = $this->getAgency();
        if ($property->agency_id !== $agency->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:available,reserved,rented',
        ]);

        $property->update(['status' => $request->status]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $property->status,
                'label' => $property->status_label,
                'color' => $property->status_color_name,
            ]);
        }

        return back()->with('success', 'دۆخی خانوو گۆڕدرا بۆ: ' . $property->status_label);
    }

    public function destroy(Property $property)
    {
        $agency = $this->getAgency();
        if ($property->agency_id !== $agency->id) {
            abort(403);
        }

        $property->delete();

        return redirect()->route('agency.dashboard')
            ->with('success', 'خانووەکە بەسەرکەوتوویی سڕایەوە.');
    }

    public function profile()
    {
        $agency = $this->getAgency();
        return view('agency.dashboard.profile', compact('agency'));
    }

    public function updateProfile(Request $request)
    {
        $agency = $this->getAgency();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'owner_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'address' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'logo_url' => 'nullable|url',
        ]);

        $logoPath = $agency->logo;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('agencies', 'public');
        } elseif ($request->filled('logo_url')) {
            $logoPath = $request->logo_url;
        }

        $agency->update([
            'name' => $validated['name'],
            'owner_name' => $validated['owner_name'] ?? null,
            'phone' => $validated['phone'],
            'whatsapp' => $validated['whatsapp'] ?? null,
            'address' => $validated['address'],
            'description' => $validated['description'] ?? null,
            'logo' => $logoPath,
        ]);

        return back()->with('success', 'زانیارییەکانی پرۆفایلی نووسینگە بە سەرکەوتوویی نوێکرانەوە.');
    }

    public function credits()
    {
        $agency = $this->getAgency();
        $transactions = $agency->creditTransactions()->paginate(15);
        $settings = [
            'credits_per_property' => (int) SystemSetting::get('credits_per_property', 1),
            'credit_price_usd' => SystemSetting::get('credit_price_usd', 2),
            'admin_phone' => SystemSetting::get('admin_phone', '07700000000'),
            'admin_whatsapp' => SystemSetting::get('admin_whatsapp', '07700000000'),
            'fastpay_number' => SystemSetting::get('fastpay_number', '07700000000'),
            'fib_account' => SystemSetting::get('fib_account', 'IQ00FIB0000000000000000'),
            'payment_instructions' => SystemSetting::get('payment_instructions', SystemSetting::defaultSettings()['payment_instructions']),
        ];

        return view('agency.dashboard.credits', compact('agency', 'transactions', 'settings'));
    }
}
