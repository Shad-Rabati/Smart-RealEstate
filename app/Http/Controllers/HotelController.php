<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $query = Hotel::query()->active();

        // Search Keyword
        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where(function ($b) use ($q) {
                $b->where('name', 'like', "%{$q}%")
                  ->orWhere('neighborhood', 'like', "%{$q}%")
                  ->orWhere('address', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Stars filter
        if ($request->filled('stars') && $request->stars !== 'all') {
            $query->where('stars', (int) $request->stars);
        }

        // Neighborhood filter
        if ($request->filled('neighborhood') && $request->neighborhood !== 'all') {
            $query->where('neighborhood', $request->neighborhood);
        }

        // Price range
        if ($request->filled('min_price') && is_numeric($request->min_price)) {
            $query->where('price_per_night', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price') && is_numeric($request->max_price)) {
            $query->where('price_per_night', '<=', (float) $request->max_price);
        }

        // Amenities multi-select filter (support array or comma-separated)
        $selectedAmenities = [];
        if ($request->has('amenities')) {
            $amenitiesInput = $request->input('amenities');
            if (is_array($amenitiesInput)) {
                $selectedAmenities = array_values(array_filter($amenitiesInput));
            } elseif (is_string($amenitiesInput) && strlen(trim($amenitiesInput)) > 0) {
                $selectedAmenities = array_values(array_filter(explode(',', $amenitiesInput)));
            }
        } elseif ($request->filled('amenity')) {
            $selectedAmenities = [$request->input('amenity')];
        }

        foreach ($selectedAmenities as $am) {
            $query->whereJsonContains('amenities', trim($am));
        }

        // Check if user provided coordinates for GPS distance & nearest sorting
        $userLat = $request->filled('user_lat') ? (float) $request->user_lat : null;
        $userLng = $request->filled('user_lng') ? (float) $request->user_lng : null;
        $sort = $request->get('sort', 'default');

        // If user requested 'nearest' sort but coordinates not set, default to Sulaimani Center
        if ($sort === 'nearest' && (!$userLat || !$userLng)) {
            $userLat = 35.5558;
            $userLng = 45.4351;
        }

        if ($userLat && $userLng) {
            $allHotels = $query->get()->each(function ($hotel) use ($userLat, $userLng) {
                $hotel->distance_km = $hotel->distanceTo($userLat, $userLng);
            });

            if ($sort === 'price_asc') {
                $allHotels = $allHotels->sortBy('price_per_night')->values();
            } elseif ($sort === 'price_desc') {
                $allHotels = $allHotels->sortByDesc('price_per_night')->values();
            } elseif ($sort === 'stars_desc') {
                $allHotels = $allHotels->sortByDesc('stars')->values();
            } elseif ($sort === 'nearest') {
                $allHotels = $allHotels->sortBy('distance_km')->values();
            } else {
                $allHotels = $allHotels->sortByDesc('is_featured')
                                       ->sortByDesc('stars')
                                       ->sortBy('price_per_night')
                                       ->values();
            }

            $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
            $perPage = 12;
            $itemsForCurrentPage = $allHotels->forPage($page, $perPage)->values();
            $hotels = new \Illuminate\Pagination\LengthAwarePaginator(
                $itemsForCurrentPage,
                $allHotels->count(),
                $perPage,
                $page,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
            );

            $mapHotelsCollection = $allHotels;
        } else {
            if ($sort === 'price_asc') {
                $query->orderBy('price_per_night');
            } elseif ($sort === 'price_desc') {
                $query->orderByDesc('price_per_night');
            } elseif ($sort === 'stars_desc') {
                $query->orderByDesc('stars');
            } else {
                $query->orderByDesc('is_featured')
                      ->orderByDesc('stars')
                      ->orderBy('price_per_night');
            }

            $hotels = $query->paginate(12)->withQueryString();
            $mapHotelsCollection = (clone $query)->get();
        }

        // Hotels for the map (all matching coordinates)
        $mapHotels = $mapHotelsCollection->map(function ($hotel) {
            return [
                'id' => $hotel->id,
                'name' => $hotel->name,
                'slug' => $hotel->slug,
                'stars' => $hotel->stars,
                'price_per_night' => (float) $hotel->price_per_night,
                'price_currency' => $hotel->price_currency,
                'neighborhood' => $hotel->neighborhood,
                'address' => $hotel->address,
                'latitude' => (float) $hotel->latitude,
                'longitude' => (float) $hotel->longitude,
                'primary_image_url' => $hotel->primary_image_url,
                'phone' => $hotel->phone,
                'whatsapp' => $hotel->clean_whatsapp,
                'distance_km' => isset($hotel->distance_km) ? $hotel->distance_km : null,
                'url' => route('hotels.show', $hotel->slug),
            ];
        });

        // Neighborhoods list for dropdown
        $neighborhoods = Hotel::select('neighborhood')
                              ->distinct()
                              ->pluck('neighborhood')
                              ->filter()
                              ->values();

        $amenityDefinitions = Hotel::$amenityDefinitions;

        if ($request->ajax() || $request->wantsJson() || $request->has('ajax')) {
            return response()->json([
                'success' => true,
                'count' => $hotels->count(),
                'total' => $hotels->total(),
                'html' => view('partials.hotels_list', compact('hotels', 'amenityDefinitions', 'selectedAmenities'))->render(),
                'map_hotels' => $mapHotels,
                'user_lat' => $userLat,
                'user_lng' => $userLng,
            ]);
        }

        return view('hotels.index', compact('hotels', 'mapHotels', 'neighborhoods', 'amenityDefinitions', 'userLat', 'userLng', 'selectedAmenities'));
    }

    public function show(string $slug)
    {
        $hotel = Hotel::where('slug', $slug)->active()->firstOrFail();

        // Similar or nearby hotels
        $relatedHotels = Hotel::active()
            ->where('id', '!=', $hotel->id)
            ->where(function ($q) use ($hotel) {
                $q->where('neighborhood', $hotel->neighborhood)
                  ->orWhere('stars', $hotel->stars);
            })
            ->take(3)
            ->get();

        $amenityDefinitions = Hotel::$amenityDefinitions;

        return view('hotels.show', compact('hotel', 'relatedHotels', 'amenityDefinitions'));
    }
}
