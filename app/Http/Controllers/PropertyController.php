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
}
