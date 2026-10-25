@extends('layouts.app')

@section('title', 'خانوبەرەی سلێمانی • پلاتفۆرمی نایابی خانووبەرە، ڕیکلامی VIP و ئوتێلەکان')

@section('content')
<div class="space-y-16 pb-16">

    <!-- 1. Hero Luxury Header & Search Hub -->
    <section class="relative overflow-hidden bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-white pt-16 pb-20 px-4 sm:px-6 lg:px-8 border-b border-slate-800">
        <!-- Background Ambient Light Glows -->
        <div class="absolute -top-40 right-1/4 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-40 left-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center max-w-3xl mx-auto space-y-6">
                <!-- VIP Crown Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-amber-500/10 via-amber-400/20 to-amber-500/10 border border-amber-500/30 text-amber-300 text-xs font-black shadow-lg shadow-amber-500/10 backdrop-blur-md">
                    <i class="fa-solid fa-crown text-amber-400 animate-bounce"></i>
                    <span>ناوەندی پۆلێنکردنی خانوبەرەی سلێمانی و ڕیکلامی VIP</span>
                </div>

                <!-- Main Headline (Contains 'خانوبەرەی' & 'سلێمانی') -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-[1.25] text-white">
                    پلاتفۆرمی نایابی <span class="bg-gradient-to-l from-emerald-400 via-teal-300 to-cyan-400 bg-clip-text text-transparent">خانوبەرەی سلێمانی</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-base sm:text-lg text-slate-300 font-medium leading-relaxed max-w-2xl mx-auto">
                    باشترین خانووی کرێ، پڕۆژەی نیشتەجێبوون، شوقەی ناوازە و ئوتێلە نایابەکان لە تەواوی گەڕەکەکانی شاری سلێمانی بە شێوەیەکی مۆدێرن و ڕاستەوخۆ ببینە.
                </p>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

        <!-- 2. VIP & Featured Properties Showcase (ڕیکلام و پڕۆژە نایابەکان) -->
        <section class="space-y-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 text-amber-700 border border-amber-500/20 text-xs font-extrabold mb-2">
                        <i class="fa-solid fa-star text-amber-500"></i>
                        <span>ڕیکلامی نایاب و هەڵبژێردراو (VIP)</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        موڵک و پڕۆژە سەرنجڕاکێشەکانی ئەم هەفتەیە
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-1">
                        تایبەتترین شوێنەکان بە کواڵیتی بەرز و پێداویستی تەواوەوە
                    </p>
                </div>
                <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white text-xs font-black transition-all hover:shadow-md">
                    <span>بینینی هەموو خانووەکان</span>
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
            </div>

            <!-- VIP Properties Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($vipProperties as $property)
                    <article class="bg-white rounded-3xl overflow-hidden {{ $property->is_featured ? 'border border-amber-300/80 shadow-md hover:shadow-2xl ring-1 ring-amber-400/30' : 'border border-slate-200/90 shadow-sm hover:shadow-xl' }} hover:border-amber-400 transition-all duration-300 flex flex-col group relative">
                        <!-- VIP Glowing Ribbon -->
                        <div class="absolute top-4 right-4 z-20 flex items-center gap-1.5">
                            @if($property->is_featured)
                                <span class="px-3 py-1 rounded-full bg-gradient-to-r from-amber-500 to-amber-600 text-white text-xs font-black shadow-lg shadow-amber-500/40 flex items-center gap-1.5 border border-amber-300/40">
                                    <i class="fa-solid fa-crown text-[11px] text-amber-200"></i> VIP
                                </span>
                                @if($property->featured_at && $property->featured_at->gt(now()->subDays(3)))
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-600 text-white text-[10px] font-black shadow-md flex items-center gap-1 animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white"></span> نوێ
                                    </span>
                                @endif
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-slate-900/80 backdrop-blur-md text-white text-xs font-bold">
                                    {{ $property->property_type_label }}
                                </span>
                            @endif
                        </div>

                        <!-- Price Tag Badge Floating -->
                        <div class="absolute top-4 left-4 z-20">
                            <span class="px-3 py-1 rounded-full bg-white/95 backdrop-blur-md text-slate-900 text-xs font-black shadow-md border border-slate-150 font-mono">
                                {{ $property->formatted_price }}
                            </span>
                        </div>

                        <!-- Image Cover -->
                        <a href="{{ route('properties.show', $property->slug) }}" class="relative aspect-[16/10] overflow-hidden bg-slate-100 block">
                            <img src="{{ $property->primary_image_url }}" alt="{{ $property->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>
                            <div class="absolute bottom-3 right-3 text-white text-xs font-bold flex items-center gap-1.5 drop-shadow">
                                <i class="fa-solid fa-location-dot text-rose-400"></i>
                                <span>{{ $property->neighborhood }}</span>
                            </div>
                        </a>

                        <!-- Content Details -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <h3 class="font-black text-slate-900 text-base line-clamp-1 group-hover:text-emerald-700 transition-colors">
                                    <a href="{{ route('properties.show', $property->slug) }}">
                                        {{ $property->title }}
                                    </a>
                                </h3>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                    {{ $property->description ?: 'خانوویەکی مۆدێرن و گونجاو لە گەڕەکی دڵخوازی سلێمانی بە نرخی گونجاو.' }}
                                </p>
                            </div>

                            <!-- Specs Row -->
                            <div class="grid grid-cols-3 gap-2 py-2 border-y border-slate-150/80 text-center text-xs font-bold text-slate-700">
                                <div class="flex items-center justify-center gap-1.5" title="ژووری خەوتن">
                                    <i class="fa-solid fa-bed text-emerald-600"></i>
                                    <span>{{ $property->bedrooms }} خەوتن</span>
                                </div>
                                <div class="flex items-center justify-center gap-1.5 border-x border-slate-200" title="گەرماو">
                                    <i class="fa-solid fa-bath text-teal-600"></i>
                                    <span>{{ $property->bathrooms }} گەرماو</span>
                                </div>
                                <div class="flex items-center justify-center gap-1.5" title="ڕووبەر بە مەتر دووجا">
                                    <i class="fa-solid fa-ruler-combined text-blue-600"></i>
                                    <span class="font-mono">{{ $property->area_sqm }} م²</span>
                                </div>
                            </div>

                            <!-- Agency Info & Fast Contact -->
                            <div class="flex items-center justify-between pt-1">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ $property->agency?->logo_url ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=100&q=80' }}" alt="{{ $property->agency?->name }}" class="w-8 h-8 rounded-full object-cover border border-slate-200">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-900 line-clamp-1">{{ $property->agency?->name ?? 'نووسینگەی خانوبەرە' }}</span>
                                        <span class="text-[10px] text-emerald-600 font-extrabold flex items-center gap-0.5">
                                            <i class="fa-solid fa-circle-check text-[9px]"></i> باوەڕپێکراو
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5">
                                    @if($property->agency?->whatsapp_link)
                                        <a href="{{ $property->agency->whatsapp_link }}?text={{ urlencode('سڵاو، دەمەوێت سەبارەت بەم خانوە پرسیار بکەم: ' . route('properties.show', $property->slug)) }}" target="_blank" rel="noopener" class="w-8 h-8 rounded-full bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-colors shadow-sm" title="پەیوەندی لە واتسئەپ">
                                            <i class="fa-brands fa-whatsapp text-sm"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('properties.show', $property->slug) }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-900 hover:text-white text-slate-800 text-xs font-black transition-colors">
                                        وردەکاری
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full py-12 text-center bg-white rounded-3xl border border-slate-200">
                        <i class="fa-solid fa-house-chimney-crack text-slate-300 text-4xl mb-3"></i>
                        <p class="text-sm font-bold text-slate-600">لە ئێستادا هیچ موڵکێکی VIP بەردەست نییە</p>
                        <a href="{{ route('properties.index') }}" class="mt-3 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold">
                            بینینی هەموو خانووەکان لەسەر نەخشە
                        </a>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- 3. Sponsored VIP Advertising & Project Promotion Banner -->
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-amber-950 to-slate-900 text-white p-8 sm:p-10 border border-amber-500/30 shadow-2xl">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-amber-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                <div class="lg:col-span-2 space-y-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400/20 border border-amber-400/40 text-amber-300 text-xs font-black">
                        <i class="fa-solid fa-bullhorn text-amber-400"></i>
                        <span>شوێنی تایبەتی ڕیکلامی VIP</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-white leading-snug">
                        خاوەن نووسینگە یان پڕۆژەی نیشتەجێبوونیت لە سلێمانی؟
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-xl">
                        موڵکەکانت و پڕۆژە ناوازەکانت بخەرە پێش چاوی هەزاران کڕیار و کرێچی ڕۆژانە لە سەرەکیترین پەیجی خانوبەرەی سلێمانی. بەرزترین ئاستی بینین بەدەستبهێنە.
                    </p>
                    <div class="flex flex-wrap items-center gap-4 pt-2 text-xs font-bold text-amber-200">
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-check-circle text-emerald-400"></i> بەرزترین بینەری ڕۆژانە</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-check-circle text-emerald-400"></i> بڵاوکردنەوەی ناوی نووسینگە</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-check-circle text-emerald-400"></i> دانانی لۆگۆ و پەیوەندی خێرا</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row lg:flex-col gap-3 justify-center">
                    <a href="https://wa.me/9647701234567?text={{ urlencode('سڵاو، دەمەوێت سەبارەت بە ڕیکلامی VIP لە پلاتفۆرمی خانوبەرەی سلێمانی زانیاری وەربگرم') }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-black text-xs sm:text-sm shadow-xl shadow-emerald-500/25 transition-all hover:scale-105 active:scale-95">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                        <span>داواکردنی ڕیکلامی VIP لە واتسئەپ</span>
                    </a>
                    <a href="{{ route('agency.register') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm border border-white/20 backdrop-blur-md transition-all hover:scale-105">
                        <i class="fa-solid fa-building text-amber-400"></i>
                        <span>تۆمارکردنی نووسینگەی نوێ</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- 4. Interactive Neighborhood Quick Explorer -->
        <section class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        گەڕان بەپێی گەڕەکە ناسراوەکانی سلێمانی
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-1">
                        گەڕەکی دڵخوازی خۆت هەڵبژێرە بۆ بینینی هەموو خانووەکانی لەسەر نەخشە
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2.5">
                @foreach($neighborhoods as $neighborhood)
                    <a href="{{ route('properties.index', ['neighborhood' => $neighborhood]) }}" class="p-3 rounded-2xl bg-white border border-slate-200/90 hover:border-emerald-500 hover:bg-emerald-50/50 shadow-sm hover:shadow transition-all text-center group flex flex-col items-center justify-center gap-1.5">
                        <i class="fa-solid fa-location-dot text-slate-400 group-hover:text-emerald-600 group-hover:scale-110 transition-all text-xs"></i>
                        <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-800 transition-colors">{{ $neighborhood }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- 5. Top Verified Agencies Showcase -->
        <section class="space-y-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 text-blue-700 border border-blue-500/20 text-xs font-extrabold mb-2">
                        <i class="fa-solid fa-shield-check text-blue-500"></i>
                        <span>نووسینگە فەرمییەکان</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        نووسینگە پێشەنگ و باوەڕپێکراوەکان
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-1">
                        لەگەڵ نووسینگە فەرمییەکانی سلێمانی ڕاستەوخۆ و بەبێ نێوەندگیر پەیوەندی بکە
                    </p>
                </div>
                <a href="{{ route('agencies.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-black transition-colors">
                    <span>تەواوی نووسینگەکان ({{ $counts['agencies'] ?? 0 }})</span>
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($topAgencies as $agency)
                    <div class="bg-white rounded-3xl p-5 border border-slate-200/90 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between space-y-4 group">
                        <div class="flex items-center gap-3">
                            <img src="{{ $agency->logo_url }}" alt="{{ $agency->name }}" class="w-12 h-12 rounded-2xl object-cover border border-slate-200 group-hover:scale-105 transition-transform">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-black text-slate-900 truncate group-hover:text-emerald-700 transition-colors">
                                    {{ $agency->name }}
                                </h3>
                                <div class="text-[11px] text-emerald-600 font-bold flex items-center gap-1 mt-0.5">
                                    <i class="fa-solid fa-circle-check text-[10px]"></i> باوەڕپێکراو
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1.5 text-xs text-slate-500">
                            <div class="flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot text-slate-400 text-xs"></i>
                                <span class="truncate">{{ $agency->address ?: 'سلێمانی' }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 font-bold text-slate-700">
                                <i class="fa-solid fa-house text-emerald-600 text-xs"></i>
                                <span>{{ $agency->properties_count }} موڵکی بەردەست</span>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex items-center gap-2">
                            <a href="{{ route('agencies.show', $agency->slug) }}" class="flex-1 py-2 rounded-xl bg-slate-100 hover:bg-emerald-600 hover:text-white text-slate-800 text-xs font-black text-center transition-colors">
                                پڕۆفایلی نووسینگە
                            </a>
                            @if($agency->whatsapp_link)
                                <a href="{{ $agency->whatsapp_link }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-xl bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-colors" title="واتسئەپ">
                                    <i class="fa-brands fa-whatsapp text-base"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- 6. Hotels & Residency Preview -->
        @if($topHotels->isNotEmpty())
            <section class="space-y-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 text-amber-700 border border-amber-500/20 text-xs font-extrabold mb-2">
                            <i class="fa-solid fa-hotel text-amber-500"></i>
                            <span>بەشی گەشتیاری و حەوانەوە</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                            ئوتێلە ناوازەکانی شاری سلێمانی
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-1">
                            بۆ گەشتیاران و میوانانی شار، باشترین ئوتێل بە نەخشە و نرخی شەوانە
                        </p>
                    </div>
                    <a href="{{ route('hotels.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-black border border-amber-200/80 transition-colors">
                        <span>هەموو ئوتێلەکان</span>
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($topHotels as $hotel)
                        <article class="bg-white rounded-3xl overflow-hidden border border-slate-200/90 shadow-sm hover:shadow-lg transition-all group flex flex-col">
                            <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                                <img src="{{ $hotel->primary_image_url }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute top-3 right-3 flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-900/80 backdrop-blur-md text-amber-400 text-xs font-black">
                                    @for($i = 0; $i < $hotel->stars; $i++)
                                        <i class="fa-solid fa-star text-[10px]"></i>
                                    @endfor
                                    <span class="text-white mr-1 font-mono">{{ $hotel->stars }} ئەستێرە</span>
                                </div>
                                <div class="absolute bottom-3 right-3 text-white text-xs font-bold drop-shadow flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot text-rose-400"></i>
                                    <span>{{ $hotel->neighborhood }}</span>
                                </div>
                            </div>

                            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                <div>
                                    <h3 class="font-black text-slate-900 text-base line-clamp-1 group-hover:text-amber-700 transition-colors">
                                        {{ $hotel->name }}
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                        {{ $hotel->description ?: 'ئوتێلێکی ناوازە لە شوێنێکی ئارامی سلێمانی بە خزمەتگوزاری تەواوەوە.' }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between pt-2 border-t border-slate-150">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-slate-400 font-bold">نرخی شەوانە لە</span>
                                        <span class="text-base font-black text-amber-600 font-mono">
                                            {{ number_format($hotel->price_per_night) }} {{ $hotel->price_currency === 'USD' ? '$' : 'د.ع' }}
                                        </span>
                                    </div>
                                    <a href="{{ route('hotels.show', $hotel->slug) }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-amber-600 text-white text-xs font-black transition-colors">
                                        بینینی ئوتێل
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

    </div>
</div>
@endsection
