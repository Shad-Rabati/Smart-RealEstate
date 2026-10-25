<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Property::with('agency');

        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('neighborhood', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('type') && $request->input('type') !== 'all') {
            $query->where('property_type', $request->input('type'));
        }

        if ($request->filled('agency_id') && $request->input('agency_id') !== 'all') {
            $query->where('agency_id', $request->input('agency_id'));
        }

        if ($request->filled('neighborhood') && $request->input('neighborhood') !== 'all') {
            $query->where('neighborhood', $request->input('neighborhood'));
        }

        if ($request->filled('featured') && $request->input('featured') !== 'all') {
            $query->where('is_featured', $request->input('featured') === '1');
        }

        $properties = $query->latest()->paginate(15)->withQueryString();

        $agencies = Agency::orderBy('name')->select(['id', 'name'])->get();
        $neighborhoods = Property::whereNotNull('neighborhood')->distinct()->pluck('neighborhood')->values();

        $counts = [
            'all' => Property::count(),
            'available' => Property::where('status', 'available')->count(),
            'reserved' => Property::where('status', 'reserved')->count(),
            'rented' => Property::where('status', 'rented')->count(),
            'featured' => Property::where('is_featured', true)->count(),
        ];

        return view('admin.properties.index', compact('properties', 'agencies', 'neighborhoods', 'counts'));
    }

    public function toggleFeatured(Property $property): RedirectResponse
    {
        $property->is_featured = !$property->is_featured;
        $property->featured_at = $property->is_featured ? now() : null;
        $property->save();

        cache()->forget('landing_vip_properties');

        $msg = $property->is_featured
            ? "خانووی \"{$property->title}\" بە سەرکەوتوویی کرا بە VIP و دەستبەجێ لە سەرەتای لاپەڕەی سەرەکی دەردەکەوێت."
            : "دۆخی VIP لەسەر خانووی \"{$property->title}\" لادرا.";

        return back()->with('success', $msg);
    }

    public function updateStatus(Request $request, Property $property): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:available,reserved,rented'],
        ]);

        $property->status = $validated['status'];
        $property->save();

        return back()->with('success', "دۆخی خانووی \"{$property->title}\" بە سەرکەوتوویی نوێکرایەوە.");
    }

    public function destroy(Property $property): RedirectResponse
    {
        $title = $property->title;
        $property->delete();

        return back()->with('success', "خانووی \"{$title}\" بە سەرکەوتوویی سڕایەوە.");
    }
}
