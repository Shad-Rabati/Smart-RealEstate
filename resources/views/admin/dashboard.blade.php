@extends('layouts.admin')

@section('title', 'داشبۆردی سەرپەرشتیار')
@section('page_title', 'کورتەی ئامارەکان و کۆنترۆڵ')

@section('content')
<div class="space-y-8">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-emerald-950/80 via-slate-900 to-slate-900 rounded-3xl p-6 sm:p-8 border border-emerald-500/20 shadow-xl relative overflow-hidden flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div class="space-y-2 relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                <i class="fa-solid fa-crown text-emerald-400"></i> سەرپەرشتیاری باڵای سیستەم
            </span>
            <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                بەخێربێیتەوە بەڕێز {{ Auth::user()->name }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 max-w-xl leading-relaxed">
                لێرەوە دەتوانیت سەرجەم نووسینگەکان، خانووە تۆمارکراوەکان، بەکارهێنەران و ڕێکخستنەکانی نەخشە و نرخی خانوبەرەی سلێمانی کۆنترۆڵ بکەیت.
            </p>
        </div>

        <div class="flex items-center gap-3 relative z-10 flex-shrink-0 flex-wrap">
            <a href="{{ route('admin.analytics.index') }}" class="px-4 py-2.5 rounded-2xl bg-teal-500/10 hover:bg-teal-500/20 text-teal-300 text-xs font-bold border border-teal-500/30 transition-all flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-teal-400"></i>
                <span>ئاماری سەردانیکەران</span>
                <span class="px-1.5 py-0.5 rounded-full bg-teal-500/20 text-[10px] font-mono text-teal-200">{{ number_format($stats['total_visits']) }}</span>
            </a>
            <a href="{{ route('admin.agencies.index') }}" class="px-4 py-2.5 rounded-2xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700 transition-all flex items-center gap-2">
                <i class="fa-solid fa-building text-emerald-400"></i>
                <span>بەڕێوەبردنی نووسینگەکان</span>
            </a>
            <a href="{{ route('admin.properties.index') }}" class="px-4 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-2">
                <i class="fa-solid fa-house-chimney"></i>
                <span>سەرجەم خانووەکان</span>
            </a>
        </div>
    </div>

    <!-- 5 Key Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        <!-- Total Properties -->
        <div class="bg-slate-900/90 rounded-3xl p-5 border border-slate-800/80 shadow-lg hover:border-emerald-500/30 transition-all space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400">سەرجەم خانووەکان</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/15 text-emerald-400 flex items-center justify-center text-sm font-bold border border-emerald-500/20">
                    <i class="fa-solid fa-house-chimney"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <div class="text-3xl font-black text-white tracking-tight">{{ $stats['total_properties'] }}</div>
                <span class="text-xs font-bold text-emerald-400">{{ $stats['available_properties'] }} بەردەستە</span>
            </div>
            <div class="text-[11px] text-slate-500 pt-2 border-t border-slate-800 flex items-center justify-between">
                <span>{{ $stats['rented_properties'] }} کرێدراو</span>
                <span>{{ $stats['reserved_properties'] }} دانوستان</span>
            </div>
        </div>

        <!-- Total Agencies -->
        <div class="bg-slate-900/90 rounded-3xl p-5 border border-slate-800/80 shadow-lg hover:border-blue-500/30 transition-all space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400">نووسینگەکانی خانوبەرە</span>
                <div class="w-10 h-10 rounded-2xl bg-blue-500/15 text-blue-400 flex items-center justify-center text-sm font-bold border border-blue-500/20">
                    <i class="fa-solid fa-building"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <div class="text-3xl font-black text-white tracking-tight">{{ $stats['total_agencies'] }}</div>
                <span class="text-xs font-bold text-blue-400">{{ $stats['verified_agencies'] }} باوەڕپێکراو</span>
            </div>
            <div class="text-[11px] text-slate-500 pt-2 border-t border-slate-800 flex items-center justify-between">
                <span>نووسینگەی فەرمی سلێمانی</span>
                <a href="{{ route('admin.agencies.index') }}" class="text-blue-400 hover:underline">بینین ←</a>
            </div>
        </div>

        <!-- Site Visitors (New) -->
        <div class="bg-slate-900/90 rounded-3xl p-5 border border-teal-500/20 shadow-lg hover:border-teal-500/40 transition-all space-y-3 bg-gradient-to-b from-teal-950/20 to-slate-900/90">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400">سەردانیکەرانی ماڵپەڕ</span>
                <div class="w-10 h-10 rounded-2xl bg-teal-500/15 text-teal-400 flex items-center justify-center text-sm font-bold border border-teal-500/25">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <div class="text-3xl font-black text-white tracking-tight font-mono">{{ number_format($stats['total_visits']) }}</div>
                <span class="text-xs font-bold text-teal-400">+{{ $stats['today_visits'] }} ئەمڕۆ</span>
            </div>
            <div class="text-[11px] text-slate-500 pt-2 border-t border-slate-800 flex items-center justify-between">
                <span>{{ number_format($stats['unique_visitors']) }} کەسی جیاواز</span>
                <a href="{{ route('admin.analytics.index') }}" class="text-teal-400 hover:underline font-bold">ئامارەکان ←</a>
            </div>
        </div>

        <!-- Total Monthly Value -->
        <div class="bg-slate-900/90 rounded-3xl p-5 border border-slate-800/80 shadow-lg hover:border-amber-500/30 transition-all space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400">کۆی نرخی کرێی مانگانە</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-500/15 text-amber-400 flex items-center justify-center text-sm font-bold border border-amber-500/20">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <div class="text-3xl font-black text-white tracking-tight">${{ number_format($stats['total_monthly_value']) }}</div>
            </div>
            <div class="text-[11px] text-slate-500 pt-2 border-t border-slate-800 flex items-center justify-between">
                <span>تێکڕای نرخ</span>
                <span class="text-amber-400 font-bold">${{ number_format($stats['average_price']) }}</span>
            </div>
        </div>

        <!-- System Registered Users -->
        <div class="bg-slate-900/90 rounded-3xl p-5 border border-slate-800/80 shadow-lg hover:border-purple-500/30 transition-all space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400">هەژمارەکان</span>
                <div class="w-10 h-10 rounded-2xl bg-purple-500/15 text-purple-400 flex items-center justify-center text-sm font-bold border border-purple-500/20">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <div class="text-3xl font-black text-white tracking-tight">{{ $stats['total_users'] }}</div>
                <span class="text-xs font-bold text-purple-400">هەژمار</span>
            </div>
            <div class="text-[11px] text-slate-500 pt-2 border-t border-slate-800 flex items-center justify-between">
                <span>سەرپەرشتیار و نووسینگە</span>
                <span class="text-emerald-400 font-bold">پارێزراو</span>
            </div>
        </div>

    </div>

    <!-- Top Neighborhoods in Sulaymaniyah -->
    <div class="bg-slate-900/80 rounded-3xl p-6 border border-slate-800/80 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-black text-white flex items-center gap-2">
                <i class="fa-solid fa-map-pin text-emerald-400"></i>
                <span>زۆرترین گەڕەکە چالاکەکانی سلێمانی</span>
            </h3>
            <span class="text-xs text-slate-400">بەپێی ژمارەی خانووی تۆمارکراو</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            @foreach($topNeighborhoods as $item)
                <div class="bg-slate-950/70 p-3.5 rounded-2xl border border-slate-800 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-slate-200">{{ $item->neighborhood }}</div>
                        <div class="text-[10px] text-slate-500 mt-0.5">گەڕەکی سلێمانی</div>
                    </div>
                    <span class="px-2 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 font-black text-xs">
                        {{ $item->count }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Two Columns: Recent Properties & Recent Agencies -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Recent Properties Table (7 cols) -->
        <div class="lg:col-span-7 bg-slate-900/90 rounded-3xl p-6 border border-slate-800/80 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-black text-white flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-emerald-400"></i>
                    <span>دوایین خانووە تۆمارکراوەکان</span>
                </h3>
                <a href="{{ route('admin.properties.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300">
                    بینینی هەموو ←
                </a>
            </div>

            <div class="divide-y divide-slate-800/70">
                @forelse($recentProperties as $prop)
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <img src="{{ $prop->primary_image_url }}" alt="{{ $prop->title }}" class="w-12 h-12 rounded-xl object-cover border border-slate-800 flex-shrink-0">
                            <div class="min-w-0">
                                <a href="{{ route('properties.show', $prop->slug) }}" target="_blank" class="text-xs font-bold text-slate-200 hover:text-emerald-400 truncate block">
                                    {{ $prop->title }}
                                </a>
                                <div class="text-[11px] text-slate-400 flex items-center gap-2 mt-0.5">
                                    <span>{{ $prop->neighborhood }}</span>
                                    <span>•</span>
                                    <span class="text-slate-500">{{ $prop->agency->name }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-left flex-shrink-0 space-y-1">
                            <div class="text-xs font-extrabold text-emerald-400">{{ $prop->formatted_price }}</div>
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold {{ $prop->status === 'available' ? 'bg-emerald-500/20 text-emerald-400' : ($prop->status === 'rented' ? 'bg-rose-500/20 text-rose-400' : 'bg-slate-700 text-slate-300') }}">
                                {{ $prop->status_text }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-xs text-slate-500">هیچ خانوویەک تۆمار نەکراوە.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Agencies (5 cols) -->
        <div class="lg:col-span-5 bg-slate-900/90 rounded-3xl p-6 border border-slate-800/80 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-black text-white flex items-center gap-2">
                    <i class="fa-solid fa-building text-blue-400"></i>
                    <span>نووسینگە چالاکەکان</span>
                </h3>
                <a href="{{ route('admin.agencies.index') }}" class="text-xs font-bold text-blue-400 hover:text-blue-300">
                    بینینی هەموو ←
                </a>
            </div>

            <div class="divide-y divide-slate-800/70">
                @forelse($recentAgencies as $agency)
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <img src="{{ $agency->logo_url }}" alt="{{ $agency->name }}" class="w-10 h-10 rounded-xl object-cover border border-slate-800 flex-shrink-0">
                            <div class="min-w-0">
                                <a href="{{ route('admin.agencies.show', $agency->id) }}" class="text-xs font-bold text-slate-200 hover:text-emerald-400 truncate block">
                                    {{ $agency->name }}
                                </a>
                                <div class="text-[11px] text-slate-500 truncate mt-0.5">
                                    {{ $agency->phone }} • {{ $agency->owner_name }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $agency->verified ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-slate-800 text-slate-400' }}">
                                {{ $agency->verified ? '✓ باوەڕپێکراو' : 'ئاسایی' }}
                            </span>
                            <span class="text-xs font-extrabold text-slate-400">
                                {{ $agency->properties_count }} خانوو
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-xs text-slate-500">هیچ نووسینگەیەک تۆمار نەکراوە.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
