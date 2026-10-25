@extends('layouts.app')

@section('title', 'ئوتێل و مانەوە لە سلێمانی | گەڕان و فلتەرکردنی ڕاستەوخۆ')
@section('meta_description', 'باشترین ئوتێلەکانی ٥، ٤ و ٣ ئەستێرە لە شاری سلێمانی. فلتەرکردنی دەستبەجێ بەبێ نوێبوونەوەی پەڕە، نەخشەی زیرەکی ئینتەراکتیڤ، ڕێ نیشاندەری زیندوو، دیاریکردنی نزیکترین ئوتێل بەپێی شوێنی تۆ.')

@push('styles')
<style>
    /* Ultra-Modern Hotel Map Pin (Luxury Dark & Gold Edition) */
    .hotel-map-pin {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        background: #0f172a;
        color: #ffffff;
        font-weight: 800;
        font-size: 11px;
        border-radius: 9999px;
        border: 2px solid #f59e0b;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.35);
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hotel-map-pin .hotel-pin-stars {
        color: #f59e0b;
        font-size: 10px;
        letter-spacing: -1px;
    }
    .hotel-map-pin .hotel-pin-price {
        font-family: system-ui, sans-serif;
        font-weight: 900;
        color: #f8fafc;
    }
    @keyframes pinPulseGlow {
        0%, 100% {
            transform: scale(1.15) translateY(-2px);
            box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7), 0 10px 25px rgba(245, 158, 11, 0.6);
        }
        50% {
            transform: scale(1.25) translateY(-6px);
            box-shadow: 0 0 0 14px rgba(245, 158, 11, 0), 0 16px 30px rgba(245, 158, 11, 0.8);
        }
    }
    .hotel-map-pin:hover, .hotel-map-pin.is-active {
        transform: scale(1.15) translateY(-2px);
        background: #f59e0b;
        color: #0f172a;
        border-color: #ffffff;
        box-shadow: 0 10px 25px rgba(245, 158, 11, 0.6);
        z-index: 1000 !important;
    }
    .hotel-map-pin.is-active {
        animation: pinPulseGlow 1.2s infinite ease-in-out;
    }
    .hotel-map-pin:hover .hotel-pin-stars, .hotel-map-pin.is-active .hotel-pin-stars {
        color: #0f172a;
    }
    .hotel-map-pin:hover .hotel-pin-price, .hotel-map-pin.is-active .hotel-pin-price {
        color: #0f172a;
    }

    /* Glass HUD styling */
    .glass-island-dark {
        background: rgba(15, 23, 42, 0.92);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: 0 14px 35px -4px rgba(0, 0, 0, 0.35);
    }

    /* User GPS Pin Pulse */
    .user-gps-container {
        pointer-events: auto;
    }
    .user-gps-pin {
        width: 24px;
        height: 24px;
        background: radial-gradient(circle, #3b82f6 35%, #1d4ed8 100%);
        border: 3.5px solid white;
        border-radius: 50%;
        box-shadow: 0 4px 15px rgba(29, 78, 216, 0.5);
        animation: pulse-gps 2s infinite;
    }
    @keyframes pulse-gps {
        0% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.65); }
        70% { box-shadow: 0 0 0 18px rgba(37, 99, 235, 0); }
        100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    <!-- Hero Header -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-amber-950 p-6 sm:p-10 text-white shadow-xl">
        <div class="relative z-10 max-w-3xl space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-xs font-bold">
                <i class="fa-solid fa-hotel text-amber-400"></i>
                <span>{{ __('بەشی تایبەتی ئوتێل و شوێنی مانەوە لە سلێمانی') }}</span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-black tracking-tight leading-tight">
                {{ __('ئوتێل و شوێنی مانەوەی گەشتیاری لە سلێمانی') }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                {{ __('بە فلتەری ڕاستەوخۆ و خێرا ئوتێلی دڵخوازت بدۆزەرەوە، بەراوردی نرخ بکە، نەخشە و ڕێگەی گەیشتن لە شوێنی خۆتەوە ببینە و پەیوەندی بە ڕیسێپشنەوە بکە.') }}
            </p>

            <!-- Action Button: Nearest Hotel to me -->
            <div class="pt-2">
                <button type="button" onclick="shouldFocusNearestHotel = true; triggerNearestSearch()" class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-black text-xs sm:text-sm shadow-xl shadow-blue-600/30 hover:shadow-blue-600/50 transition-all hover:scale-105 active:scale-95 cursor-pointer">
                    <i class="fa-solid fa-location-crosshairs text-base animate-pulse"></i>
                    <span>{{ __('نزیکترین ئوتێل لە منەوە بدۆزەرەوە') }}</span>
                </button>
            </div>
        </div>

        <!-- Decorative Icons in background -->
        <div class="absolute -left-10 -bottom-10 text-white/5 text-9xl pointer-events-none select-none">
            <i class="fa-solid fa-hotel"></i>
        </div>
    </div>

    <!-- Filters Section (Real-Time AJAX) -->
    <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-sm border border-slate-200/90 space-y-4">
        <form id="hotelsFilterForm" action="{{ route('hotels.index') }}" method="GET" class="space-y-4" onsubmit="event.preventDefault(); applyHotelsFilterAjax();">
            
            <!-- Hidden Location & Sort Inputs -->
            <input type="hidden" name="user_lat" id="form_user_lat" value="{{ request('user_lat', $userLat) }}">
            <input type="hidden" name="user_lng" id="form_user_lng" value="{{ request('user_lng', $userLng) }}">
            <input type="hidden" name="sort" id="form_sort" value="{{ request('sort', 'default') }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Search Keyword -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('گەڕان بەپێی ناو یان ناونیشان') }}</label>
                    <div class="relative">
                        <input type="text" name="q" id="filter_q" value="{{ request('q') }}" placeholder="{{ __('ناوی ئوتێل، شەقام، گەڕەک...') }}" autocomplete="off" class="w-full pl-3 pr-9 py-2.5 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 text-xs outline-none bg-slate-50 focus:bg-white transition-all">
                        <i class="fa-solid fa-magnifying-glass absolute right-3 top-3 text-slate-400 text-xs"></i>
                    </div>
                </div>

                <!-- Stars Selector -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('پلەی ئوتێل (ئەستێرە)') }}</label>
                    <select name="stars" id="filter_stars" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 text-xs outline-none bg-white">
                        <option value="all">{{ __('هەموو ئەستێرەکان') }}</option>
                        <option value="5" {{ request('stars') == '5' ? 'selected' : '' }}>{{ __('⭐⭐⭐⭐⭐ ٥ ئەستێرە (لوکس)') }}</option>
                        <option value="4" {{ request('stars') == '4' ? 'selected' : '' }}>{{ __('⭐⭐⭐⭐ ٤ ئەستێرە') }}</option>
                        <option value="3" {{ request('stars') == '3' ? 'selected' : '' }}>{{ __('⭐⭐⭐ ٣ ئەستێرە (ئابووری)') }}</option>
                    </select>
                </div>

                <!-- Neighborhood -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('گەڕەک یان ناوچە') }}</label>
                    <select name="neighborhood" id="filter_neighborhood" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 text-xs outline-none bg-white">
                        <option value="all">{{ __('هەموو ناوچەکان') }}</option>
                        @foreach($neighborhoods as $n)
                            <option value="{{ $n }}" {{ request('neighborhood') == $n ? 'selected' : '' }}>{{ __($n) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('نرخی شەوانە ($)') }}</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="min_price" id="filter_min_price" value="{{ request('min_price') }}" placeholder="{{ __('کەمترین $') }}" class="w-1/2 px-2.5 py-2.5 rounded-xl border border-slate-300 text-xs outline-none">
                        <input type="number" name="max_price" id="filter_max_price" value="{{ request('max_price') }}" placeholder="{{ __('زۆرترین $') }}" class="w-1/2 px-2.5 py-2.5 rounded-xl border border-slate-300 text-xs outline-none">
                    </div>
                </div>
            </div>

            <!-- Action Buttons Row -->
            <div class="flex items-center justify-between gap-3 pt-3 border-t border-slate-100 flex-wrap">
                <div class="flex items-center gap-2">
                    <!-- GPS Locate Me Button -->
                    <button type="button" id="hotelsGpsBtn" onclick="shouldFocusNearestHotel = true; triggerNearestSearch()" class="px-3.5 py-2.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs border border-blue-200 transition-all flex items-center gap-1.5 cursor-pointer hover:scale-105 active:scale-95" title="{{ __('شوێنی من (GPS)') }}">
                        <i class="fa-solid fa-location-crosshairs text-blue-600"></i>
                        <span>{{ __('شوێنی من (GPS)') }}</span>
                    </button>

                    <!-- Realtime Indicator Badge -->
                    <div class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>{{ __('فلتەری ڕاستەوخۆ چالاکە') }}</span>
                    </div>
                </div>

                <!-- Reset Filter Button -->
                <button type="button" onclick="clearHotelsFiltersAjax()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors flex items-center gap-1.5 cursor-pointer" title="{{ __('سڕینەوەی فلتەر') }}">
                    <i class="fa-solid fa-arrow-rotate-right text-[10px]"></i>
                    <span>{{ __('سڕینەوەی فلتەر') }}</span>
                </button>
            </div>

        </form>
    </div>

    <!-- Active Location Banner (If GPS active) -->
    <div id="userLocationBanner" class="{{ $userLat ? '' : 'hidden' }} bg-blue-50 border border-blue-200 rounded-2xl p-4 flex items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm shadow-md shadow-blue-500/20 shrink-0">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div>
                <div class="text-xs font-bold text-blue-900" id="userLocationTitle">
                    {{ __('شوێنی ئێوە لەسەر نەخشەی سلێمانی چالاککراوە!') }}
                </div>
                <div class="text-[11px] text-blue-700">
                    {{ __('ئوتێلەکان بەپێی دووری لە شوێنی تۆوە ڕیزبەند کراون (نزیکترین لە پێشەوە).') }}
                </div>
            </div>
        </div>
        <button type="button" onclick="clearUserLocation()" class="text-xs text-blue-700 hover:text-blue-900 font-bold hover:underline shrink-0">
            {{ __('لابردنی شوێن') }}
        </button>
    </div>

    <!-- View Mode Switcher Bar -->
    <div class="flex items-center justify-between flex-wrap gap-3 pb-1">
        <div class="inline-flex items-center p-1 rounded-2xl bg-white border border-slate-200/90 shadow-sm text-xs font-bold text-slate-700">
            <button type="button" id="viewModeSplitBtn" onclick="switchViewMode('split')" class="px-4 py-2 rounded-xl bg-slate-900 text-white shadow-sm flex items-center gap-2 transition-all">
                <i class="fa-solid fa-table-columns text-amber-400"></i>
                <span>{{ __('هاوبەش (نەخشە + ئوتێل)') }}</span>
            </button>
            <button type="button" id="viewModeListBtn" onclick="switchViewMode('list')" class="px-4 py-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center gap-2 transition-all">
                <i class="fa-solid fa-grip text-slate-400"></i>
                <span>{{ __('تەنها ئوتێلەکان') }}</span>
            </button>
            <button type="button" id="viewModeMapBtn" onclick="switchViewMode('map')" class="px-4 py-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center gap-2 transition-all">
                <i class="fa-solid fa-map-location-dot text-slate-400"></i>
                <span>{{ __('تەنها نەخشە') }}</span>
            </button>
        </div>

        <!-- Sorting selector (Instant AJAX) -->
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-slate-500">{{ __('ڕیزبەندی:') }}</span>
            <select id="hotelsSortDropdown" onchange="changeSort(this.value)" class="px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none cursor-pointer">
                <option value="default" {{ request('sort', 'default') == 'default' ? 'selected' : '' }}>{{ __('تایبەت و پلەبەرز') }}</option>
                <option value="nearest" {{ request('sort') == 'nearest' ? 'selected' : '' }}>{{ __('📍 نزیکترین لە من (GPS)') }}</option>
                <option value="stars_desc" {{ request('sort') == 'stars_desc' ? 'selected' : '' }}>{{ __('⭐ پلەی ئەستێرە (زۆرترین)') }}</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('💵 نرخ: لە کەمەوە بۆ زۆر') }}</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('💎 نرخ: لە زۆرەوە بۆ کەم') }}</option>
            </select>
        </div>
    </div>

    <!-- Main View Container: Interactive Map & Hotel Grid -->
    <div id="hotelsMainViewContainer" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left Column: Hotel Cards Grid (7 Cols in Split Mode) -->
        <div id="hotelsListingContainer" class="lg:col-span-7 space-y-4 order-2 lg:order-1 relative min-h-[300px]">
            @include('partials.hotels_list')
        </div>

        <!-- Right Column: Sticky Interactive Map (5 Cols in Split Mode) -->
        <div id="hotelMapSectionWrapper" class="lg:col-span-5 lg:sticky lg:top-24 z-10 space-y-3 order-1 lg:order-2">
            <div class="bg-white rounded-3xl p-3 sm:p-3.5 shadow-xl border border-slate-200/90 overflow-hidden relative group">
                
                <!-- Map Top Clean Header -->
                <div class="flex items-center justify-between px-2 py-1.5 border-b border-slate-100 mb-2.5">
                    <div class="flex items-center gap-2">
                        <span class="font-black text-slate-900 text-sm">{{ __('نەخشەی ئوتێلەکان') }}</span>
                        <span id="mapHotelsCountBadge" class="text-[11px] bg-amber-50 text-amber-800 border border-amber-200 px-2.5 py-0.5 rounded-full font-bold">
                            {{ count($mapHotels) }} {{ __('ئوتێل') }}
                        </span>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <!-- Modern / Satellite Switcher Dock -->
                        <div class="p-0.5 rounded-xl bg-slate-100 flex items-center gap-0.5 border border-slate-200/80 text-xs">
                            <button type="button" id="btnHotelModern" onclick="switchHotelLayer('osm')" class="px-2.5 py-1 rounded-lg bg-slate-900 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1">
                                <i class="fa-solid fa-map text-amber-400 text-[10px]"></i>
                                <span>{{ __('مۆدێرن') }}</span>
                            </button>
                            <button type="button" id="btnHotelSat" onclick="switchHotelLayer('sat')" class="px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 font-bold text-[11px] transition-all flex items-center gap-1">
                                <i class="fa-solid fa-earth-americas text-blue-500 text-[10px]"></i>
                                <span>{{ __('سەتەلایت') }}</span>
                            </button>
                        </div>

                        <!-- Recenter & Fullscreen buttons -->
                        <button type="button" onclick="resetHotelsView()" class="w-7 h-7 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs transition-all" title="{{ __('گەڕانەوە بۆ ناوەندی سلێمانی') }}">
                            <i class="fa-solid fa-crosshairs text-[11px]"></i>
                        </button>
                        <button type="button" onclick="toggleMapFullscreen()" id="mapFullscreenBtn" class="w-7 h-7 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs transition-all" title="{{ __('تەواوی شاشە') }}">
                            <i class="fa-solid fa-expand text-[11px]"></i>
                        </button>
                    </div>
                </div>

                <!-- Leaflet Map Container -->
                <div class="relative rounded-2xl overflow-hidden border border-slate-200/90 shadow-inner group/map">
                    <div id="hotelsMap" class="w-full z-0 h-[380px] sm:h-[480px] lg:h-[580px]" style="width: 100%;"></div>

                    <!-- Floating Zoom Controls in corner -->
                    <div class="absolute left-3 top-3 z-[400] flex flex-col gap-1 pointer-events-auto">
                        <button type="button" onclick="customMapZoomIn()" class="w-8 h-8 rounded-xl bg-white/95 hover:bg-white text-slate-800 flex items-center justify-center text-xs font-black shadow-md border border-slate-200/80 transition-all hover:scale-105 active:scale-95" title="{{ __('نزیککردنەوە') }}">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </button>
                        <button type="button" onclick="customMapZoomOut()" class="w-8 h-8 rounded-xl bg-white/95 hover:bg-white text-slate-800 flex items-center justify-center text-xs font-black shadow-md border border-slate-200/80 transition-all hover:scale-105 active:scale-95" title="{{ __('دوورخستنەوە') }}">
                            <i class="fa-solid fa-minus text-xs"></i>
                        </button>
                    </div>

                    <!-- Floating Navigation Route HUD Bar (ڕێ نیشاندەری زیندوو) -->
                    <div id="mapNavigationHUD" class="hidden absolute bottom-3 inset-x-3 z-[450] pointer-events-auto">
                        <div class="glass-island-dark text-white p-3 rounded-2xl shadow-2xl border border-white/20 space-y-2">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-2.5">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm shadow-md shadow-blue-500/40 shrink-0 animate-bounce">
                                        <i class="fa-solid fa-diamond-turn-right text-xs"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-[11px] font-bold text-blue-300">{{ __('ڕێگەی گەیشتن:') }}</span>
                                            <span id="routeDistanceBadge" class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-500/30 text-emerald-300 font-extrabold border border-emerald-400/30"></span>
                                        </div>
                                        <div id="routeDestTitle" class="text-xs font-black text-white truncate max-w-[260px]"></div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5 shrink-0">
                                    <a id="routeGoogleMapsBtn" href="#" target="_blank" class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-[11px] font-bold transition-all shadow flex items-center gap-1.5">
                                        <i class="fa-solid fa-location-arrow text-[10px]"></i>
                                        <span>Google Maps</span>
                                    </a>
                                    <button type="button" onclick="clearNavigationRoute()" class="px-2.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white text-[11px] font-bold transition-colors" title="{{ __('لابردنی هێڵی ڕێگە') }}">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Map Bottom Helper -->
                <div class="px-2 pt-2 text-[11px] text-slate-500 flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-amber-500"></i>
                        {{ __('کلیک لەسەر هەر پینێک بکە بۆ بینینی هەموو زانیاری و کێشانی ڕێگەی گەیشتن') }}
                    </span>
                    <span class="text-amber-700 font-bold flex items-center gap-1">
                        <i class="fa-solid fa-mountain-city"></i> {{ __('سلێمانی') }}
                    </span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // Sulaymaniyah Center Coordinates
    const SULAIMANI_CENTER = [35.5558, 45.4351];
    let hotelsMapInstance = null;
    let hotelMarkersLayer = null;
    let hotelUserMarker = null;
    let hotelsList = @json($mapHotels);
    let hotelMarkersMap = {}; // ID to leaflet marker

    // Live Navigation Route layers
    let navigationRouteGroup = null;
    let currentRouteLayer = null;
    let currentRouteCasingLayer = null;
    let currentRouteDest = null;
    let routeAbortController = null;

    // Tile Layers
    let hotelTileOSM = null;
    let hotelTileSat = null;
    let currentHotelTile = null;

    // AJAX Filter state
    let hotelsFilterAbortController = null;
    let hotelsFilterDebounceTimer = null;
    window.shouldFocusNearestHotel = false;
    window.shouldFocusSortHotel = false;

    document.addEventListener('DOMContentLoaded', function () {
        initHotelsMap();
        initHotelsLiveFilter();
    });

    function initHotelsMap() {
        if (typeof L === 'undefined') {
            console.warn('Leaflet library is still loading, retrying...');
            setTimeout(initHotelsMap, 150);
            return;
        }

        if (hotelsMapInstance) return;

        const mapEl = document.getElementById('hotelsMap');
        if (!mapEl) return;

        try {
            hotelsMapInstance = L.map('hotelsMap', {
                center: SULAIMANI_CENTER,
                zoom: 12.5,
                zoomControl: false,
                scrollWheelZoom: true
            });

            // 1. OpenStreetMap (100% reliable in Iraq)
            hotelTileOSM = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | خانوبەرەی سلێمانی'
            });

            // 2. High-Resolution Satellite View (Esri)
            hotelTileSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: '&copy; Esri World Imagery | سلێمانی'
            });

            currentHotelTile = hotelTileOSM;
            currentHotelTile.addTo(hotelsMapInstance);

            // Cluster Group
            if (typeof L.markerClusterGroup !== 'undefined') {
                hotelMarkersLayer = L.markerClusterGroup({
                    showCoverageOnHover: false,
                    maxClusterRadius: 38,
                    spiderfyOnMaxZoom: true,
                    zoomToBoundsOnClick: true,
                    disableClusteringAtZoom: 15,
                    iconCreateFunction: function (cluster) {
                        const count = cluster.getChildCount();
                        return L.divIcon({
                            html: `<div class="cluster-pill-badge" style="background:#0f172a; color:#f59e0b; border:2px solid #f59e0b; font-weight:900;">${count}</div>`,
                            className: 'custom-cluster-wrapper',
                            iconSize: L.point(36, 36),
                            iconAnchor: [18, 18]
                        });
                    }
                }).addTo(hotelsMapInstance);
            } else {
                hotelMarkersLayer = L.layerGroup().addTo(hotelsMapInstance);
            }

            navigationRouteGroup = L.layerGroup().addTo(hotelsMapInstance);

            // Render all hotel markers
            renderHotelMarkers(hotelsList);

            // Invalidate size on load
            setTimeout(() => { if (hotelsMapInstance) hotelsMapInstance.invalidateSize(); }, 150);
            setTimeout(() => { if (hotelsMapInstance) hotelsMapInstance.invalidateSize(); }, 350);

            // Check if user coordinates already set in URL
            const urlParams = new URLSearchParams(window.location.search);
            const userLatParam = parseFloat(urlParams.get('user_lat'));
            const userLngParam = parseFloat(urlParams.get('user_lng'));
            if (userLatParam && userLngParam) {
                setUserPosition(userLatParam, userLngParam, 'شوێنی دیاریکراو');
                if (urlParams.get('sort') === 'nearest' && hotelsList && hotelsList.length > 0) {
                    setTimeout(() => {
                        focusNearestHotelOnMap(hotelsList[0]);
                    }, 400);
                }
            } else if (urlParams.get('action') === 'nearest') {
                window.shouldFocusNearestHotel = true;
                setTimeout(() => {
                    triggerNearestSearch();
                }, 350);
            }

            // Setup silky-smooth mobile touch gestures
            setupMobileFriendlyMapGestures(hotelsMapInstance, 'hotelsMap');

        } catch (e) {
            console.error('Error initializing hotels map:', e);
        }
    }

    function renderHotelMarkers(hotels) {
        if (!hotelsMapInstance || !hotelMarkersLayer) return;

        hotelMarkersLayer.clearLayers();
        hotelMarkersMap = {};

        if (!hotels || !hotels.length) return;

        const bounds = [];

        hotels.forEach((hotel, index) => {
            const lat = parseFloat(hotel.latitude);
            const lng = parseFloat(hotel.longitude);
            if (!lat || !lng) return;

            const starsCount = Math.min(Math.max(parseInt(hotel.stars) || 3, 1), 5);
            const starStr = '★'.repeat(starsCount);

            const iconHtml = `
                <div class="hotel-map-pin">
                    <span class="hotel-pin-stars">${starStr}</span>
                    <span class="hotel-pin-price">$${Math.round(hotel.price_per_night)}</span>
                </div>
            `;

            const customIcon = L.divIcon({
                className: 'custom-pin-wrapper',
                html: iconHtml,
                iconSize: [88, 28],
                iconAnchor: [44, 28],
                popupAnchor: [0, -28]
            });

            const marker = L.marker([lat, lng], { icon: customIcon });

            const isNearest = (index === 0 && hotel.distance_km);
            const distanceBadgeHtml = hotel.distance_km 
                ? `<div class="flex items-center gap-1.5 flex-wrap">
                     <div class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-xl bg-blue-50 text-blue-700 border border-blue-200">
                       <i class="fa-solid fa-route text-blue-500"></i> ${hotel.distance_km < 1 ? Math.round(hotel.distance_km * 1000) + ' مەتر' : hotel.distance_km + ' کم'} لە تۆوە
                     </div>
                     ${isNearest ? `<div class="inline-flex items-center gap-1 text-[10px] font-black px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-300 animate-pulse"><i class="fa-solid fa-bullseye text-emerald-600"></i> نزیکترین لە تۆ</div>` : ''}
                   </div>`
                : '';

            const popupContent = `
                <div class="w-[280px] font-sans overflow-hidden bg-white text-slate-800 rounded-3xl shadow-2xl border border-slate-100" dir="rtl" style="font-family: 'Vazirmatn', sans-serif;">
                    <div class="relative h-36 w-full bg-slate-100 overflow-hidden">
                        <img src="${hotel.primary_image_url}" alt="${hotel.name}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-black/20 to-black/30 pointer-events-none"></div>
                        <div class="absolute top-2.5 right-2.5">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-black bg-slate-900/85 text-amber-400 backdrop-blur-md shadow-md border border-white/20">
                                <span>${starStr}</span>
                                <span class="text-white text-[10px]">${starsCount} ئەستێرە</span>
                            </span>
                        </div>
                        <div class="absolute bottom-2.5 right-3 text-white">
                            <span class="font-black text-lg drop-shadow-md tracking-tight">$${Math.round(hotel.price_per_night)}</span>
                            <span class="text-[11px] text-amber-300 font-medium"> / شەوانە</span>
                        </div>
                    </div>

                    <div class="p-3.5 space-y-2.5">
                        <h4 class="font-black text-sm text-slate-900 line-clamp-1 leading-snug">${hotel.name}</h4>
                        <div class="flex items-center gap-1.5 text-[11px] text-slate-500">
                            <i class="fa-solid fa-location-dot text-amber-500"></i>
                            <span class="truncate">سلێمانی، ${hotel.neighborhood}</span>
                        </div>

                        ${distanceBadgeHtml ? `<div>${distanceBadgeHtml}</div>` : ''}

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-2 border-t border-slate-100 gap-1.5">
                            <button type="button" onclick="showRouteToHotel(${lat}, ${lng}, '${hotel.name.replace(/'/g, "\\'")}', '${hotel.neighborhood.replace(/'/g, "\\'")}')" class="px-2.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all shadow-sm flex items-center gap-1 hover:scale-105 active:scale-95 cursor-pointer" title="ڕێ نیشاندەر بەسەر نەخشەدا تا ئەم ئوتێلە">
                                <i class="fa-solid fa-diamond-turn-right text-[11px] text-blue-200"></i>
                                <span>ڕێگە</span>
                            </button>

                            <a href="${hotel.url}" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-amber-600 text-white text-xs font-bold transition-all shadow-sm flex items-center gap-1">
                                <span>بینین</span>
                                <i class="fa-solid fa-arrow-left text-[9px]"></i>
                            </a>

                            ${hotel.whatsapp ? `<a href="https://wa.me/${hotel.whatsapp}" target="_blank" class="w-8 h-8 rounded-xl bg-[#25D366] hover:bg-[#20ba5a] text-white flex items-center justify-center text-sm shadow-sm transition-all hover:scale-105 active:scale-95" title="واتسئەپ"><i class="fa-brands fa-whatsapp"></i></a>` : ''}
                            ${hotel.phone ? `<a href="tel:${hotel.phone}" class="w-8 h-8 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center text-xs shadow-sm transition-all hover:scale-105 active:scale-95" title="پەیوەندی"><i class="fa-solid fa-phone"></i></a>` : ''}
                        </div>
                    </div>
                </div>
            `;

            marker.bindPopup(popupContent, { maxWidth: 290 });
            hotelMarkersLayer.addLayer(marker);
            hotelMarkersMap[hotel.id] = marker;
            bounds.push([lat, lng]);
        });

        const currentSort = document.getElementById('form_sort')?.value || 'default';

        if (bounds.length && !hotelUserMarker && !window.shouldFocusNearestHotel && !window.shouldFocusSortHotel && currentSort === 'default') {
            hotelsMapInstance.fitBounds(bounds, { padding: [40, 40], maxZoom: 14 });
        }
    }

    // -------------------------------------------------------------
    // LIVE AJAX FILTERING ENGINE (فلتەری ڕاستەوخۆ بەبێ ڕیفرێش)
    // -------------------------------------------------------------
    function initHotelsLiveFilter() {
        const form = document.getElementById('hotelsFilterForm');
        if (!form) return;

        // Instant filter on select changes
        form.querySelectorAll('select').forEach(sel => {
            sel.addEventListener('change', function () {
                applyHotelsFilterAjax();
            });
        });

        // Fast debounced filter on text search (150ms)
        const qInput = document.getElementById('filter_q');
        if (qInput) {
            qInput.addEventListener('input', function () {
                clearTimeout(hotelsFilterDebounceTimer);
                hotelsFilterDebounceTimer = setTimeout(() => {
                    applyHotelsFilterAjax();
                }, 150);
            });
        }

        // Fast debounced filter on price range (250ms)
        ['filter_min_price', 'filter_max_price'].forEach(id => {
            const inp = document.getElementById(id);
            if (inp) {
                inp.addEventListener('input', function () {
                    clearTimeout(hotelsFilterDebounceTimer);
                    hotelsFilterDebounceTimer = setTimeout(() => {
                        applyHotelsFilterAjax();
                    }, 250);
                });
            }
        });
    }

    function applyHotelsFilterAjax(page = null) {
        const form = document.getElementById('hotelsFilterForm');
        if (!form) return;

        const formData = new FormData(form);
        formData.set('ajax', '1');
        if (page) {
            formData.set('page', page);
        }

        const params = new URLSearchParams(formData).toString();
        const ajaxUrl = `{{ route('hotels.index') }}?${params}`;

        // Clean URL for history state without 'ajax' parameter
        formData.delete('ajax');
        const cleanUrl = `{{ route('hotels.index') }}?${new URLSearchParams(formData).toString()}`;

        const listing = document.getElementById('hotelsListingContainer');
        const spinner = document.getElementById('hotelsFilterSpinner');
        if (spinner) spinner.classList.remove('hidden');

        // Cancel previous pending request to eliminate lag
        if (hotelsFilterAbortController) {
            hotelsFilterAbortController.abort();
        }
        hotelsFilterAbortController = new AbortController();

        fetch(ajaxUrl, {
            signal: hotelsFilterAbortController.signal,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (data.html && listing) {
                    listing.innerHTML = data.html;
                    window.history.pushState({}, '', cleanUrl);
                }

                // Update map count badge
                const mapCountBadge = document.getElementById('mapHotelsCountBadge');
                if (mapCountBadge) {
                    mapCountBadge.innerText = `${data.total} ئوتێل`;
                }

                // Update map markers instantly from single response!
                if (data.map_hotels && Array.isArray(data.map_hotels)) {
                    hotelsList = data.map_hotels;
                    renderHotelMarkers(hotelsList);
                }

                // Synchronize user position & banner if coordinates returned
                if (data.user_lat && data.user_lng) {
                    const latEl = document.getElementById('form_user_lat');
                    const lngEl = document.getElementById('form_user_lng');
                    if (latEl) latEl.value = data.user_lat;
                    if (lngEl) lngEl.value = data.user_lng;

                    const banner = document.getElementById('userLocationBanner');
                    if (banner) banner.classList.remove('hidden');

                    if (!hotelUserMarker) {
                        setUserPosition(data.user_lat, data.user_lng, 'شوێنی دیاریکراو');
                    }
                }

                // When sort is changed, or nearest requested, focus the top matching hotel on the map and open its card!
                const currentSort = document.getElementById('form_sort')?.value;
                if ((window.shouldFocusSortHotel || window.shouldFocusNearestHotel || currentSort === 'nearest') && data.map_hotels && data.map_hotels.length > 0) {
                    const targetHotel = data.map_hotels[0];
                    window.shouldFocusSortHotel = false;
                    window.shouldFocusNearestHotel = false;
                    setTimeout(() => {
                        focusHotelOnMap(targetHotel.id, targetHotel.latitude, targetHotel.longitude);
                    }, 300);
                }
            }
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                console.warn('Hotels AJAX filtering error:', err);
            }
        })
        .finally(() => {
            const spinner = document.getElementById('hotelsFilterSpinner');
            if (spinner) spinner.classList.add('hidden');
        });
    }

    // Clear all filters via AJAX
    function clearHotelsFiltersAjax() {
        const form = document.getElementById('hotelsFilterForm');
        if (form) {
            const q = document.getElementById('filter_q');
            if (q) q.value = '';
            const stars = document.getElementById('filter_stars');
            if (stars) stars.value = 'all';
            const neigh = document.getElementById('filter_neighborhood');
            if (neigh) neigh.value = 'all';
            const minP = document.getElementById('filter_min_price');
            if (minP) minP.value = '';
            const maxP = document.getElementById('filter_max_price');
            if (maxP) maxP.value = '';

            document.getElementById('form_sort').value = 'default';
            const sortDropdown = document.getElementById('hotelsSortDropdown');
            if (sortDropdown) sortDropdown.value = 'default';

            document.getElementById('form_user_lat').value = '';
            document.getElementById('form_user_lng').value = '';
            const banner = document.getElementById('userLocationBanner');
            if (banner) banner.classList.add('hidden');

            if (hotelUserMarker && hotelsMapInstance) {
                hotelsMapInstance.removeLayer(hotelUserMarker);
                hotelUserMarker = null;
            }
            clearNavigationRoute();
        }

        applyHotelsFilterAjax();
    }

    // Instant Sort Change via AJAX
    function changeSort(val) {
        document.getElementById('form_sort').value = val;
        const sortDropdown = document.getElementById('hotelsSortDropdown');
        if (sortDropdown && sortDropdown.value !== val) {
            sortDropdown.value = val;
        }

        window.shouldFocusSortHotel = true;

        if (val === 'nearest') {
            window.shouldFocusNearestHotel = true;
            const curLat = document.getElementById('form_user_lat')?.value;
            const curLng = document.getElementById('form_user_lng')?.value;
            if (!curLat || !curLng) {
                triggerNearestSearch();
                return;
            }
        }

        applyHotelsFilterAjax();
    }

    // Intercept AJAX pagination link clicks
    document.addEventListener('click', function (e) {
        const pagLink = e.target.closest('#hotelsListingContainer .ajax-hotels-pagination a');
        if (pagLink && pagLink.href) {
            e.preventDefault();
            const targetUrl = new URL(pagLink.href);
            const pageNum = targetUrl.searchParams.get('page') || 1;
            applyHotelsFilterAjax(pageNum);

            const listing = document.getElementById('hotelsListingContainer');
            if (listing) {
                listing.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    });

    // View Mode Switcher: Split / Only Hotels / Only Map
    function switchViewMode(mode) {
        const listCol = document.getElementById('hotelsListingContainer');
        const mapCol = document.getElementById('hotelMapSectionWrapper');
        const mapEl = document.getElementById('hotelsMap');
        const cardsCont = document.getElementById('hotelsCardsContainer');
        const btnSplit = document.getElementById('viewModeSplitBtn');
        const btnList = document.getElementById('viewModeListBtn');
        const btnMap = document.getElementById('viewModeMapBtn');

        const activeBtn = 'px-4 py-2 rounded-xl bg-slate-900 text-white shadow-sm flex items-center gap-2 transition-all';
        const inactiveBtn = 'px-4 py-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center gap-2 transition-all';

        if (btnSplit) btnSplit.className = (mode === 'split') ? activeBtn : inactiveBtn;
        if (btnList) btnList.className = (mode === 'list') ? activeBtn : inactiveBtn;
        if (btnMap) btnMap.className = (mode === 'map') ? activeBtn : inactiveBtn;

        if (mode === 'list') {
            if (listCol) listCol.className = 'col-span-12 space-y-4 block order-1';
            if (mapCol) mapCol.className = 'hidden';
            if (cardsCont) cardsCont.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4.5';
        } else if (mode === 'map') {
            if (listCol) listCol.className = 'hidden';
            if (mapCol) mapCol.className = 'col-span-12 space-y-3 block order-1';
            if (mapEl) mapEl.style.height = (window.innerWidth < 1024) ? '520px' : '680px';
            setTimeout(() => hotelsMapInstance && hotelsMapInstance.invalidateSize(), 200);
        } else { // split
            if (listCol) listCol.className = 'lg:col-span-7 space-y-4 block order-2 lg:order-1';
            if (mapCol) mapCol.className = 'lg:col-span-5 lg:sticky lg:top-24 z-10 space-y-3 block order-1 lg:order-2';
            if (cardsCont) cardsCont.className = 'grid grid-cols-1 sm:grid-cols-2 gap-4';
            if (mapEl) mapEl.style.height = (window.innerWidth < 1024) ? '380px' : '580px';
            setTimeout(() => hotelsMapInstance && hotelsMapInstance.invalidateSize(), 200);
        }
    }

    // Toggle Map Fullscreen
    function toggleMapFullscreen() {
        const wrapper = document.getElementById('hotelMapSectionWrapper');
        if (!wrapper) return;
        const btn = document.getElementById('mapFullscreenBtn');

        if (!document.fullscreenElement) {
            if (wrapper.requestFullscreen) {
                wrapper.requestFullscreen();
            } else if (wrapper.webkitRequestFullscreen) {
                wrapper.webkitRequestFullscreen();
            }
            if (btn) btn.innerHTML = '<i class="fa-solid fa-compress text-amber-500"></i>';
            setTimeout(() => hotelsMapInstance && hotelsMapInstance.invalidateSize(), 300);
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
            if (btn) btn.innerHTML = '<i class="fa-solid fa-expand"></i>';
            setTimeout(() => hotelsMapInstance && hotelsMapInstance.invalidateSize(), 300);
        }
    }

    document.addEventListener('fullscreenchange', function () {
        const btn = document.getElementById('mapFullscreenBtn');
        if (!document.fullscreenElement && btn) {
            btn.innerHTML = '<i class="fa-solid fa-expand"></i>';
        }
        setTimeout(() => hotelsMapInstance && hotelsMapInstance.invalidateSize(), 300);
    });

    // Custom Zoom buttons
    function customMapZoomIn() {
        if (hotelsMapInstance) hotelsMapInstance.zoomIn();
    }
    function customMapZoomOut() {
        if (hotelsMapInstance) hotelsMapInstance.zoomOut();
    }

    // Recenter
    function resetHotelsView() {
        if (hotelsMapInstance) {
            hotelsMapInstance.setView(SULAIMANI_CENTER, 12.5);
        }
    }

    // Switch Tile Layer: OSM vs Satellite
    function switchHotelLayer(layerType) {
        if (!hotelsMapInstance) return;

        const btnModern = document.getElementById('btnHotelModern');
        const btnSat = document.getElementById('btnHotelSat');

        const activeClass = 'px-2.5 py-1 rounded-lg bg-slate-900 text-white font-bold text-[11px] shadow-sm flex items-center gap-1';
        const inactiveClass = 'px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 font-bold text-[11px] flex items-center gap-1';

        if (layerType === 'sat') {
            if (hotelsMapInstance.hasLayer(hotelTileOSM)) hotelsMapInstance.removeLayer(hotelTileOSM);
            hotelTileSat.addTo(hotelsMapInstance);
            if (btnSat) btnSat.className = activeClass;
            if (btnModern) btnModern.className = inactiveClass;
        } else {
            if (hotelsMapInstance.hasLayer(hotelTileSat)) hotelsMapInstance.removeLayer(hotelTileSat);
            hotelTileOSM.addTo(hotelsMapInstance);
            if (btnModern) btnModern.className = activeClass;
            if (btnSat) btnSat.className = inactiveClass;
        }
    }

    // Detect GPS Location with Live AJAX update & smooth fallback
    function detectUserLocation() {
        window.shouldFocusNearestHotel = true;
        const gpsBtn = document.getElementById('hotelsGpsBtn');
        if (gpsBtn) {
            gpsBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-blue-600"></i> <span>دیاریکردن...</span>';
        }

        const fallbackToCenter = () => {
            const fallbackLat = 35.5558;
            const fallbackLng = 45.4351;
            document.getElementById('form_user_lat').value = fallbackLat;
            document.getElementById('form_user_lng').value = fallbackLng;
            document.getElementById('form_sort').value = 'nearest';
            const sortDropdown = document.getElementById('hotelsSortDropdown');
            if (sortDropdown) sortDropdown.value = 'nearest';

            setUserPosition(fallbackLat, fallbackLng, 'سەنتەری سلێمانی');

            const banner = document.getElementById('userLocationBanner');
            if (banner) {
                banner.classList.remove('hidden');
                const title = document.getElementById('userLocationTitle');
                if (title) {
                    title.innerHTML = 'شوێنی سەرەتایی: <strong>سەنتەری سلێمانی</strong> (دەتوانیت پینەکە ڕابکێشیت بۆ شوێنی خۆت)';
                }
            }

            if (gpsBtn) {
                gpsBtn.innerHTML = '<i class="fa-solid fa-location-crosshairs text-blue-600"></i> <span>شوێنی من (GPS)</span>';
            }

            applyHotelsFilterAjax();
        };

        if (!navigator.geolocation) {
            fallbackToCenter();
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function (pos) {
                let lat = pos.coords.latitude;
                let lng = pos.coords.longitude;
                let label = 'شوێنی تۆ (GPS)';

                const dLat = (lat - 35.5558) * 111;
                const dLng = (lng - 45.4351) * 90.3;
                const distKm = Math.sqrt(dLat * dLat + dLng * dLng);

                if (distKm > 45) {
                    lat = 35.5558;
                    lng = 45.4351;
                    label = 'سەنتەری سلێمانی';
                }

                document.getElementById('form_user_lat').value = lat;
                document.getElementById('form_user_lng').value = lng;
                document.getElementById('form_sort').value = 'nearest';
                const sortDropdown = document.getElementById('hotelsSortDropdown');
                if (sortDropdown) sortDropdown.value = 'nearest';

                setUserPosition(lat, lng, label);

                const banner = document.getElementById('userLocationBanner');
                if (banner) {
                    banner.classList.remove('hidden');
                    const title = document.getElementById('userLocationTitle');
                    if (title) {
                        title.innerText = (distKm > 45) 
                            ? 'شوێنی سەرەتایی: سەنتەری سلێمانی (دەتوانیت پینەکە ڕابکێشیت بۆ هەر شوێنێک)'
                            : 'شوێنی ئێوە لەسەر نەخشەی سلێمانی چالاککراوە!';
                    }
                }

                if (gpsBtn) {
                    gpsBtn.innerHTML = '<i class="fa-solid fa-location-crosshairs text-blue-600"></i> <span>شوێنی من (GPS)</span>';
                }

                applyHotelsFilterAjax();
            },
            function (err) {
                console.warn('GPS location unavailable, falling back smoothly to Sulaymaniyah center:', err);
                fallbackToCenter();
            },
            { enableHighAccuracy: true, timeout: 6000, maximumAge: 60000 }
        );
    }

    function setUserPosition(lat, lng, label = 'شوێنی ئێوە') {
        if (!hotelsMapInstance) return;

        if (hotelUserMarker) {
            hotelsMapInstance.removeLayer(hotelUserMarker);
            hotelUserMarker = null;
        }

        const userIcon = L.divIcon({
            className: 'user-gps-container',
            html: `<div class="user-gps-pin cursor-grab active:cursor-grabbing" title="${label}"></div>`,
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        hotelUserMarker = L.marker([lat, lng], { icon: userIcon, draggable: true }).addTo(hotelsMapInstance);
        hotelUserMarker.bindPopup(`
            <div style="text-align: center; font-weight: bold; padding: 4px; font-family: 'Vazirmatn', sans-serif;" dir="rtl">
                <div class="text-blue-600 mb-0.5 font-black">📍 ${label}</div>
                <div style="font-size: 11px; color: #64748b;">ئوتێلەکان بەپێی دووری لە تۆوە پیشان دراون</div>
                <div style="font-size: 10px; color: #3b82f6; margin-top: 3px;">💡 دەتوانیت ئەم پینە ڕابکێشیت بۆ هەر شوێنێک</div>
            </div>
        `).openPopup();

        // Pan map smoothly to user location
        hotelsMapInstance.setView([lat, lng], 13);

        // Drag marker to recalculate route or re-sort
        hotelUserMarker.on('dragend', function (e) {
            const pos = e.target.getLatLng();
            document.getElementById('form_user_lat').value = pos.lat;
            document.getElementById('form_user_lng').value = pos.lng;
            document.getElementById('form_sort').value = 'nearest';
            const sortDropdown = document.getElementById('hotelsSortDropdown');
            if (sortDropdown) sortDropdown.value = 'nearest';

            const banner = document.getElementById('userLocationBanner');
            if (banner) banner.classList.remove('hidden');

            if (currentRouteDest) {
                fetchAndDrawRoute(pos.lat, pos.lng, currentRouteDest.lat, currentRouteDest.lng, currentRouteDest.title, currentRouteDest.neighborhood);
            }
            applyHotelsFilterAjax();
        });
    }

    // Global locate handler for Hotels (invoked from location picker modal or GPS)
    window.locateAndFilterHotelsMap = function (lat, lng, name = 'شوێنی تۆ') {
        window.shouldFocusNearestHotel = true;
        const latEl = document.getElementById('form_user_lat');
        const lngEl = document.getElementById('form_user_lng');
        const sortEl = document.getElementById('form_sort');
        const sortDropdown = document.getElementById('hotelsSortDropdown');

        if (latEl) latEl.value = lat;
        if (lngEl) lngEl.value = lng;
        if (sortEl) sortEl.value = 'nearest';
        if (sortDropdown) sortDropdown.value = 'nearest';

        setUserPosition(lat, lng, name);

        const banner = document.getElementById('userLocationBanner');
        if (banner) {
            banner.classList.remove('hidden');
            const title = document.getElementById('userLocationTitle');
            if (title) {
                title.innerText = `شوێنی ئێوە دیاریکرا: ${name}`;
            }
        }

        applyHotelsFilterAjax();
        scrollToSulaimaniMap();
    };

    function clearUserLocation() {
        document.getElementById('form_user_lat').value = '';
        document.getElementById('form_user_lng').value = '';
        document.getElementById('form_sort').value = 'default';
        const sortDropdown = document.getElementById('hotelsSortDropdown');
        if (sortDropdown) sortDropdown.value = 'default';

        const banner = document.getElementById('userLocationBanner');
        if (banner) banner.classList.add('hidden');

        if (hotelUserMarker && hotelsMapInstance) {
            hotelsMapInstance.removeLayer(hotelUserMarker);
            hotelUserMarker = null;
        }
        clearNavigationRoute();
        applyHotelsFilterAjax();
    }

    // Focus Hotel On Map from Card
    function focusHotelOnMap(id, lat, lng) {
        if (!hotelsMapInstance) return;
        lat = parseFloat(lat);
        lng = parseFloat(lng);
        if (!lat || !lng) return;

        // Switch out of list-only mode if currently active
        const mapCol = document.getElementById('hotelMapSectionWrapper');
        if (mapCol && mapCol.classList.contains('hidden')) {
            switchViewMode('split');
        }

        const marker = hotelMarkersMap[id] || hotelMarkersMap[parseInt(id, 10)];
        if (marker) {
            const highlightPin = () => {
                try {
                    const el = marker.getElement();
                    if (el) {
                        const pin = el.querySelector('.hotel-map-pin');
                        if (pin) {
                            pin.classList.add('is-active');
                            setTimeout(() => pin.classList.remove('is-active'), 5000);
                        }
                    }
                } catch(e) {}
            };

            if (hotelMarkersLayer && typeof hotelMarkersLayer.zoomToShowLayer === 'function') {
                hotelMarkersLayer.zoomToShowLayer(marker, function() {
                    hotelsMapInstance.panTo([lat, lng], { animate: true, duration: 0.35 });
                    marker.openPopup();
                    highlightPin();
                });
            } else {
                hotelsMapInstance.flyTo([lat, lng], 15.5, { animate: true, duration: 0.6 });
                setTimeout(() => {
                    if (marker) marker.openPopup();
                    highlightPin();
                }, 350);
            }
        } else {
            hotelsMapInstance.flyTo([lat, lng], 15.5, { animate: true, duration: 0.6 });
        }

        // On mobile, scroll up to map smoothly
        if (window.innerWidth < 1024) {
            scrollToSulaimaniMap();
        }
    }

    // Focus Nearest Hotel on Map like a Card
    function focusNearestHotelOnMap(hotel) {
        if (!hotel || !hotel.id) return;
        clearNavigationRoute();
        focusHotelOnMap(hotel.id, hotel.latitude, hotel.longitude);
    }

    // -------------------------------------------------------------
    // LIVE NAVIGATION ROUTE TO HOTEL (ڕێ نیشاندەر تا شوێنی ئوتێل)
    // -------------------------------------------------------------
    function showRouteToHotel(destLat, destLng, title, neighborhood) {
        currentRouteDest = { lat: destLat, lng: destLng, title: title, neighborhood: neighborhood };

        const userLatVal = document.getElementById('form_user_lat')?.value;
        const userLngVal = document.getElementById('form_user_lng')?.value;
        let userLat = parseFloat(userLatVal);
        let userLng = parseFloat(userLngVal);

        // If user hasn't set location, default to Sulaimani Center and draw immediately!
        if (!userLat || !userLng) {
            userLat = 35.5558;
            userLng = 45.4351;
            setUserPosition(userLat, userLng, 'سەنتەری شار');
        }

        // Draw route immediately
        fetchAndDrawRoute(userLat, userLng, destLat, destLng, title, neighborhood);

        // Scroll to map on mobile
        if (window.innerWidth < 1024) {
            const mapEl = document.getElementById('hotelsMap');
            if (mapEl) mapEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function fetchAndDrawRoute(startLat, startLng, destLat, destLng, title, neighborhood) {
        if (!hotelsMapInstance) return;

        // Abort previous in-flight route fetch & clear previous route layers
        if (routeAbortController) {
            routeAbortController.abort();
            routeAbortController = null;
        }
        if (navigationRouteGroup) {
            navigationRouteGroup.clearLayers();
        }

        // OSRM Driving Road Router
        const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${startLng},${startLat};${destLng},${destLat}?overview=full&geometries=geojson`;
        const directCoords = [[startLat, startLng], [destLat, destLng]];

        routeAbortController = new AbortController();
        const timeoutId = setTimeout(() => {
            if (routeAbortController) routeAbortController.abort();
        }, 4000);

        fetch(osrmUrl, { signal: routeAbortController.signal })
            .then(res => res.json())
            .then(data => {
                clearTimeout(timeoutId);
                routeAbortController = null;
                if (data.code === 'Ok' && data.routes && data.routes.length > 0) {
                    const route = data.routes[0];
                    const distanceKm = (route.distance / 1000).toFixed(1);
                    const durationMin = Math.max(1, Math.round(route.duration / 60));
                    const latLngs = route.geometry.coordinates.map(coord => [coord[1], coord[0]]);
                    renderRoutePolylines(latLngs, distanceKm, durationMin, title, neighborhood, startLat, startLng, destLat, destLng);
                } else {
                    fallbackDirectRoute(directCoords, title, neighborhood, startLat, startLng, destLat, destLng);
                }
            })
            .catch(err => {
                clearTimeout(timeoutId);
                if (err.name !== 'AbortError') {
                    console.warn('OSRM routing fallback:', err);
                    fallbackDirectRoute(directCoords, title, neighborhood, startLat, startLng, destLat, destLng);
                }
            });
    }

    function renderRoutePolylines(latLngs, distanceKm, durationMin, title, neighborhood, startLat, startLng, destLat, destLng) {
        if (!hotelsMapInstance || !navigationRouteGroup) return;

        navigationRouteGroup.clearLayers();

        // Outer casing (deep blue)
        currentRouteCasingLayer = L.polyline(latLngs, {
            color: '#1d4ed8',
            weight: 7,
            opacity: 0.75,
            lineCap: 'round',
            lineJoin: 'round'
        }).addTo(navigationRouteGroup);

        // Inner bright dash polyline (vibrant amber / sky blue)
        currentRouteLayer = L.polyline(latLngs, {
            color: '#38bdf8',
            weight: 4,
            opacity: 0.95,
            lineCap: 'round',
            lineJoin: 'round',
            dashArray: '8, 8'
        }).addTo(navigationRouteGroup);

        // Fit map bounds to view full route with padding
        hotelsMapInstance.fitBounds(currentRouteLayer.getBounds(), { padding: [60, 60] });

        // Update floating HUD card
        const hud = document.getElementById('mapNavigationHUD');
        if (hud) {
            hud.classList.remove('hidden');
            document.getElementById('routeDistanceBadge').innerText = `${distanceKm} کم • نزیکەی ${durationMin} خولەک بە ئۆتۆمبێل`;
            document.getElementById('routeDestTitle').innerText = `${title} (${neighborhood})`;

            const gmapsBtn = document.getElementById('routeGoogleMapsBtn');
            if (gmapsBtn) {
                gmapsBtn.href = `https://www.google.com/maps/dir/?api=1&origin=${startLat},${startLng}&destination=${destLat},${destLng}&travelmode=driving`;
            }
        }

        // Auto open destination hotel popup card
        setTimeout(() => {
            const destMarker = Object.values(hotelMarkersMap).find(m => {
                const ll = m.getLatLng();
                return Math.abs(ll.lat - destLat) < 0.0002 && Math.abs(ll.lng - destLng) < 0.0002;
            });
            if (destMarker) {
                if (hotelMarkersLayer && typeof hotelMarkersLayer.zoomToShowLayer === 'function') {
                    hotelMarkersLayer.zoomToShowLayer(destMarker, () => destMarker.openPopup());
                } else {
                    destMarker.openPopup();
                }
            }
        }, 350);
    }

    function fallbackDirectRoute(latLngs, title, neighborhood, startLat, startLng, destLat, destLng) {
        if (!hotelsMapInstance || !navigationRouteGroup) return;

        navigationRouteGroup.clearLayers();

        const dLat = (destLat - startLat) * 111;
        const dLng = (destLng - startLng) * 90.3;
        const directDistKm = Math.sqrt(dLat * dLat + dLng * dLng).toFixed(1);
        const estMin = Math.max(2, Math.round(directDistKm * 2.2));

        currentRouteCasingLayer = L.polyline(latLngs, {
            color: '#1e3a8a',
            weight: 5,
            opacity: 0.6,
            dashArray: '6, 6'
        }).addTo(navigationRouteGroup);

        currentRouteLayer = L.polyline(latLngs, {
            color: '#f59e0b',
            weight: 3,
            opacity: 0.9,
            dashArray: '6, 6'
        }).addTo(navigationRouteGroup);

        hotelsMapInstance.fitBounds(currentRouteLayer.getBounds(), { padding: [60, 60] });

        const hud = document.getElementById('mapNavigationHUD');
        if (hud) {
            hud.classList.remove('hidden');
            document.getElementById('routeDistanceBadge').innerText = `${directDistKm} کم (ڕاستەوخۆ) • ~${estMin} خولەک`;
            document.getElementById('routeDestTitle').innerText = `${title} (${neighborhood})`;

            const gmapsBtn = document.getElementById('routeGoogleMapsBtn');
            if (gmapsBtn) {
                gmapsBtn.href = `https://www.google.com/maps/dir/?api=1&origin=${startLat},${startLng}&destination=${destLat},${destLng}&travelmode=driving`;
            }
        }

        // Auto open destination hotel popup card
        setTimeout(() => {
            const destMarker = Object.values(hotelMarkersMap).find(m => {
                const ll = m.getLatLng();
                return Math.abs(ll.lat - destLat) < 0.0002 && Math.abs(ll.lng - destLng) < 0.0002;
            });
            if (destMarker) {
                if (hotelMarkersLayer && typeof hotelMarkersLayer.zoomToShowLayer === 'function') {
                    hotelMarkersLayer.zoomToShowLayer(destMarker, () => destMarker.openPopup());
                } else {
                    destMarker.openPopup();
                }
            }
        }, 350);
    }

    function clearNavigationRoute() {
        if (routeAbortController) {
            routeAbortController.abort();
            routeAbortController = null;
        }
        if (navigationRouteGroup) {
            navigationRouteGroup.clearLayers();
        }
        currentRouteLayer = null;
        currentRouteCasingLayer = null;
        currentRouteDest = null;
        const hud = document.getElementById('mapNavigationHUD');
        if (hud) hud.classList.add('hidden');
    }

    // Touch scroll protection on mobile
    function setupMobileFriendlyMapGestures(mapInstance, containerId) {
        const container = document.getElementById(containerId);
        if (!container || !mapInstance) return;

        const isMobileOrTouch = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (window.innerWidth < 1024);
        if (!isMobileOrTouch) return;

        if (document.getElementById(containerId + '_touchToggle')) return;

        let mapActive = false;

        const toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.id = containerId + '_touchToggle';
        toggleBtn.className = 'absolute top-2.5 right-2.5 z-[400] flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/95 text-slate-800 text-[11px] font-bold shadow-md border border-slate-200/90 active:scale-95 transition-all lg:hidden pointer-events-auto select-none';
        toggleBtn.innerHTML = '<i class="fa-solid fa-hand-pointer text-amber-500"></i> <span>دەستکاری نەخشە</span>';

        // Start disabled for easy page scrolling
        mapInstance.dragging.disable();
        mapInstance.touchZoom.disable();
        mapInstance.doubleClickZoom.disable();
        mapInstance.scrollWheelZoom.disable();

        toggleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            mapActive = !mapActive;
            if (mapActive) {
                mapInstance.dragging.enable();
                mapInstance.touchZoom.enable();
                mapInstance.doubleClickZoom.enable();
                toggleBtn.className = 'absolute top-2.5 right-2.5 z-[400] flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500 text-slate-950 font-black text-[11px] shadow-lg border border-amber-300 active:scale-95 transition-all lg:hidden pointer-events-auto select-none';
                toggleBtn.innerHTML = '<i class="fa-solid fa-lock-open"></i> <span>نەخشە چالاکە</span>';
            } else {
                mapInstance.dragging.disable();
                mapInstance.touchZoom.disable();
                mapInstance.doubleClickZoom.disable();
                toggleBtn.className = 'absolute top-2.5 right-2.5 z-[400] flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/95 text-slate-800 text-[11px] font-bold shadow-md border border-slate-200/90 active:scale-95 transition-all lg:hidden pointer-events-auto select-none';
                toggleBtn.innerHTML = '<i class="fa-solid fa-hand-pointer text-amber-500"></i> <span>دەستکاری نەخشە</span>';
            }
        });

        container.parentElement.appendChild(toggleBtn);
    }
</script>
@endpush
