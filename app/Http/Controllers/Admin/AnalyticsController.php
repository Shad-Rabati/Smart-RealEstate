<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteVisit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        $now = Carbon::now();

        // 1. KPI Counts
        $totalViews = SiteVisit::count();
        $uniqueVisitors = SiteVisit::distinct('ip_address')->count('ip_address');
        $todayViews = SiteVisit::whereDate('visited_at', $now->toDateString())->count();
        $todayUniques = SiteVisit::whereDate('visited_at', $now->toDateString())->distinct('ip_address')->count('ip_address');
        $weekViews = SiteVisit::where('visited_at', '>=', $now->copy()->subDays(7))->count();
        $monthViews = SiteVisit::where('visited_at', '>=', $now->copy()->subDays(30))->count();

        // 2. Device Breakdown
        $devices = SiteVisit::select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->pluck('count', 'device_type')
            ->toArray();

        $mobileCount = $devices['mobile'] ?? 0;
        $desktopCount = $devices['desktop'] ?? 0;
        $tabletCount = $devices['tablet'] ?? 0;
        $totalDevices = max(1, $totalViews);

        $deviceStats = [
            'mobile_percent' => round(($mobileCount / $totalDevices) * 100),
            'desktop_percent' => round(($desktopCount / $totalDevices) * 100),
            'tablet_percent' => round(($tabletCount / $totalDevices) * 100),
            'mobile_count' => $mobileCount,
            'desktop_count' => $desktopCount,
            'tablet_count' => $tabletCount,
        ];

        // 3. 7-Day Chart Data with Kurdish Weekday Names
        $kurdishDays = [
            'Saturday' => 'شەممە',
            'Sunday' => 'یەکشەممە',
            'Monday' => 'دووشەممە',
            'Tuesday' => 'سێشەممە',
            'Wednesday' => 'چوارشەممە',
            'Thursday' => 'پێنجشەممە',
            'Friday' => 'هەینی',
        ];

        $chartDays = [];
        $maxViews = 1;

        for ($i = 6; $i >= 0; $i--) {
            $dayDate = $now->copy()->subDays($i);
            $dayDateStr = $dayDate->toDateString();
            $viewsCount = SiteVisit::whereDate('visited_at', $dayDateStr)->count();
            $uniqueCount = SiteVisit::whereDate('visited_at', $dayDateStr)->distinct('ip_address')->count('ip_address');

            if ($viewsCount > $maxViews) {
                $maxViews = $viewsCount;
            }

            $englishDay = $dayDate->format('l');
            $kurdishDay = $kurdishDays[$englishDay] ?? $englishDay;

            $chartDays[] = [
                'date' => $dayDate->format('m/d'),
                'kurdish_day' => $kurdishDay,
                'views' => $viewsCount,
                'uniques' => $uniqueCount,
            ];
        }

        // Calculate height % for CSS bars
        foreach ($chartDays as &$cd) {
            $cd['height_percent'] = max(12, round(($cd['views'] / $maxViews) * 100));
        }
        unset($cd);

        // 4. Top Visited Pages
        $topPages = SiteVisit::select('page_url', 'page_title', DB::raw('count(*) as total_views'), DB::raw('count(distinct ip_address) as unique_views'))
            ->groupBy('page_url', 'page_title')
            ->orderByDesc('total_views')
            ->take(7)
            ->get();

        // 5. Browser Breakdown
        $browsers = SiteVisit::select('browser', DB::raw('count(*) as count'))
            ->groupBy('browser')
            ->orderByDesc('count')
            ->take(4)
            ->get();

        // 6. Live Recent Visits Log with optional filters
        $logQuery = SiteVisit::latest('visited_at');

        if ($request->filled('device') && $request->input('device') !== 'all') {
            $logQuery->where('device_type', $request->input('device'));
        }

        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $logQuery->where(function ($q) use ($search) {
                $q->where('page_url', 'like', "%{$search}%")
                  ->orWhere('page_title', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $recentVisits = $logQuery->paginate(15)->withQueryString();

        return view('admin.analytics.index', compact(
            'totalViews',
            'uniqueVisitors',
            'todayViews',
            'todayUniques',
            'weekViews',
            'monthViews',
            'deviceStats',
            'chartDays',
            'topPages',
            'browsers',
            'recentVisits'
        ));
    }

    /**
     * Clear / reset all site visit data back to zero.
     */
    public function reset(): \Illuminate\Http\RedirectResponse
    {
        SiteVisit::truncate();

        return redirect()->route('admin.analytics.index')->with('success', 'سەرجەم داتاکانی سەردانیکەران بە سەرکەوتوویی سفرکرانەوە.');
    }
}
