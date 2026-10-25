@extends('layouts.app')

@section('title', 'پانێڵی بەڕێوەبردنی نووسینگە | خانوبەرەی سلێمانی')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Agency Dashboard Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <img src="{{ $agency->logo_url }}" alt="{{ $agency->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border-2 border-emerald-400">
            <div>
                <span class="text-xs font-bold text-emerald-400 block mb-1">پانێڵی فەرمی نووسینگە</span>
                <h1 class="text-xl sm:text-2xl font-black">{{ $agency->name }}</h1>
                <p class="text-xs text-slate-300 mt-1">
                    بەخێربێیت! لێرەوە دەتوانیت خانووەکانت زیادبکەیت، دۆخەکانیان بگۆڕیت، و لەسەر نەخشەی سلێمانی بڵاویان بکەیتەوە.
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('agency.credits') }}" class="px-4 py-3 rounded-2xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-xs sm:text-sm shadow-md transition-all flex items-center gap-2 hover:scale-[1.02]">
                <i class="fa-solid fa-coins text-amber-900 text-base"></i>
                <span>باڵانسی کریدت: {{ $agency->credits }}</span>
            </a>

            <a href="{{ route('agency.properties.create') }}" class="px-5 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs sm:text-sm shadow-md transition-all flex items-center gap-2 hover:scale-[1.02]">
                <i class="fa-solid fa-circle-plus text-base"></i>
                <span>زیادکردنی خانووی نوێ</span>
            </a>

            <a href="{{ route('agency.profile') }}" class="px-4 py-3 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-gear"></i>
                <span>ڕێکخستنی نووسینگە</span>
            </a>

            <a href="{{ route('agencies.show', $agency->slug) }}" target="_blank" class="px-4 py-3 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>لاپەڕەی گشتی</span>
            </a>
        </div>
    </div>

    @if($agency->credits <= 0)
        <!-- Zero Credits Warning Banner -->
        <div class="bg-gradient-to-r from-amber-500/15 via-rose-500/10 to-amber-500/15 border-2 border-amber-500/40 rounded-3xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-amber-500 text-slate-950 flex items-center justify-center text-xl flex-shrink-0 shadow-md">
                    <i class="fa-solid fa-coins animate-bounce"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-900">باڵانسی کریدتەکەت سفرە (0 کریدت)</h3>
                    <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">
                        بۆ ئەوەی بتوانیت خانووی نوێ تۆمار بکەیت و لەسەر نەخشەی سلێمانی دەربکەوێت، پێویستە باڵانسی کریدتت نوێ بکەیتەوە.
                    </p>
                </div>
            </div>
            <a href="{{ route('agency.credits') }}" class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-400 hover:from-amber-400 hover:to-amber-300 text-slate-950 font-black text-xs shadow-md transition-all flex items-center justify-center gap-2 flex-shrink-0">
                <i class="fa-solid fa-bag-shopping"></i>
                <span>کڕینی کریدت ئێستا</span>
            </a>
        </div>
    @endif

    <!-- Statistics Overview Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        
        <!-- Credits Balance Card -->
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-4 sm:p-5 shadow-sm border border-amber-200 hover:border-amber-300 transition-all">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-black text-amber-900">باڵانسی کریدت</span>
                <i class="fa-solid fa-coins text-amber-500 text-sm"></i>
            </div>
            <div class="flex items-baseline justify-between mt-1">
                <div class="text-3xl font-black text-amber-900 font-mono">{{ $agency->credits }}</div>
                <a href="{{ route('agency.credits') }}" class="text-[11px] font-bold text-amber-700 hover:text-amber-900 underline">کڕین ←</a>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-slate-200">
            <span class="text-xs font-bold text-slate-700 block mb-1">کۆی خانووەکانت</span>
            <div class="text-2xl font-black text-slate-900">{{ $stats['total'] }}</div>
        </div>

        <div class="bg-emerald-50 rounded-2xl p-4 sm:p-5 shadow-sm border border-emerald-200">
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-bold text-emerald-800">بەردەستە (سەوز)</span>
            </div>
            <div class="text-2xl font-black text-emerald-600">{{ $stats['available'] }}</div>
        </div>

        <div class="bg-slate-100 rounded-2xl p-4 sm:p-5 shadow-sm border border-slate-300">
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2.5 h-2.5 rounded-full bg-slate-500"></span>
                <span class="text-xs font-bold text-slate-800">لە دانوستان</span>
            </div>
            <div class="text-2xl font-black text-slate-700">{{ $stats['reserved'] }}</div>
        </div>

        <div class="bg-rose-50 rounded-2xl p-4 sm:p-5 shadow-sm border border-rose-200">
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                <span class="text-xs font-bold text-rose-800">بەکرێدراو (سور)</span>
            </div>
            <div class="text-2xl font-black text-rose-600">{{ $stats['rented'] }}</div>
        </div>

    </div>

    <!-- Properties Management Table / List -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 space-y-6">
        
        <!-- Controls: Filter & Search -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-base sm:text-lg font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-emerald-600"></i>
                <span>لیستی خانووەکانی نووسینگەکەت</span>
            </h2>

            <!-- Status Quick Filters -->
            <div class="flex flex-wrap items-center gap-2 text-xs">
                <a href="{{ route('agency.dashboard') }}" class="px-3 py-1.5 rounded-xl {{ !request('status') || request('status') === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }} font-bold">
                    هەمووی
                </a>
                <a href="{{ route('agency.dashboard', ['status' => 'available']) }}" class="px-3 py-1.5 rounded-xl {{ request('status') === 'available' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700' }} font-bold">
                    🟢 سەوز (بەردەست)
                </a>
                <a href="{{ route('agency.dashboard', ['status' => 'reserved']) }}" class="px-3 py-1.5 rounded-xl {{ request('status') === 'reserved' ? 'bg-slate-700 text-white' : 'bg-slate-100 text-slate-700' }} font-bold">
                    ⚪ رەساسی (دانوستان)
                </a>
                <a href="{{ route('agency.dashboard', ['status' => 'rented']) }}" class="px-3 py-1.5 rounded-xl {{ request('status') === 'rented' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-700' }} font-bold">
                    🔴 سور (گیراوە)
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-700 font-bold">
                        <th class="py-3 px-3">خانوو / موڵک</th>
                        <th class="py-3 px-3">گەڕەک</th>
                        <th class="py-3 px-3">کرێی مانگانە</th>
                        <th class="py-3 px-3">دۆخی ئێستا (ڕەنگ لە نەخشە)</th>
                        <th class="py-3 px-3">گۆڕینی خێرای دۆخ</th>
                        <th class="py-3 px-3 text-center">کردارەکان</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($properties as $prop)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <!-- Image + Title -->
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $prop->primary_image_url }}" alt="{{ $prop->title }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 flex-shrink-0">
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <a href="{{ route('properties.show', $prop->slug) }}" target="_blank" class="font-bold text-slate-900 hover:text-emerald-700 line-clamp-1">
                                                {{ $prop->title }}
                                            </a>
                                            @if($prop->is_featured)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-500/15 text-amber-700 border border-amber-500/30 text-[10px] font-black" title="ئەم خانوە ڕیکلامی VIP یە و لە لاپەڕەی سەرەکی دەردەکەوێت">
                                                    <i class="fa-solid fa-crown text-amber-500 text-[9px]"></i> VIP
                                                </span>
                                            @endif
                                            @if($prop->has_video)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-50 text-rose-600 border border-rose-200 text-[10px] font-black" title="خاوەنی لینکی ڤیدیۆیە">
                                                    <i class="fa-solid fa-play text-[8px]"></i> ڤیدیۆ
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-[11px] text-slate-600 block">
                                            {{ $prop->property_type_label }} • {{ $prop->bedrooms }} ژوور • {{ $prop->area_sqm }}م²
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Neighborhood -->
                            <td class="py-3 px-3 font-semibold text-slate-700">
                                {{ $prop->neighborhood }}
                            </td>

                            <!-- Price -->
                            <td class="py-3 px-3 font-black text-emerald-600 text-sm">
                                {{ $prop->formatted_price }}
                            </td>

                            <!-- Current Status Badge -->
                            <td class="py-3 px-3">
                                @if($prop->status === 'available')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> سەوز (بەردەست)
                                    </span>
                                @elseif($prop->status === 'reserved')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold bg-slate-100 text-slate-800 border border-slate-300">
                                        <span class="w-2 h-2 rounded-full bg-slate-500"></span> رەساسی (دانوستان)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> سور (گیراوە)
                                    </span>
                                @endif
                            </td>

                            <!-- Quick Status Toggle Form -->
                            <td class="py-3 px-3">
                                <form action="{{ route('agency.properties.status', $prop->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="px-2.5 py-1 rounded-lg border border-slate-300 font-bold text-xs outline-none bg-white">
                                        <option value="available" {{ $prop->status === 'available' ? 'selected' : '' }}>🟢 بەردەست (سەوز)</option>
                                        <option value="reserved" {{ $prop->status === 'reserved' ? 'selected' : '' }}>⚪ دانوستان (رەساسی)</option>
                                        <option value="rented" {{ $prop->status === 'rented' ? 'selected' : '' }}>🔴 گیراوە (سور)</option>
                                    </select>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('properties.show', $prop->slug) }}" target="_blank" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors" title="بینین لە ماڵپەڕ">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('agency.properties.edit', $prop->id) }}" class="p-2 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 transition-colors" title="دەستکاری">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-700">
                                هیچ خانوویەک نەدۆزرایەوە. دەتوانیت لە دوگمەی سەرەوە خانووی نوێ زیادبکەیت!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $properties->links() }}
        </div>

    </div>

</div>
@endsection
