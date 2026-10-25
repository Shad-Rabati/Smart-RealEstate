<?php

namespace Database\Seeders;

use App\Models\SiteVisit;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SiteVisitSeeder extends Seeder
{
    public function run(): void
    {
        // Only seed if empty
        if (SiteVisit::count() > 50) {
            return;
        }

        $pages = [
            ['url' => '/', 'title' => 'پەڕەی سەرەکی و نەخشەی سلێمانی', 'weight' => 50],
            ['url' => '/agencies', 'title' => 'بەشی نووسینگەکانی خانوبەرە', 'weight' => 20],
            ['url' => '/agencies/rebin-real-estate', 'title' => 'نووسینگەی ڕێبین بۆ خانوبەرە', 'weight' => 8],
            ['url' => '/agencies/bakhtiari-real-estate', 'title' => 'نووسینگەی بەختیاری بۆ خانوبەرە', 'weight' => 7],
            ['url' => '/properties/villa-bakhtiari', 'title' => 'ڤێلای شاهانە لە بەختیاری', 'weight' => 6],
            ['url' => '/properties/apartment-sarchinar', 'title' => 'شوقەی مۆدێرن لە سەرچنار', 'weight' => 5],
            ['url' => '/agency/register', 'title' => 'فۆرمی تۆماری نووسینگە', 'weight' => 4],
        ];

        $devices = ['mobile', 'mobile', 'mobile', 'mobile', 'desktop', 'desktop', 'tablet'];
        $browsers = ['Chrome', 'Chrome', 'Safari', 'Safari', 'Edge', 'Firefox'];
        
        $ips = [
            '185.120.44.12', '185.120.44.15', '37.236.192.8', '37.236.192.14',
            '82.145.220.1', '82.145.220.19', '151.236.18.4', '151.236.18.22',
            '95.141.24.5', '149.255.225.10', '185.70.184.2', '176.241.80.3',
            '109.127.80.9', '37.239.112.5', '185.107.56.3', '151.248.120.8',
        ];

        $records = [];
        $now = Carbon::now();

        // Generate past 14 days of realistic traffic
        for ($daysAgo = 13; $daysAgo >= 0; $daysAgo--) {
            $date = $now->copy()->subDays($daysAgo);
            
            // Daily traffic between 45 and 110 visits, higher on recent days
            $dailyCount = rand(45, 80) + (14 - $daysAgo) * 2;

            for ($i = 0; $i < $dailyCount; $i++) {
                // Random hour during day/evening
                $hour = rand(0, 100) < 85 ? rand(9, 23) : rand(0, 8);
                $minute = rand(0, 59);
                $visitTime = $date->copy()->setTime($hour, $minute, rand(0, 59));

                // Weighted random page
                $rand = rand(1, 100);
                $cumulative = 0;
                $chosenPage = $pages[0];
                foreach ($pages as $p) {
                    $cumulative += $p['weight'];
                    if ($rand <= $cumulative) {
                        $chosenPage = $p;
                        break;
                    }
                }

                $device = $devices[array_rand($devices)];
                $browser = $browsers[array_rand($browsers)];
                $ip = $ips[array_rand($ips)];

                $records[] = [
                    'ip_address' => $ip,
                    'user_agent' => "Mozilla/5.0 ({$device}; CPU OS like Mac OS X) AppleWebKit/537.36",
                    'device_type' => $device,
                    'browser' => $browser,
                    'page_url' => $chosenPage['url'],
                    'page_title' => $chosenPage['title'],
                    'visited_at' => $visitTime,
                    'created_at' => $visitTime,
                    'updated_at' => $visitTime,
                ];

                if (count($records) >= 200) {
                    SiteVisit::insert($records);
                    $records = [];
                }
            }
        }

        if (!empty($records)) {
            SiteVisit::insert($records);
        }
    }
}
