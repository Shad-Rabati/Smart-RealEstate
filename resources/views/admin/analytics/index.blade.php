@extends('layouts.admin')

@section('title', 'ئاماری سەردانیکەرانی ماڵپەڕ')
@section('page_title', 'ئامار و هاتووچۆی سەردانیکەران')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900/60 p-4 rounded-3xl border border-slate-800">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-500/15 text-emerald-400 flex items-center justify-center text-lg border border-emerald-500/20">
                <i class="fa-solid fa-satellite-dish animate-pulse"></i>
            </div>
            <div>
                <h2 class="text-sm font-black text-white flex items-center gap-2">
                    <span>سیستەمی ڕاستەوخۆی چاودێری سەردانیکەران</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/25">تۆماری ڕاستەقینە (Live)</span>
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">هەر سەردانێک کە دەکرێت ڕاستەوخۆ بەپێی IP، جۆری ئامێر و وێبگەڕ تۆمار دەکرێت</p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <form action="{{ route('admin.analytics.reset') }}" method="POST" onsubmit="return confirm('ئایا دڵنیایت لە سفرکردنەوە و پاککردنەوەی تەواوی داتاکانی سەردانیکەران؟ ئەم کارە ناگەڕێتەوە.');">
                @csrf
                <button type="submit" class="px-3.5 py-2 rounded-2xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 text-xs font-bold border border-rose-500/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-trash-can text-rose-400"></i>
                    <span>سفرکردنەوەی تەواوی ئامارەکان (Reset to 0)</span>
                </button>
            </form>
        </div>
    </div>

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Pageviews -->
        <div class="bg-slate-900/90 rounded-3xl p-5 border border-slate-800/80 shadow-lg relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400">کۆی گشتی سەردانەکان</span>
                    <div class="text-2xl font-black text-white mt-1 font-mono tracking-tight">
                        {{ number_format($totalViews) }}
                    </div>
                    <span class="text-[10px] text-emerald-400 font-bold block mt-1">
                        {{ number_format($monthViews) }} سەردان لە ٣٠ ڕۆژدا
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl shadow-md shadow-emerald-500/10">
                    <i class="fa-solid fa-eye"></i>
                </div>
            </div>
        </div>

        <!-- Unique Visitors -->
        <div class="bg-slate-900/90 rounded-3xl p-5 border border-slate-800/80 shadow-lg relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400">سەردانیکەرانی جیاواز (Unique)</span>
                    <div class="text-2xl font-black text-white mt-1 font-mono tracking-tight">
                        {{ number_format($uniqueVisitors) }}
                    </div>
                    <span class="text-[10px] text-teal-400 font-bold block mt-1">
                        بەپێی ناونیشانی IP جیاواز
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-teal-500/10 border border-teal-500/20 text-teal-400 flex items-center justify-center text-xl shadow-md shadow-teal-500/10">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
        </div>

        <!-- Today's Visits -->
        <div class="bg-slate-900/90 rounded-3xl p-5 border border-slate-800/80 shadow-lg relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400">سەردانەکانی ئەمڕۆ</span>
                    <div class="text-2xl font-black text-amber-400 mt-1 font-mono tracking-tight">
                        {{ number_format($todayViews) }}
                    </div>
                    <span class="text-[10px] text-slate-400 font-bold block mt-1">
                        {{ number_format($todayUniques) }} کەسی جیاواز
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-xl shadow-md shadow-amber-500/10">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
            </div>
        </div>

        <!-- Devices Share -->
        <div class="bg-slate-900/90 rounded-3xl p-5 border border-slate-800/80 shadow-lg relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400">ڕێژەی مۆبایل بەرامبەر PC</span>
                    <div class="text-xl font-black text-cyan-400 mt-1 font-mono tracking-tight">
                        {{ $deviceStats['mobile_percent'] }}% <span class="text-xs text-slate-400 font-sans">مۆبایل</span>
                    </div>
                    <span class="text-[10px] text-slate-400 font-bold block mt-1">
                        {{ $deviceStats['desktop_percent'] }}% کۆمپیوتەر • {{ $deviceStats['tablet_percent'] }}% تابلێت
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center text-xl shadow-md shadow-cyan-500/10">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- 7-Day Visual Activity Bar Chart -->
    <div class="bg-slate-900/90 rounded-3xl p-6 border border-slate-800 shadow-xl space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-black text-white flex items-center gap-2">
                    <i class="fa-solid fa-chart-simple text-emerald-400"></i>
                    <span>هاتووچۆی ٧ ڕۆژی ڕابردوو</span>
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">بەراوردی ژمارەی سەردانەکان لە سەرجەم ڕۆژەکانی هەفتەدا</p>
            </div>
            <span class="px-3 py-1 rounded-xl bg-slate-800 text-[11px] font-bold text-slate-300 border border-slate-700">
                کۆی ئەم هەفتەیە: {{ number_format($weekViews) }} سەردان
            </span>
        </div>

        <!-- CSS Bar Graph -->
        <div class="pt-6 pb-2">
            <div class="grid grid-cols-7 gap-2 sm:gap-4 items-end h-44 border-b border-slate-800/80 pb-2">
                @foreach($chartDays as $day)
                    <div class="flex flex-col items-center gap-2 h-full justify-end group">
                        
                        <!-- Tooltip on hover -->
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity bg-slate-950 border border-slate-700 text-white text-[10px] rounded-lg py-1 px-2 pointer-events-none whitespace-nowrap shadow-lg">
                            <span class="font-bold text-emerald-400 font-mono">{{ $day['views'] }}</span> سەردان
                        </div>

                        <!-- Bar -->
                        <div class="w-full max-w-[42px] bg-gradient-to-t from-emerald-600 via-emerald-500 to-teal-400 rounded-xl transition-all duration-300 group-hover:from-emerald-400 group-hover:to-teal-300 shadow-lg shadow-emerald-500/15 group-hover:shadow-emerald-500/30"
                             style="height: {{ $day['height_percent'] }}%;">
                        </div>

                        <!-- Day Label -->
                        <div class="text-center pt-1">
                            <span class="text-[11px] font-bold text-slate-300 block leading-tight">{{ $day['kurdish_day'] }}</span>
                            <span class="text-[9px] text-slate-500 font-mono block">{{ $day['date'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Two Columns: Top Visited Pages & Devices/Browsers -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Top Pages (2 Cols) -->
        <div class="lg:col-span-2 bg-slate-900/90 rounded-3xl p-6 border border-slate-800 shadow-xl space-y-4">
            <h3 class="text-sm font-black text-white flex items-center gap-2">
                <i class="fa-solid fa-fire text-amber-400"></i>
                <span>پڕبینەرترین بەش و لاپەڕەکانی ماڵپەڕ</span>
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-slate-950/70 border-b border-slate-800 text-slate-400 font-bold uppercase text-[10px]">
                        <tr>
                            <th class="py-3 px-3">بەش / ناونیشانی لاپەڕە</th>
                            <th class="py-3 px-3">بەستەر</th>
                            <th class="py-3 px-3 text-center">بینینەکان</th>
                            <th class="py-3 px-3 text-center">کەسی جیاواز</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/70 text-slate-300">
                        @foreach($topPages as $page)
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="py-3 px-3">
                                    <div class="font-bold text-white flex items-center gap-2">
                                        <i class="fa-solid fa-file-lines text-slate-500 text-[11px]"></i>
                                        <span>{{ $page->page_title ?? 'لاپەڕە' }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <a href="{{ $page->page_url }}" target="_blank" class="text-emerald-400 hover:underline font-mono text-[11px]" dir="ltr">
                                        {{ $page->page_url }}
                                    </a>
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-bold text-slate-100">
                                    {{ number_format($page->total_views) }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono text-slate-400">
                                    {{ number_format($page->unique_views) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Devices & Browsers (1 Col) -->
        <div class="bg-slate-900/90 rounded-3xl p-6 border border-slate-800 shadow-xl space-y-6 flex flex-col justify-between">
            
            <!-- Devices Progress Bars -->
            <div class="space-y-4">
                <h3 class="text-sm font-black text-white flex items-center gap-2">
                    <i class="fa-solid fa-desktop text-cyan-400"></i>
                    <span>جۆری ئامێری بەکارهێنەران</span>
                </h3>

                <!-- Mobile Bar -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-mobile-screen text-emerald-400 text-xs"></i> مۆبایل (Mobile)
                        </span>
                        <span class="text-emerald-400 font-mono">{{ $deviceStats['mobile_percent'] }}%</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-950 overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $deviceStats['mobile_percent'] }}%;"></div>
                    </div>
                </div>

                <!-- Desktop Bar -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-laptop text-blue-400 text-xs"></i> کۆمپیوتەر (Desktop)
                        </span>
                        <span class="text-blue-400 font-mono">{{ $deviceStats['desktop_percent'] }}%</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-950 overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full" style="width: {{ $deviceStats['desktop_percent'] }}%;"></div>
                    </div>
                </div>

                <!-- Tablet Bar -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-tablet-screen-button text-purple-400 text-xs"></i> تابلێت (Tablet)
                        </span>
                        <span class="text-purple-400 font-mono">{{ $deviceStats['tablet_percent'] }}%</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-950 overflow-hidden">
                        <div class="h-full bg-purple-500 rounded-full" style="width: {{ $deviceStats['tablet_percent'] }}%;"></div>
                    </div>
                </div>
            </div>

            <!-- Top Browsers -->
            <div class="space-y-3 pt-4 border-t border-slate-800">
                <h4 class="text-xs font-black text-slate-300 flex items-center gap-2">
                    <i class="fa-brands fa-chrome text-amber-400"></i>
                    <span>براوزەرە سەرەکییەکان</span>
                </h4>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    @foreach($browsers as $b)
                        <div class="p-2.5 rounded-xl bg-slate-950/70 border border-slate-800 flex items-center justify-between">
                            <span class="text-slate-300 font-semibold">{{ $b->browser ?? 'Other' }}</span>
                            <span class="font-bold text-white font-mono">{{ number_format($b->count) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

    <!-- Live Recent Visits Log Table -->
    <div class="bg-slate-900/90 rounded-3xl p-6 border border-slate-800 shadow-xl space-y-4">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-black text-white flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-emerald-400"></i>
                    <span>تۆماری سەردانە ڕاستەوخۆکان (Live Activity Log)</span>
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">دوایین کەسەکان کە لە سلێمانی و ناوچەکانی ترەوە سەردانی پلاتفۆرمەکەیان کردووە</p>
            </div>

            <!-- Filter Controls -->
            <form method="GET" action="{{ route('admin.analytics.index') }}" class="flex items-center gap-2">
                <select name="device" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:border-emerald-500">
                    <option value="all">هەموو ئامێرەکان</option>
                    <option value="mobile" {{ request('device') === 'mobile' ? 'selected' : '' }}>مۆبایل</option>
                    <option value="desktop" {{ request('device') === 'desktop' ? 'selected' : '' }}>کۆمپیوتەر</option>
                    <option value="tablet" {{ request('device') === 'tablet' ? 'selected' : '' }}>تابلێت</option>
                </select>

                <div class="relative w-44">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="گەڕان بەپێی IP یان لاپەڕە..."
                        class="w-full px-3 py-1.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder:text-slate-500 focus:outline-none focus:border-emerald-500">
                </div>

                <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition-colors">
                    گەڕان
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-950/70 border-b border-slate-800 text-slate-400 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="py-3 px-4">کات</th>
                        <th class="py-3 px-4">لاپەڕەی بینراو</th>
                        <th class="py-3 px-4">ئامێر</th>
                        <th class="py-3 px-4">براوزەر</th>
                        <th class="py-3 px-4">ناونیشانی IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-slate-300">
                    @forelse($recentVisits as $visit)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="font-mono text-slate-300 text-xs" dir="ltr">
                                    {{ $visit->visited_at->diffForHumans() }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-white flex items-center gap-1.5">
                                    <span>{{ $visit->page_title ?? 'لاپەڕە' }}</span>
                                </div>
                                <span class="text-[10px] text-slate-500 font-mono" dir="ltr">{{ $visit->page_url }}</span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($visit->device_type === 'mobile')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/25">
                                        <i class="fa-solid fa-mobile-screen"></i> مۆبایل
                                    </span>
                                @elseif($visit->device_type === 'desktop')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/15 text-blue-400 border border-blue-500/25">
                                        <i class="fa-solid fa-laptop"></i> کۆمپیوتەر
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/15 text-purple-400 border border-purple-500/25">
                                        <i class="fa-solid fa-tablet-screen-button"></i> تابلێت
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap font-medium text-slate-300">
                                {{ $visit->browser ?? 'Chrome' }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap font-mono text-slate-400" dir="ltr">
                                {{ $visit->ip_address }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-500 text-xs">
                                هیچ تۆمارێکی سەردان نەدۆزرایەوە.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pt-2">
            {{ $recentVisits->links() }}
        </div>

    </div>

</div>
@endsection
