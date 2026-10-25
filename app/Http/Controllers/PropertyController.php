<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function show(string $slug)
    {
        $property = Property::with('agency')->where('slug', $slug)->firstOrFail();
        $property->increment('views_count');

        // Nearby properties in same neighborhood or closest distance
        $nearbyProperties = Property::with('agency')
            ->where('id', '!=', $property->id)
            ->where('status', 'available')
            ->where('neighborhood', $property->neighborhood)
            ->take(3)
            ->get();

        if ($nearbyProperties->count() < 3) {
            $otherProperties = Property::with('agency')
                ->where('id', '!=', $property->id)
                ->whereNotIn('id', $nearbyProperties->pluck('id'))
                ->where('status', 'available')
                ->take(3 - $nearbyProperties->count())
                ->get();
            $nearbyProperties = $nearbyProperties->concat($otherProperties);
        }

        return view('properties.show', compact('property', 'nearbyProperties'));
    }

    public function favorites(Request $request)
    {
        $properties = collect();

        if ($request->filled('ids')) {
            $ids = array_filter(array_map('intval', explode(',', $request->query('ids', ''))));
            if (!empty($ids)) {
                $properties = Property::with('agency')->whereIn('id', $ids)->get();
            }
        }

        return view('properties.favorites', compact('properties'));
    }

    public function getFavoriteProperties(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => true,
                'count' => 0,
                'html' => '',
            ]);
        }

        $properties = Property::with('agency')
            ->whereIn('id', array_slice($ids, 0, 50))
            ->get();

        return response()->json([
            'success' => true,
            'count' => $properties->count(),
            'html' => view('partials.favorites_grid', compact('properties'))->render(),
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
