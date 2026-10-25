@extends('layouts.admin')

@section('title', 'بەڕێوەبردنی خانووە VIPیەکان | پانێڵی سەرپەرشتیار')
@section('header_title', 'خانووە VIPیەکان (ڕیکلامی لاپەڕەی سەرەکی)')

@section('content')
<div class="space-y-8">

    <!-- Top Headline & Action Card -->
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-amber-950/40 to-slate-900 border border-amber-500/30 rounded-3xl p-6 sm:p-8 shadow-xl">
        <div class="absolute -top-20 -left-20 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/15 border border-amber-500/30 text-amber-300 text-xs font-black">
                    <i class="fa-solid fa-crown text-amber-400"></i>
                    <span>بەشی ڕیکلامی VIP</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                    بەڕێوەبردنی موڵک و خانووە ناوازەکان (VIP)
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                    ئەم خانووانە ڕاستەوخۆ لە پێشەنگی لاپەڕەی سەرەکی ماڵپەڕەکەدا بە شێوەیەکی زێڕین و سەرنجڕاکێش دەردەکەون. هەر خانوویەکی نوێ زیاد بکەیت، بە شێوەیەکی ئۆتۆماتیکی دەچێتە یەکەم شوێن (سەرەتاوە).
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs shadow-lg shadow-amber-500/20 transition-all hover:scale-105 active:scale-95">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>بینینی لاپەڕەی سەرەکی</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards (3 Counters) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Stat 1: Total VIP -->
        <div class="bg-slate-900/90 rounded-2xl p-5 border border-amber-500/30 shadow-lg flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-amber-400/90">سەرجەم خانووە VIPیەکان</div>
                <div class="text-2xl sm:text-3xl font-black text-white font-mono mt-1">{{ $stats['total_vip'] }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">موڵکی هەڵبژێردراو</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-500/15 text-amber-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-crown"></i>
            </div>
        </div>

        <!-- Stat 2: Active on Home -->
        <div class="bg-slate-900/90 rounded-2xl p-5 border border-emerald-500/30 shadow-lg flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-emerald-400">چالاک لە لاپەڕەی سەرەکی</div>
                <div class="text-2xl sm:text-3xl font-black text-white font-mono mt-1">{{ $stats['active_on_home'] }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">خانووی بەردەست (سەوز)</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/15 text-emerald-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <!-- Stat 3: Agencies with VIP -->
        <div class="bg-slate-900/90 rounded-2xl p-5 border border-slate-800 shadow-lg flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-slate-400">نووسینگە بەشداربووەکان</div>
                <div class="text-2xl sm:text-3xl font-black text-white font-mono mt-1">{{ $stats['agencies_with_vip'] }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">نووسینگەی خاوەن ڕیکلام</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-500/15 text-blue-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-building"></i>
            </div>
        </div>
    </div>

    <!-- Quick Add Property to VIP Section -->
    <div class="bg-slate-900/90 rounded-3xl p-6 border border-slate-800 shadow-xl space-y-4">
        <div class="flex items-center gap-2 text-white font-black text-sm">
            <i class="fa-solid fa-plus-circle text-amber-400"></i>
            <span>زیادکردنی خێرای خانوو بۆ لیستی VIP</span>
        </div>

        <form action="{{ route('admin.vip.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-4 gap-3">
            @csrf
            <div class="lg:col-span-3">
                <select name="property_id" required class="w-full px-4 py-3 rounded-2xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:border-amber-400 cursor-pointer">
                    <option value="">-- خانوویەک لە خانووە بەردەستەکان هەڵبژێرە بۆ کردنی بە VIP --</option>
                    @foreach($availableCandidates as $candidate)
                        <option value="{{ $candidate->id }}">
                            {{ $candidate->title }} • [{{ $candidate->agency?->name ?? 'نووسینگە' }}] • {{ $candidate->neighborhood }} (${{ number_format($candidate->rent_price) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="w-full h-full py-3 px-5 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs shadow-md shadow-amber-500/20 transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
                    <i class="fa-solid fa-crown text-slate-950 text-sm"></i>
                    <span>بیکە بە VIP (لە سەرەتاوە)</span>
                </button>
            </div>
        </form>
    </div>

    <!-- VIP Properties List Table -->
    <div class="bg-slate-900/90 rounded-3xl border border-slate-800 overflow-hidden shadow-xl">
        <div class="p-6 border-b border-slate-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-black text-white flex items-center gap-2">
                    <i class="fa-solid fa-list-ol text-amber-400"></i>
                    <span>ڕیزبەندی خانووە VIPیەکان بەپێی دەرکەوتن لە لاپەڕەی سەرەکی</span>
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    خانووەکان بەپێی نوێترین دانان لە سەرەتاوە بە ڕێکوپێکی ڕیزکراون
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-950/70 border-b border-slate-800 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4 text-center">ڕیزبەندی</th>
                        <th class="py-3.5 px-4">خانوو</th>
                        <th class="py-3.5 px-4">نووسینگە</th>
                        <th class="py-3.5 px-4">گەڕەک</th>
                        <th class="py-3.5 px-4">نرخی کرێ</th>
                        <th class="py-3.5 px-4">دۆخ</th>
                        <th class="py-3.5 px-4">کاتی دانان بە VIP</th>
                        <th class="py-3.5 px-4 text-center">کردارەکان</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-slate-300">
                    @forelse($vipProperties as $index => $prop)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <!-- Rank Number -->
                            <td class="py-3 px-4 text-center">
                                @if($index === 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-amber-500 text-slate-950 font-black text-xs shadow-md shadow-amber-500/30">
                                        <i class="fa-solid fa-crown text-[10px]"></i> #1 پێشەنگ
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl bg-slate-800 text-amber-400 font-black font-mono text-xs border border-slate-700">
                                        #{{ $vipProperties->firstItem() + $index }}
                                    </span>
                                @endif
                            </td>

                            <!-- Image & Title -->
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3 min-w-[220px]">
                                    <img src="{{ $prop->primary_image_url }}" alt="{{ $prop->title }}" class="w-12 h-12 rounded-2xl object-cover border border-amber-500/30 flex-shrink-0">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <a href="{{ route('properties.show', $prop->slug) }}" target="_blank" class="font-black text-slate-100 hover:text-amber-300 truncate block">
                                                {{ $prop->title }}
                                            </a>
                                            @if($prop->featured_at && $prop->featured_at->gt(now()->subDays(3)))
                                                <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-[9px] font-black animate-pulse">
                                                    نوێ
                                                </span>
                                            @endif
                                            @if($prop->has_video)
                                                <span class="px-1.5 py-0.5 rounded-full bg-rose-500/20 text-rose-400 border border-rose-500/30 text-[9px] font-black" title="خاوەنی ڤیدیۆیە">
                                                    <i class="fa-solid fa-play text-[7px]"></i> ڤیدیۆ
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-[10px] text-slate-400 font-medium">
                                            {{ $prop->property_type_label }} • {{ $prop->bedrooms }} ژوور • {{ $prop->area_sqm }}م²
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Agency -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($prop->agency)
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $prop->agency->logo_url }}" alt="{{ $prop->agency->name }}" class="w-6 h-6 rounded-full object-cover border border-slate-700">
                                        <span class="font-bold text-slate-200">{{ $prop->agency->name }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-500">نادیار</span>
                                @endif
                            </td>

                            <!-- Neighborhood -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-xl bg-slate-950 border border-slate-800 text-slate-300 text-xs font-bold">
                                    {{ $prop->neighborhood }}
                                </span>
                            </td>

                            <!-- Price -->
                            <td class="py-3 px-4 whitespace-nowrap font-mono font-black text-emerald-400">
                                {{ $prop->formatted_price }}
                            </td>

                            <!-- Status -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($prop->status === 'available')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-emerald-500/15 text-emerald-400 text-[11px] font-bold border border-emerald-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> بەردەست
                                    </span>
                                @elseif($prop->status === 'reserved')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-slate-500/15 text-slate-400 text-[11px] font-bold border border-slate-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> دانوستان
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-rose-500/15 text-rose-400 text-[11px] font-bold border border-rose-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> گیراوە
                                    </span>
                                @endif
                            </td>

                            <!-- Featured At -->
                            <td class="py-3 px-4 whitespace-nowrap text-slate-400 text-[11px]">
                                @if($prop->featured_at)
                                    <div class="font-bold text-slate-200">{{ $prop->featured_at->diffForHumans() }}</div>
                                    <div class="text-[10px] text-slate-500 font-mono">{{ $prop->featured_at->format('Y/m/d H:i') }}</div>
                                @else
                                    <span class="text-slate-500">پێشتر دانراوە</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('properties.show', $prop->slug) }}" target="_blank" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-colors" title="بینین لە ماڵپەڕ">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>

                                    <form action="{{ route('admin.vip.destroy', $prop->id) }}" method="POST" onsubmit="return confirm('ئایا دڵنیایت لە لابردنی ئەم خانووە لە لیستی VIP؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs font-bold transition-all hover:scale-105" title="لابردن لە VIP">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                            <span>لابردن لە VIP</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-crown text-amber-500/30 text-4xl mb-3 block"></i>
                                <span class="font-bold text-sm block">هیچ خانوویەک لە لیستی VIP دانەنراوە</span>
                                <span class="text-xs text-slate-500 mt-1 block">دەتوانیت لە فۆڕمی سەرەوە خانوویەک هەڵبژێریت و بیکەیت بە VIP</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vipProperties->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $vipProperties->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
