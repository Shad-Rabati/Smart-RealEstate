<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Hotel;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Sulaymaniyah known neighborhoods list
     */
    public static array $neighborhoods = [
        'سەرچنار',
        'تووی مەلیک',
        'بەختیاری',
        'گۆیژە',
        'ڕاپەڕین',
        'تاسڵوجە',
        'زەرگەتە',
        'چوارچرا',
        'ئیبراهیم ئەحمەد',
        'شۆڕش',
        'ڕزگاری',
        'کوێستان',
        'قڕگە',
        'کوردسات',
        'مەجیدبەگ',
        'کانێسکان',
    ];

    /**
     * Public Welcome & VIP Landing Hub
     */
    public function landing()
    {
        $counts = cache()->remember('landing_counts', 60, function () {
            return [
                'total' => Property::count(),
                'available' => Property::where('status', 'available')->count(),
                'agencies' => Agency::where('verified', true)->count(),
                'hotels' => Hotel::count(),
            ];
        });

        // VIP / Featured properties showcase
        $vipProperties = cache()->remember('landing_vip_properties', 60, function () {
            return Property::with('agency')
                ->where('status', 'available')
                ->orderByDesc('is_featured')
                ->orderByRaw('CASE WHEN featured_at IS NOT NULL THEN featured_at ELSE updated_at END DESC')
                ->latest('created_at')
                ->take(6)
                ->get();
        });

        // Featured Verified Agencies
        $topAgencies = cache()->remember('landing_top_agencies', 120, function () {
            return Agency::withCount('properties')
                ->where('verified', true)
                ->latest()
                ->take(4)
                ->get();
        });

        // Top Hotels Showcase
        $topHotels = cache()->remember('landing_top_hotels', 120, function () {
            return Hotel::orderByDesc('stars')
                ->latest()
                ->take(3)
                ->get();
        });

        $neighborhoods = self::$neighborhoods;

        return view('landing', compact('vipProperties', 'topAgencies', 'topHotels', 'counts', 'neighborhoods'));
    }

    public function index(Request $request)
    {
        $query = Property::with('agency');

        // Search Keyword
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('neighborhood', 'like', "%{$q}%")
                    ->orWhere('street_address', 'like', "%{$q}%");
            });
        }

        // Neighborhood filter
        if ($request->filled('neighborhood') && $request->neighborhood !== 'all') {
            $query->where('neighborhood', $request->neighborhood);
        }

        // Property Type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('property_type', $request->type);
        }

        // Status filter (available = سەوز, reserved = رەساسی, rented = سور)
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Price range
        if ($request->filled('max_price')) {
            $query->where('rent_price', '<=', (float) $request->max_price);
        }
        if ($request->filled('min_price')) {
            $query->where('rent_price', '>=', (float) $request->min_price);
        }

        // Bedrooms
        if ($request->filled('bedrooms') && $request->bedrooms !== 'all') {
            $query->where('bedrooms', '>=', (int) $request->bedrooms);
        }

        // Furnished
        if ($request->filled('furnished')) {
            $query->where('is_furnished', $request->furnished == '1');
        }

        // Agency filter
        if ($request->filled('agency_id')) {
            $query->where('agency_id', $request->agency_id);
        }

        // Check if user provided coordinates for sorting by nearest
        $userLat = $request->filled('user_lat') ? (float) $request->user_lat : null;
        $userLng = $request->filled('user_lng') ? (float) $request->user_lng : null;

        if ($userLat && $userLng) {
            $allProps = $query->get()->each(function ($prop) use ($userLat, $userLng) {
                $prop->distance_km = $prop->distanceTo($userLat, $userLng);
            });

            if ($request->get('sort') === 'price_asc') {
                $allProps = $allProps->sortBy('rent_price')->values();
            } elseif ($request->get('sort') === 'price_desc') {
                $allProps = $allProps->sortByDesc('rent_price')->values();
            } elseif ($request->get('sort') === 'nearest' || $userLat) {
                $allProps = $allProps->sortBy('distance_km')->values();
            } else {
                $allProps = $allProps->sortByDesc('created_at')->values();
            }

            $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
            $perPage = 12;
            $itemsForCurrentPage = $allProps->forPage($page, $perPage)->values();
            $properties = new \Illuminate\Pagination\LengthAwarePaginator(
                $itemsForCurrentPage,
                $allProps->count(),
                $perPage,
                $page,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
            );
        } else {
            if ($request->get('sort') === 'price_asc') {
                $query->orderBy('rent_price', 'asc');
            } elseif ($request->get('sort') === 'price_desc') {
                $query->orderBy('rent_price', 'desc');
            } else {
                $query->latest();
            }
            $properties = $query->paginate(12)->withQueryString();
        }

        if ($request->ajax() || $request->wantsJson() || $request->has('ajax')) {
            $mapItems = ($userLat && $userLng) ? $allProps : (clone $query)->get();
            $mapProperties = $mapItems->map(function ($prop) {
                return [
                    'id' => $prop->id,
                    'title' => $prop->title,
                    'slug' => $prop->slug,
                    'url' => route('properties.show', $prop->slug),
                    'status' => $prop->status,
                    'status_label' => $prop->status_label,
                    'status_color' => $prop->status_color_name,
                    'status_hex' => $prop->status_hex,
                    'price' => $prop->rent_price,
                    'short_price' => '$' . number_format((float) $prop->rent_price, 0),
                    'formatted_price' => $prop->formatted_price,
                    'property_type' => $prop->property_type,
                    'property_type_label' => $prop->property_type_label,
                    'bedrooms' => $prop->bedrooms,
                    'bathrooms' => $prop->bathrooms,
                    'area_sqm' => $prop->area_sqm,
                    'neighborhood' => $prop->neighborhood,
                    'street_address' => $prop->street_address,
                    'latitude' => (float) $prop->latitude,
                    'longitude' => (float) $prop->longitude,
                    'image' => $prop->primary_image_url,
                    'is_furnished' => $prop->is_furnished,
                    'distance_km' => $prop->distance_km ?? null,
                    'agency' => [
                        'id' => $prop->agency->id,
                        'name' => $prop->agency->name,
                        'phone' => $prop->agency->phone,
                        'whatsapp' => $prop->agency->whatsapp,
                        'whatsapp_link' => $prop->agency->whatsapp_link,
                        'url' => route('agencies.show', $prop->agency->slug),
                    ],
                ];
            });

            return response()->json([
                'success' => true,
                'count' => $properties->count(),
                'total' => $properties->total(),
                'html' => view('partials.properties_list', compact('properties'))->render(),
                'map_properties' => $mapProperties,
            ]);
        }

        // Statistics (cached for 60 seconds to avoid repeated COUNT queries)
        $counts = cache()->remember('property_counts', 60, function () {
            return [
                'total' => Property::count(),
                'available' => Property::where('status', 'available')->count(),
                'reserved' => Property::where('status', 'reserved')->count(),
                'rented' => Property::where('status', 'rented')->count(),
                'agencies' => Agency::count(),
                'hotels' => Hotel::count(),
            ];
        });

        // VIP & Featured Properties for landing showcase
        $vipProperties = cache()->remember('home_vip_properties', 60, function () {
            return Property::with('agency')
                ->where('status', 'available')
                ->orderByDesc('is_featured')
                ->latest()
                ->take(6)
                ->get();
        });

        $neighborhoods = self::$neighborhoods;
        $agencies = cache()->remember('agencies_list', 120, function () {
            return Agency::select('id', 'name')->get();
        });

        return view('home', compact('properties', 'vipProperties', 'counts', 'neighborhoods', 'agencies', 'userLat', 'userLng'));
    }

    /**
     * JSON API endpoint for map pins
     */
    public function mapData(Request $request): JsonResponse
    {
        $query = Property::with('agency:id,slug,name,phone,whatsapp,logo');

        if ($request->filled('neighborhood') && $request->neighborhood !== 'all') {
            $query->where('neighborhood', $request->neighborhood);
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('property_type', $request->type);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('min_price')) {
            $query->where('rent_price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('rent_price', '<=', (float) $request->max_price);
        }

        if ($request->filled('agency_id')) {
            $query->where('agency_id', $request->agency_id);
        }

        $userLat = $request->filled('user_lat') ? (float) $request->user_lat : null;
        $userLng = $request->filled('user_lng') ? (float) $request->user_lng : null;

        $properties = $query->get()->map(function ($prop) use ($userLat, $userLng) {
            $distance = null;
            if ($userLat && $userLng) {
                $distance = $prop->distanceTo($userLat, $userLng);
            }

            return [
                'id' => $prop->id,
                'title' => __($prop->title),
                'slug' => $prop->slug,
                'url' => route('properties.show', $prop->slug),
                'status' => $prop->status,
                'status_label' => $prop->status_label,
                'status_color' => $prop->status_color_name,
                'status_hex' => $prop->status_hex,
                'price' => $prop->rent_price,
                'short_price' => '$' . number_format((float) $prop->rent_price, 0),
                'formatted_price' => $prop->formatted_price,
                'property_type' => $prop->property_type,
                'property_type_label' => $prop->property_type_label,
                'bedrooms' => $prop->bedrooms,
                'bathrooms' => $prop->bathrooms,
                'area_sqm' => $prop->area_sqm,
                'neighborhood' => __($prop->neighborhood),
                'street_address' => __($prop->street_address),
                'latitude' => (float) $prop->latitude,
                'longitude' => (float) $prop->longitude,
                'image' => $prop->primary_image_url,
                'is_furnished' => $prop->is_furnished,
                'distance_km' => $distance,
                'agency' => [
                    'id' => $prop->agency->id,
                    'name' => __($prop->agency->name),
                    'phone' => $prop->agency->phone,
                    'whatsapp' => $prop->agency->whatsapp,
                    'whatsapp_link' => $prop->agency->whatsapp_link,
                    'url' => route('agencies.show', $prop->agency->slug),
                ],
            ];
        });

        if ($request->get('sort') === 'price_asc') {
            $properties = $properties->sortBy('price')->values();
        } elseif ($request->get('sort') === 'price_desc') {
            $properties = $properties->sortByDesc('price')->values();
        } elseif (($request->get('sort') === 'nearest' || $userLat) && $userLat && $userLng) {
            $properties = $properties->sortBy('distance_km')->values();
        }

        return response()->json([
            'success' => true,
            'count' => $properties->count(),
            'properties' => $properties,
        ]);
    }
}
