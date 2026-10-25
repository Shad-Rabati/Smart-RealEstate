<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $query = Hotel::query();

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($b) use ($q) {
                $b->where('name', 'like', "%{$q}%")
                  ->orWhere('neighborhood', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        if ($request->filled('stars') && $request->stars !== 'all') {
            $query->where('stars', (int) $request->stars);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $hotels = $query->orderByDesc('id')->paginate(15)->withQueryString();

        $stats = [
            'total' => Hotel::count(),
            'active' => Hotel::where('status', 'active')->count(),
            'five_star' => Hotel::where('stars', 5)->count(),
            'featured' => Hotel::where('is_featured', true)->count(),
        ];

        return view('admin.hotels.index', compact('hotels', 'stats'));
    }

    public function create()
    {
        $neighborhoods = [
            'بەختیاری', 'سەرچنار', 'گۆیژە', 'تووی مەلیک', 'سالم', 'زەرگەتە',
            'ڕاپەڕین', 'چوارچرا', 'ئیبراهیم ئەحمەد', 'شۆڕش', 'ڕزگاری', 'تاسڵوجە'
        ];
        $amenityDefinitions = Hotel::$amenityDefinitions;

        return view('admin.hotels.create', compact('neighborhoods', 'amenityDefinitions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stars' => 'required|integer|min:1|max:5',
            'price_per_night' => 'required|numeric|min:0',
            'price_currency' => 'required|string|max:10',
            'neighborhood' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'phone' => 'required|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
            'primary_image' => 'nullable|image|max:4096',
            'primary_image_url' => 'nullable|url',
        ]);

        $hotelData = $validated;
        $hotelData['is_featured'] = $request->boolean('is_featured');
        $hotelData['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);

        // Primary Image Handling
        if ($request->hasFile('primary_image')) {
            $path = $request->file('primary_image')->store('hotels', 'public');
            $hotelData['primary_image'] = $path;
        } elseif (!empty($validated['primary_image_url'])) {
            $hotelData['primary_image'] = $validated['primary_image_url'];
        }

        unset($hotelData['primary_image_url']);

        Hotel::create($hotelData);

        return redirect()->route('admin.hotels.index')->with('success', 'ئوتێلی نوێ بە سەرکەوتوویی زیادکرا.');
    }

    public function edit(Hotel $hotel)
    {
        $neighborhoods = [
            'بەختیاری', 'سەرچنار', 'گۆیژە', 'تووی مەلیک', 'سالم', 'زەرگەتە',
            'ڕاپەڕین', 'چوارچرا', 'ئیبراهیم ئەحمەد', 'شۆڕش', 'ڕزگاری', 'تاسڵوجە'
        ];
        $amenityDefinitions = Hotel::$amenityDefinitions;

        return view('admin.hotels.edit', compact('hotel', 'neighborhoods', 'amenityDefinitions'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stars' => 'required|integer|min:1|max:5',
            'price_per_night' => 'required|numeric|min:0',
            'price_currency' => 'required|string|max:10',
            'neighborhood' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'phone' => 'required|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
            'primary_image' => 'nullable|image|max:4096',
            'primary_image_url' => 'nullable|url',
        ]);

        $hotelData = $validated;
        $hotelData['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('primary_image')) {
            $path = $request->file('primary_image')->store('hotels', 'public');
            $hotelData['primary_image'] = $path;
        } elseif (!empty($validated['primary_image_url'])) {
            $hotelData['primary_image'] = $validated['primary_image_url'];
        }

        unset($hotelData['primary_image_url']);

        $hotel->update($hotelData);

        return redirect()->route('admin.hotels.index')->with('success', 'زانیارییەکانی ئوتێل بە سەرکەوتوویی نوێکرایەوە.');
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->delete();

        return redirect()->route('admin.hotels.index')->with('success', 'ئوتێل بە سەرکەوتوویی سڕایەوە.');
    }

    public function toggleStatus(Hotel $hotel)
    {
        $hotel->status = ($hotel->status === 'active') ? 'inactive' : 'active';
        $hotel->save();

        return back()->with('success', 'دۆخی ئوتێل گۆڕدرا بۆ: ' . ($hotel->status === 'active' ? 'چالاک' : 'ناچالاک'));
    }
}
