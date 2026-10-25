<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Property;
use Illuminate\Http\Request;

class AgencyPublicController extends Controller
{
    public function index()
    {
        $agencies = Agency::withCount(['properties', 'availableProperties'])->latest()->paginate(12);
        return view('agencies.index', compact('agencies'));
    }

    public function show(string $slug, Request $request)
    {
        $agency = Agency::withCount(['properties', 'availableProperties', 'rentedProperties', 'reservedProperties'])
            ->where('slug', $slug)
            ->firstOrFail();

        $query = $agency->properties()->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $properties = $query->paginate(9)->withQueryString();

        return view('agencies.show', compact('agency', 'properties'));
    }
}
