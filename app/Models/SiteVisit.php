<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SiteVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'page_url',
        'page_title',
        'visited_at',
    ];

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }

    /**
     * Record a site visit from an incoming HTTP request.
     */
    public static function record(Request $request, ?string $title = null): ?self
    {
        // Don't track admin, api, livewire, or debugbar routes
        if ($request->is('admin*') || $request->is('api*') || $request->is('_debugbar*') || $request->is('up')) {
            return null;
        }

        // Don't count logged-in admin's own browsing as public visits
        if (auth()->check() && auth()->user()->role === 'admin') {
            return null;
        }

        $ip = $request->ip();
        $path = '/' . ltrim($request->path(), '/');

        // Prevent rapid refresh spam from the same IP within 20 seconds
        $recentSameVisit = self::where('ip_address', $ip)
            ->where('page_url', $path)
            ->where('visited_at', '>=', now()->subSeconds(20))
            ->exists();

        if ($recentSameVisit) {
            return null;
        }

        $userAgent = $request->userAgent() ?? '';
        $isMobile = preg_match('/Mobile|Android|iPhone|iPod/i', $userAgent);
        $isTablet = preg_match('/iPad|Tablet/i', $userAgent);
        $deviceType = $isTablet ? 'tablet' : ($isMobile ? 'mobile' : 'desktop');

        $browser = 'Chrome';
        if (str_contains($userAgent, 'Firefox')) {
            $browser = 'Firefox';
        } elseif (str_contains($userAgent, 'Edg')) {
            $browser = 'Edge';
        } elseif (str_contains($userAgent, 'Safari') && !str_contains($userAgent, 'Chrome')) {
            $browser = 'Safari';
        } elseif (str_contains($userAgent, 'Opera') || str_contains($userAgent, 'OPR')) {
            $browser = 'Opera';
        }

        if (!$title) {
            if ($path === '/' || $path === '') {
                $title = 'پەڕەی سەرەکی و نەخشەی سلێمانی';
            } elseif ($path === '/properties') {
                $title = 'گەڕان و لیستی هەموو خانووەکان';
            } elseif (str_starts_with($path, '/properties/')) {
                $title = 'بینینی وردەکاری خانوو';
            } elseif ($path === '/agencies') {
                $title = 'لیستی نووسینگەکانی خانوبەرە';
            } elseif (str_starts_with($path, '/agencies/')) {
                $title = 'پەڕەی نووسینگەی خانوبەرە';
            } elseif (str_starts_with($path, '/agency/register')) {
                $title = 'فۆرمی تۆماری نووسینگە';
            } elseif (str_starts_with($path, '/agency/login')) {
                $title = 'چوونەژوورەوەی نووسینگە';
            } else {
                $title = $path;
            }
        }

        return self::create([
            'ip_address' => $request->ip(),
            'user_agent' => substr($userAgent, 0, 500),
            'device_type' => $deviceType,
            'browser' => $browser,
            'page_url' => substr($path, 0, 255),
            'page_title' => $title,
            'visited_at' => now(),
        ]);
    }
}
