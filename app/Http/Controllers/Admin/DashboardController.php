<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Property;
use App\Models\SiteVisit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_properties' => Property::count(),
            'available_properties' => Property::where('status', 'available')->count(),
            'rented_properties' => Property::where('status', 'rented')->count(),
            'reserved_properties' => Property::where('status', 'reserved')->count(),
            'total_agencies' => Agency::count(),
            'verified_agencies' => Agency::where('verified', true)->count(),
            'total_users' => User::count(),
            'total_monthly_value' => Property::sum('rent_price'),
            'average_price' => round(Property::avg('rent_price') ?? 0),
            'total_visits' => SiteVisit::count(),
            'today_visits' => SiteVisit::whereDate('visited_at', today())->count(),
            'unique_visitors' => SiteVisit::distinct('ip_address')->count('ip_address'),
        ];

        // Top Neighborhoods with most listings
        $topNeighborhoods = Property::select('neighborhood', DB::raw('count(*) as count'))
            ->groupBy('neighborhood')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        // Recent 6 Properties
        $recentProperties = Property::with('agency')
            ->latest()
            ->take(6)
            ->get();

        // Recent 5 Agencies
        $recentAgencies = Agency::withCount('properties')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'topNeighborhoods', 'recentProperties', 'recentAgencies'));
    }
}
