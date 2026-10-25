<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VipPropertyController extends Controller
{
    public function index(Request $request): View
    {
        $vipProperties = Property::with('agency')
            ->where('is_featured', true)
            ->orderByRaw('CASE WHEN featured_at IS NOT NULL THEN featured_at ELSE updated_at END DESC')
            ->latest('created_at')
            ->paginate(15);

        // Candidate properties to quickly add to VIP
        $availableCandidates = Property::with('agency')
            ->where('is_featured', false)
            ->where('status', 'available')
            ->latest()
            ->take(60)
            ->get();

        $stats = [
            'total_vip' => Property::where('is_featured', true)->count(),
            'active_on_home' => Property::where('is_featured', true)->where('status', 'available')->count(),
            'agencies_with_vip' => Agency::whereHas('properties', fn($q) => $q->where('is_featured', true))->count(),
        ];

        return view('admin.vip.index', compact('vipProperties', 'availableCandidates', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
        ]);

        $property = Property::findOrFail($validated['property_id']);
        $property->is_featured = true;
        $property->featured_at = now();
        $property->save();

        cache()->forget('landing_vip_properties');

        return back()->with('success', "خانووی \"{$property->title}\" بە سەرکەوتوویی خرایە ناو لیستی VIP و دەستبەجێ لە سەرەتای لاپەڕەی سەرەکی دەردەکەوێت.");
    }

    public function destroy(Property $property): RedirectResponse
    {
        $title = $property->title;
        $property->is_featured = false;
        $property->featured_at = null;
        $property->save();

        cache()->forget('landing_vip_properties');

        return back()->with('success', "خانووی \"{$title}\" لە لیستی VIP لادرا.");
    }
}
