@extends('layouts.app')

@section('title', 'خانوبەرەی سلێمانی | دۆزینەوەی خانووی کرێ لەسەر نەخشە')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Hero / Headline Box -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-950 rounded-3xl p-6 sm:p-8 md:p-10 text-white shadow-xl relative overflow-hidden">
        <!-- Decorative Ambient Glows -->
        <div class="absolute -left-16 -bottom-16 w-72 h-72 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -right-16 -top-16 w-72 h-72 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-teal-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-4xl mx-auto text-center space-y-5">
            
            <!-- Top Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 backdrop-blur-md shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span>{{ __('پلاتفۆرمی فەرمی خانوبەرەی سلێمانی') }}</span>
            </div>

            <!-- Main Headline -->
            <h1 class="text-2xl sm:text-4xl md:text-5xl font-black tracking-tight leading-tight">
                {{ __('خانووی کرێ لە سلێمانی بەبێ سەردانی نووسینگەکان') }}
            </h1>

            <!-- Subtitle -->
            <p class="text-sm sm:text-base text-slate-300/90 leading-relaxed max-w-2xl mx-auto">
                {{ __('ڕاستەوخۆ لەسەر نەخشەی شار بەپێی ڕەنگەکانی دۆخی خانوو بزانە کام خانوو بەردەستە، و بە یەک کلیک نزیکترین شوێن لە خۆتەوە بدۆزەرەوە.') }}
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-3 pt-1">
                <button type="button" onclick="triggerNearestSearch()" class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-black text-xs sm:text-sm shadow-xl shadow-blue-600/30 hover:shadow-blue-600/50 transition-all hover:scale-105 active:scale-95">
                    <i class="fa-solid fa-location-crosshairs text-base animate-pulse"></i>
                    <span>{{ __('نزیکترین خانوو لە منەوە بدۆزەرەوە') }}</span>
                </button>

                <a href="{{ route('agencies.index') }}" class="inline-flex items-center gap-2 px-5 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/15 text-white font-bold text-xs sm:text-sm backdrop-blur-md transition-all">
                    <i class="fa-solid fa-building text-emerald-400"></i>
                    <span>{{ __('بینینی نووسینگەکان') }}</span>
                </a>
            </div>

            <!-- Modern Glass Status Legend -->
            <div class="flex flex-wrap items-center justify-center gap-2.5 pt-2 text-xs">
                <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-slate-200 shadow-sm hover:bg-white/15 transition-all">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span class="font-bold text-white">{{ __('بەردەستە بۆ کرێ') }}</span>
                    <span class="text-emerald-400 font-semibold text-[11px]">{{ __('(سەوز)') }}</span>
                </div>

                <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-slate-200 shadow-sm hover:bg-white/15 transition-all">
                    <span class="h-2.5 w-2.5 rounded-full bg-slate-400"></span>
                    <span class="font-bold text-white">{{ __('لە گفتوگۆدا') }}</span>
                    <span class="text-slate-300 font-semibold text-[11px]">{{ __('(رەساسی)') }}</span>
                </div>

                <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-slate-200 shadow-sm hover:bg-white/15 transition-all">
                    <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                    <span class="font-bold text-white">{{ __('بەکرێ گیراوە') }}</span>
                    <span class="text-rose-400 font-semibold text-[11px]">{{ __('(سور)') }}</span>
                </div>
            </div>

        </div>
    </div>

    <!-- Filter & Search Controls Bar -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-slate-200">
        <form action="{{ route('properties.index') }}" method="GET" id="searchFilterForm" class="space-y-4">
            <!-- Hidden inputs to persist user location if any -->
            <input type="hidden" name="user_lat" id="form_user_lat" value="{{ request('user_lat') }}">
            <input type="hidden" name="user_lng" id="form_user_lng" value="{{ request('user_lng') }}">
            <input type="hidden" name="sort" id="form_sort" value="{{ request('sort', 'latest') }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                
                <!-- Keyword search -->
                <div class="lg:col-span-2 relative">
                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('گەڕان بە وشە / ناو / کۆڵان') }}</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute right-3.5 top-3.5 text-slate-400 text-sm"></i>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('بۆ نموونە: خانووی سەرچنار، شوقە...') }}" class="w-full pr-10 pl-4 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm outline-none transition-all">
                    </div>
                </div>

                <!-- Neighborhood filter -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('گەڕەکی سلێمانی') }}</label>
                    <select name="neighborhood" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm outline-none bg-white">
                        <option value="all">{{ __('هەموو گەڕەکەکان') }}</option>
                        @foreach($neighborhoods as $n)
                            <option value="{{ $n }}" {{ request('neighborhood') == $n ? 'selected' : '' }}>{{ __($n) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Property Type -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('جۆری موڵک') }}</label>
                    <select name="type" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm outline-none bg-white">
                        <option value="all">{{ __('هەموو جۆرەکان') }}</option>
                        <option value="house" {{ request('type') == 'house' ? 'selected' : '' }}>{{ __('خانوو') }}</option>
                        <option value="apartment" {{ request('type') == 'apartment' ? 'selected' : '' }}>{{ __('شوقە') }}</option>
                        <option value="villa" {{ request('type') == 'villa' ? 'selected' : '' }}>{{ __('ڤێلا') }}</option>
                        <option value="floor" {{ request('type') == 'floor' ? 'selected' : '' }}>{{ __('نهۆم') }}</option>
                        <option value="commercial" {{ request('type') == 'commercial' ? 'selected' : '' }}>{{ __('دوکان / شوێنی بازرگانی') }}</option>
                    </select>
                </div>

                <!-- Status Selector (The requested colors) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('دۆخی خانوو (ڕەنگ)') }}</label>
                    <select name="status" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm outline-none font-semibold bg-white">
                        <option value="all">{{ __('هەموو دۆخەکان (سەوز + رەساسی + سور)') }}</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }} class="text-emerald-600 font-bold">{{ __('🟢 سەوز (بەردەستە بۆ کرێ)') }}</option>
                        <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }} class="text-slate-600 font-bold">{{ __('⚪ رەساسی (لە دانوستاندا)') }}</option>
                        <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }} class="text-rose-600 font-bold">{{ __('🔴 سور (بەکرێ گیراوە)') }}</option>
                    </select>
                </div>

            </div>

            <!-- Extra row: Max price, Bedrooms, buttons -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-100">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-bold text-slate-600">{{ __('نزمترین نرخ:') }}</span>
                        <div class="relative w-24">
                            <input type="number" name="min_price" id="form_min_price" value="{{ request('min_price') }}" placeholder="{{ __('کەمترین $') }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 text-xs outline-none focus:border-emerald-500">
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-bold text-slate-600">{{ __('بەرزترین نرخ:') }}</span>
                        <div class="relative w-24">
                            <input type="number" name="max_price" id="form_max_price" value="{{ request('max_price') }}" placeholder="{{ __('زۆرترین $') }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 text-xs outline-none focus:border-emerald-500">
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-600">{{ __('ژووری خەوتن:') }}</span>
                        <select name="bedrooms" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs bg-white">
                            <option value="all">{{ __('هەمووی') }}</option>
                            <option value="1" {{ request('bedrooms') == '1' ? 'selected' : '' }}>{{ __('+١ ژوور') }}</option>
                            <option value="2" {{ request('bedrooms') == '2' ? 'selected' : '' }}>{{ __('+٢ ژوور') }}</option>
                            <option value="3" {{ request('bedrooms') == '3' ? 'selected' : '' }}>{{ __('+٣ ژوور') }}</option>
                            <option value="4" {{ request('bedrooms') == '4' ? 'selected' : '' }}>{{ __('+٤ ژوور') }}</option>
                        </select>
                    </div>

                    <!-- Furnished toggle -->
                    <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs font-bold text-slate-700">
                        <input type="checkbox" name="furnished" value="1" {{ request('furnished') == '1' ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500">
                        <span>{{ __('مەفروش (بە کەلوپەلەوە)') }}</span>
                    </label>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <button type="submit" id="filterSubmitBtn" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-filter"></i>
                        <span>{{ __('فلتەرکردن') }}</span>
                    </button>
                    <button type="button" onclick="clearFiltersAjax()" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-rotate-right text-[11px]"></i>
                        <span>{{ __('سڕینەوەی فلتەر') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>



    <!-- Active Location Banner (If GPS active) -->
    <div id="userLocationBanner" class="{{ $userLat ? '' : 'hidden' }} bg-blue-50 border border-blue-200 rounded-2xl p-4 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm shadow-md shadow-blue-500/20">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div>
                <div class="text-xs font-bold text-blue-900" id="userLocationTitle">
                    {{ __('شوێنی ئێوە لەسەر نەخشەی سلێمانی چالاککراوە!') }}
                </div>
                <div class="text-[11px] text-blue-700">
                    {{ __('خانووەکان بەپێی دووری لە شوێنی تۆوە ڕیزبەند کراون (نزیکترین لە پێشەوە).') }}
                </div>
            </div>
        </div>
        <button type="button" onclick="clearUserLocation()" class="text-xs text-blue-700 hover:text-blue-900 font-bold hover:underline">
            {{ __('لابردنی شوێن') }}
        </button>
    </div>

    <!-- View Mode Switcher Bar -->
    <div class="flex items-center justify-between flex-wrap gap-3 pb-1">
        <div class="inline-flex items-center p-1 rounded-2xl bg-white border border-slate-200/90 shadow-sm text-xs font-bold text-slate-700">
            <button type="button" id="viewModeSplitBtn" onclick="switchViewMode('split')" class="px-4 py-2 rounded-xl bg-slate-900 text-white shadow-sm flex items-center gap-2 transition-all">
                <i class="fa-solid fa-table-columns text-emerald-400"></i>
                <span>{{ __('هاوبەش (نەخشە + خانوو)') }}</span>
            </button>
            <button type="button" id="viewModeListBtn" onclick="switchViewMode('list')" class="px-4 py-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center gap-2 transition-all">
                <i class="fa-solid fa-grip text-slate-400"></i>
                <span>{{ __('تەنها خانووەکان') }}</span>
            </button>
            <button type="button" id="viewModeMapBtn" onclick="switchViewMode('map')" class="px-4 py-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center gap-2 transition-all">
                <i class="fa-solid fa-map-location-dot text-slate-400"></i>
                <span>{{ __('تەنها نەخشە') }}</span>
            </button>
        </div>

        <div class="hidden sm:flex items-center gap-3 text-xs text-slate-600 font-semibold bg-white px-4 py-2 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span> {{ __('بەردەست') }}</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span> {{ __('دانوستان') }}</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500 shadow-sm shadow-rose-500/50"></span> {{ __('گیراوە') }}</span>
        </div>
    </div>

    <!-- Main View Container: Interactive Map & Property Grid -->
    <div id="mainViewContainer" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Right Column: Properties Listing (7 Columns in Split Mode, Below Map on Mobile) -->
        <div id="propertiesListingContainer" class="lg:col-span-7 space-y-4 order-2 lg:order-1">
            @include('partials.properties_list')
        </div>

        <!-- Left Column: Sticky Interactive Map (5 Columns in Split Mode, Above Listing on Mobile) -->
        <div class="lg:col-span-5 lg:sticky lg:top-24 z-10 space-y-3 order-1 lg:order-2" id="mapSectionWrapper">
            <div class="bg-white rounded-3xl p-3 sm:p-3.5 shadow-xl border border-slate-200/90 overflow-hidden relative group">
                
                <!-- Map Top Clean Header -->
                <div class="flex items-center justify-between px-2 py-1.5 border-b border-slate-100 mb-2.5">
                    <div class="flex items-center gap-2">
                        <span class="font-black text-slate-900 text-sm">{{ __('نەخشەی سلێمانی') }}</span>
                        <span id="mapPropertiesCountBadge" class="text-[11px] bg-emerald-50 text-emerald-800 border border-emerald-200 px-2.5 py-0.5 rounded-full font-bold">
                            {{ $properties->total() }} {{ __('خانوو') }}
                        </span>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <!-- Modern / Satellite Switcher Dock -->
                        <div class="p-0.5 rounded-xl bg-slate-100 flex items-center gap-0.5 border border-slate-200/80 text-xs">
                            <button type="button" id="btnTileVoyager" onclick="switchMapLayer('voyager')" class="px-2.5 py-1 rounded-lg bg-slate-900 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1">
                                <i class="fa-solid fa-map text-emerald-400 text-[10px]"></i>
                                <span>{{ __('مۆدێرن') }}</span>
                            </button>
                            <button type="button" id="btnTileSatellite" onclick="switchMapLayer('satellite')" class="px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 font-bold text-[11px] transition-all flex items-center gap-1">
                                <i class="fa-solid fa-earth-americas text-blue-500 text-[10px]"></i>
                                <span>{{ __('سەتەلایت') }}</span>
                            </button>
                        </div>

                        <!-- Recenter & Fullscreen buttons -->
                        <button type="button" onclick="resetSulaimaniView()" class="w-7 h-7 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs transition-all" title="{{ __('گەڕانەوە بۆ ناوەندی سلێمانی') }}">
                            <i class="fa-solid fa-crosshairs text-[11px]"></i>
                        </button>
                        <button type="button" onclick="toggleMapFullscreen()" id="mapFullscreenBtn" class="w-7 h-7 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs transition-all" title="{{ __('تەواوی شاشە') }}">
                            <i class="fa-solid fa-expand text-[11px]"></i>
                        </button>
                    </div>
                </div>

                <!-- Leaflet Container -->
                <div class="relative rounded-2xl overflow-hidden border border-slate-200/90 shadow-inner group/map">
                    <div id="sulaimaniMap" class="w-full z-0 h-[340px] sm:h-[420px] lg:h-[560px]" style="width: 100%;"></div>

                    <!-- Clean Floating Zoom Controls in corner -->
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
                                    <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm shadow-md shadow-blue-500/40 flex-shrink-0 animate-bounce">
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

                                <div class="flex items-center gap-1.5 flex-shrink-0">
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

                <!-- Map Bottom Minimal Helper -->
                <div class="px-2 pt-2 text-[11px] text-slate-500 flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-emerald-600"></i>
                        {{ __('کلیک لەسەر هەر نرخێک بکە بۆ بینینی هەموو وردەکاری و پەیوەندی') }}
                    </span>
                    <span class="text-emerald-700 font-bold flex items-center gap-1">
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
    let map = null;
    let markersLayer = null;
    let userMarker = null;
    let mapProperties = [];
    let propertyMarkersMap = {}; // ID to leaflet marker

    // Live Navigation Route layers
    let currentRouteLayer = null;
    let currentRouteCasingLayer = null;
    let currentRouteDest = null;
    let pendingRouteDestination = null;
    let homeRouteWatchId = null;
    let lastHomeTrackedCoords = null;
    let lastHomeRouteFetchTime = 0;

    // Tile Layer instances (created inside initSulaimaniMap)
    let tileOSM = null;
    let tileSatellite = null;
    let currentTileLayer = null;

    document.addEventListener('DOMContentLoaded', function () {
        initSulaimaniMap();
        loadMapProperties();
    });

    function initSulaimaniMap() {
        if (typeof L === 'undefined') {
            console.warn('Leaflet library is still loading, retrying...');
            setTimeout(initSulaimaniMap, 150);
            return;
        }

        if (map) return;

        const mapEl = document.getElementById('sulaimaniMap');
        if (!mapEl) return;

        try {
            map = L.map('sulaimaniMap', {
                center: SULAIMANI_CENTER,
                zoom: 12.5,
                zoomControl: false,
                scrollWheelZoom: true
            });

            // 1. Standard OpenStreetMap - 100% reliable, fast, works everywhere in Iraq
            tileOSM = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors | خانوبەرەی سلێمانی'
            });

            // 2. High-Resolution Satellite View (Esri)
            tileSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: '&copy; Esri World Imagery | سلێمانی'
            });

            currentTileLayer = tileOSM;
            currentTileLayer.addTo(map);

            // Use MarkerClusterGroup to prevent map overcrowding
            if (typeof L.markerClusterGroup !== 'undefined') {
                markersLayer = L.markerClusterGroup({
                    showCoverageOnHover: false,
                    maxClusterRadius: 38,
                    spiderfyOnMaxZoom: true,
                    zoomToBoundsOnClick: true,
                    disableClusteringAtZoom: 15,
                    iconCreateFunction: function (cluster) {
                        const count = cluster.getChildCount();
                        return L.divIcon({
                            html: `<div class="cluster-pill-badge">${count}</div>`,
                            className: 'custom-cluster-wrapper',
                            iconSize: L.point(36, 36),
                            iconAnchor: [18, 18]
                        });
                    }
                }).addTo(map);
            } else {
                markersLayer = L.layerGroup().addTo(map);
            }

            // Force layout recalculations multiple times to guarantee tile render in grid
            setTimeout(() => { if (map) map.invalidateSize(); }, 100);
            setTimeout(() => { if (map) map.invalidateSize(); }, 300);
            setTimeout(() => { if (map) map.invalidateSize(); }, 600);

            // Check if user coordinates or action already in URL
            const urlParams = new URLSearchParams(window.location.search);
            const lat = parseFloat(urlParams.get('user_lat'));
            const lng = parseFloat(urlParams.get('user_lng'));
            if (lat && lng) {
                setUserPosition(lat, lng, 'شوێنی دیاریکراوی ئێوە');
                setTimeout(() => {
                    if (window.scrollToSulaimaniMap) {
                        window.scrollToSulaimaniMap();
                    } else if (mapEl) {
                        mapEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }, 400);
            } else if (urlParams.get('action') === 'nearest') {
                setTimeout(() => {
                    if (window.scrollToSulaimaniMap) window.scrollToSulaimaniMap();
                    if (window.triggerNearestSearch) window.triggerNearestSearch();
                }, 350);
            }

            // Setup silky-smooth mobile touch scrolling (no map scroll trap!)
            setupMobileFriendlyMapGestures(map, 'sulaimaniMap');
        } catch (e) {
            console.error('Error initializing map:', e);
        }
    }

    // View Mode Switcher: Split / Only Properties / Only Map
    function switchViewMode(mode) {
        const propsCol = document.getElementById('propertiesListingContainer');
        const mapCol = document.getElementById('mapSectionWrapper');
        const mapEl = document.getElementById('sulaimaniMap');
        const cardsCont = document.getElementById('propertiesCardsContainer');
        const btnSplit = document.getElementById('viewModeSplitBtn');
        const btnList = document.getElementById('viewModeListBtn');
        const btnMap = document.getElementById('viewModeMapBtn');

        const activeBtn = 'px-4 py-2 rounded-xl bg-slate-900 text-white shadow-sm flex items-center gap-2 transition-all';
        const inactiveBtn = 'px-4 py-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center gap-2 transition-all';

        if (btnSplit) btnSplit.className = (mode === 'split') ? activeBtn : inactiveBtn;
        if (btnList) btnList.className = (mode === 'list') ? activeBtn : inactiveBtn;
        if (btnMap) btnMap.className = (mode === 'map') ? activeBtn : inactiveBtn;

        if (mode === 'list') {
            if (propsCol) propsCol.className = 'col-span-12 space-y-4 block order-1';
            if (mapCol) mapCol.className = 'hidden';
            if (cardsCont) cardsCont.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4.5';
        } else if (mode === 'map') {
            if (propsCol) propsCol.className = 'hidden';
            if (mapCol) mapCol.className = 'col-span-12 space-y-3 block order-1';
            if (mapEl) mapEl.style.height = (window.innerWidth < 1024) ? '520px' : '680px';
            setTimeout(() => map && map.invalidateSize(), 200);
        } else { // split
            if (propsCol) propsCol.className = 'lg:col-span-7 space-y-4 block order-2 lg:order-1';
            if (mapCol) mapCol.className = 'lg:col-span-5 lg:sticky lg:top-24 z-10 space-y-3 block order-1 lg:order-2';
            if (cardsCont) cardsCont.className = 'grid grid-cols-1 sm:grid-cols-2 gap-4';
            if (mapEl) mapEl.style.height = (window.innerWidth < 1024) ? '340px' : '560px';
            setTimeout(() => map && map.invalidateSize(), 200);
        }
    }

    // Enhanced Mobile Touch Gesture Handling (Silky Smooth Page Scrolling)
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
        toggleBtn.innerHTML = '<i class="fa-solid fa-hand text-emerald-600 text-[11px]"></i> <span>جووڵاندنی نەخشە</span>';

        toggleBtn.onclick = function (e) {
            e.stopPropagation();
            mapActive = !mapActive;
            if (mapActive) {
                mapInstance.dragging.enable();
                toggleBtn.className = 'absolute top-2.5 right-2.5 z-[400] flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-[11px] font-bold shadow-lg ring-2 ring-emerald-300 active:scale-95 transition-all lg:hidden pointer-events-auto select-none';
                toggleBtn.innerHTML = '<i class="fa-solid fa-lock-open text-[11px]"></i> <span>نەخشە کراوەیە (تەواو)</span>';
            } else {
                mapInstance.dragging.disable();
                toggleBtn.className = 'absolute top-2.5 right-2.5 z-[400] flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/95 text-slate-800 text-[11px] font-bold shadow-md border border-slate-200/90 active:scale-95 transition-all lg:hidden pointer-events-auto select-none';
                toggleBtn.innerHTML = '<i class="fa-solid fa-hand text-emerald-600 text-[11px]"></i> <span>جووڵاندنی نەخشە</span>';
            }
        };

        const parent = container.parentElement;
        if (parent) parent.appendChild(toggleBtn);

        // Disable single-finger drag on mobile by default so page scrolls smoothly
        mapInstance.dragging.disable();

        // 2-finger gestures (pinch/pan) work automatically
        container.addEventListener('touchstart', function (e) {
            if (e.touches && e.touches.length >= 2) {
                mapInstance.dragging.enable();
            } else if (!mapActive) {
                mapInstance.dragging.disable();
            }
        }, { passive: true });

        container.addEventListener('touchend', function (e) {
            if (!mapActive && (!e.touches || e.touches.length < 2)) {
                mapInstance.dragging.disable();
            }
        }, { passive: true });
    }

    // Custom Map Zoom In & Out
    function customMapZoomIn() {
        if (map) map.zoomIn();
    }
    function customMapZoomOut() {
        if (map) map.zoomOut();
    }

    // Switch between Modern Map and Satellite Tile Layers
    function switchMapLayer(name) {
        if (!map || !tileOSM || !tileSatellite) return;
        const btnOSM = document.getElementById('btnTileVoyager');
        const btnSat = document.getElementById('btnTileSatellite');

        const activeClass = 'px-2.5 py-1 rounded-lg bg-slate-900 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1';
        const inactiveClass = 'px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 font-bold text-[11px] transition-all flex items-center gap-1';

        if (name === 'satellite') {
            if (map.hasLayer(tileOSM)) map.removeLayer(tileOSM);
            tileSatellite.addTo(map);
            currentTileLayer = tileSatellite;
            if (btnSat) btnSat.className = activeClass;
            if (btnOSM) btnOSM.className = inactiveClass;
        } else {
            if (map.hasLayer(tileSatellite)) map.removeLayer(tileSatellite);
            tileOSM.addTo(map);
            currentTileLayer = tileOSM;
            if (btnOSM) btnOSM.className = activeClass;
            if (btnSat) btnSat.className = inactiveClass;
        }
    }

    // Toggle Map Fullscreen
    function toggleMapFullscreen() {
        const wrapper = document.getElementById('mapSectionWrapper');
        if (!wrapper) return;
        const btn = document.getElementById('mapFullscreenBtn');

        if (!document.fullscreenElement) {
            if (wrapper.requestFullscreen) {
                wrapper.requestFullscreen();
            } else if (wrapper.webkitRequestFullscreen) {
                wrapper.webkitRequestFullscreen();
            }
            if (btn) btn.innerHTML = '<i class="fa-solid fa-compress text-emerald-600"></i>';
            setTimeout(() => map && map.invalidateSize(), 300);
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
            if (btn) btn.innerHTML = '<i class="fa-solid fa-expand"></i>';
            setTimeout(() => map && map.invalidateSize(), 300);
        }
    }

    document.addEventListener('fullscreenchange', function () {
        const btn = document.getElementById('mapFullscreenBtn');
        if (!document.fullscreenElement && btn) {
            btn.innerHTML = '<i class="fa-solid fa-expand"></i>';
        }
        setTimeout(() => map && map.invalidateSize(), 300);
    });

    // Quick Map Status Filter from floating buttons
    function quickMapStatusFilter(status) {
        const statusSelect = document.querySelector('#searchFilterForm select[name="status"]');
        if (statusSelect) {
            const nextVal = (statusSelect.value === status) ? 'all' : status;
            statusSelect.value = nextVal;
            
            // Update chip active states
            updateMapChipStyles(nextVal);
            applyFiltersAjax();
        }
    }

    function updateMapChipStyles(activeStatus) {
        const chipAvail = document.getElementById('chipAvailable');
        const chipRes = document.getElementById('chipReserved');
        const chipRent = document.getElementById('chipRented');

        const defaultClass = 'px-3 py-1.5 rounded-xl text-xs font-bold glass-island text-slate-800 transition-all flex items-center gap-1.5 hover:scale-105 active:scale-95 cursor-pointer shadow-md';

        if (chipAvail) {
            chipAvail.className = (activeStatus === 'available')
                ? 'px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-600 text-white shadow-lg shadow-emerald-600/30 ring-2 ring-emerald-300 transition-all flex items-center gap-1.5 scale-105'
                : defaultClass + ' hover:bg-emerald-50';
        }
        if (chipRes) {
            chipRes.className = (activeStatus === 'reserved')
                ? 'px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-800 text-white shadow-lg shadow-slate-800/30 ring-2 ring-slate-300 transition-all flex items-center gap-1.5 scale-105'
                : defaultClass + ' hover:bg-slate-100';
        }
        if (chipRent) {
            chipRent.className = (activeStatus === 'rented')
                ? 'px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-600 text-white shadow-lg shadow-rose-600/30 ring-2 ring-rose-300 transition-all flex items-center gap-1.5 scale-105'
                : defaultClass + ' hover:bg-rose-50';
        }
    }

    // Load pins from JSON API
    function loadMapProperties() {
        const form = document.getElementById('searchFilterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();

        fetch(`{{ route('api.properties.map') }}?${params}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    mapProperties = data.properties;
                    renderMarkers(mapProperties);
                }
            })
            .catch(err => console.error('Map loading error:', err));
    }

    function renderMarkers(properties) {
        if (!map) {
            initSulaimaniMap();
        }
        if (!map) {
            setTimeout(() => renderMarkers(properties), 200);
            return;
        }
        if (!markersLayer) {
            if (typeof L.markerClusterGroup !== 'undefined') {
                markersLayer = L.markerClusterGroup({
                    showCoverageOnHover: false,
                    maxClusterRadius: 38,
                    spiderfyOnMaxZoom: true,
                    zoomToBoundsOnClick: true,
                    disableClusteringAtZoom: 15,
                    iconCreateFunction: function (cluster) {
                        const count = cluster.getChildCount();
                        return L.divIcon({
                            html: `<div class="cluster-pill-badge">${count}</div>`,
                            className: 'custom-cluster-wrapper',
                            iconSize: L.point(38, 38),
                            iconAnchor: [19, 19]
                        });
                    }
                }).addTo(map);
            } else {
                markersLayer = L.layerGroup().addTo(map);
            }
        }
        markersLayer.clearLayers();
        propertyMarkersMap = {};

        if (!properties || !properties.length) return;

        const bounds = [];

        properties.forEach(prop => {
            if (!prop.latitude || !prop.longitude) return;

            // Compact Airbnb-style price pill (only ~$420, clean, minimal, never clutters)
            let pinClass = 'pin-badge-available';
            let dotColor = '#34d399'; // Emerald dot
            let priceLabel = prop.short_price || ('$' + Math.round(prop.price));

            if (prop.status === 'rented') {
                pinClass = 'pin-badge-rented';
                dotColor = '#fda4af'; // Rose dot
            } else if (prop.status === 'reserved') {
                pinClass = 'pin-badge-reserved';
                dotColor = '#cbd5e1'; // Slate dot
            }

            const customIcon = L.divIcon({
                className: 'custom-pin-wrapper',
                html: `<div class="map-property-pin ${pinClass}"><span class="pin-dot"></span><span>${priceLabel}</span></div>`,
                iconSize: [56, 26],
                iconAnchor: [28, 26],
                popupAnchor: [0, -26]
            });

            const marker = L.marker([prop.latitude, prop.longitude], { icon: customIcon });

            // Popup HTML Card - Ultra Modern Luxury Design
            let statusBadgeHtml = '';
            if (prop.status === 'available') {
                statusBadgeHtml = `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-emerald-500 text-white backdrop-blur-md shadow-md border border-white/30"><span class="w-2 h-2 rounded-full bg-white animate-pulse"></span> {{ __('بەردەست') }}</span>`;
            } else if (prop.status === 'reserved') {
                statusBadgeHtml = `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-slate-700 text-white backdrop-blur-md shadow-md border border-white/30"><span class="w-2 h-2 rounded-full bg-slate-300"></span> {{ __('دانوستان') }}</span>`;
            } else {
                statusBadgeHtml = `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-rose-600 text-white backdrop-blur-md shadow-md border border-white/30"><span class="w-2 h-2 rounded-full bg-white"></span> {{ __('گیراوە') }}</span>`;
            }

            const distanceBadge = prop.distance_km 
                ? `<div class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-xl bg-blue-50 text-blue-700 border border-blue-200"><i class="fa-solid fa-route text-blue-500"></i> ${prop.distance_km < 1 ? Math.round(prop.distance_km * 1000) + ' {{ __('مەتر') }}' : prop.distance_km + ' {{ __('کم') }}'} {{ __('لە تۆوە') }}</div>` 
                : '';

            const popupContent = `
                <div class="w-[280px] font-sans overflow-hidden bg-white text-slate-800 rounded-3xl shadow-2xl border border-slate-100">
                    <div class="relative h-36 w-full bg-slate-100 overflow-hidden">
                        <img src="${prop.image}" alt="${prop.title}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-black/20 to-black/30 pointer-events-none"></div>
                        <div class="absolute top-2.5 right-2.5">${statusBadgeHtml}</div>
                        <div class="absolute top-2.5 left-2.5 text-[10.5px] font-bold px-2.5 py-1 rounded-xl bg-slate-900/80 backdrop-blur-md text-white border border-white/20">${prop.property_type_label}</div>
                        <div class="absolute bottom-2.5 right-3 text-white">
                            <span class="font-black text-lg drop-shadow-md tracking-tight">${prop.formatted_price}</span>
                            <span class="text-[11px] text-emerald-300 font-medium"> / {{ __('مانگانە') }}</span>
                        </div>
                    </div>
                    <div class="p-3.5 space-y-2.5">
                        <h4 class="font-black text-sm text-slate-900 line-clamp-1 leading-snug">${prop.title}</h4>
                        ${distanceBadge ? `<div>${distanceBadge}</div>` : ''}
                        
                        <!-- Property Metrics -->
                        <div class="grid grid-cols-3 gap-1.5 py-1.5 border-y border-slate-100 text-center">
                            <div class="bg-slate-50 rounded-xl py-1 px-1">
                                <span class="text-[10px] text-slate-400 block"><i class="fa-solid fa-location-dot text-slate-400"></i> {{ __('گەڕەک') }}</span>
                                <span class="text-xs font-bold text-slate-700 truncate block">${prop.neighborhood}</span>
                            </div>
                            <div class="bg-slate-50 rounded-xl py-1 px-1">
                                <span class="text-[10px] text-slate-400 block"><i class="fa-solid fa-bed text-slate-400"></i> {{ __('ژوور') }}</span>
                                <span class="text-xs font-bold text-slate-700 block">${prop.bedrooms} {{ __('ژوور') }}</span>
                            </div>
                            <div class="bg-slate-50 rounded-xl py-1 px-1">
                                <span class="text-[10px] text-slate-400 block"><i class="fa-solid fa-ruler-combined text-slate-400"></i> {{ __('ڕووبەر') }}</span>
                                <span class="text-xs font-bold text-slate-700 block">${prop.area_sqm}{{ __('م²') }}</span>
                            </div>
                        </div>

                        <!-- Agency & Action Buttons -->
                        <div class="flex items-center justify-between pt-1">
                            <div class="text-xs text-slate-700 font-bold truncate max-w-[95px] flex items-center gap-1">
                                <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                                <span class="truncate">${prop.agency.name}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="showRouteToProperty(${prop.latitude}, ${prop.longitude}, '${prop.title.replace(/'/g, "\\'")}', '${prop.neighborhood}')" class="px-2.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all shadow-sm flex items-center gap-1 hover:scale-105 active:scale-95 cursor-pointer" title="{{ __('ڕێ نیشاندەر بەسەر نەخشەدا تا ئەم خانوو') }}">
                                    <i class="fa-solid fa-diamond-turn-right text-[11px] text-blue-200"></i>
                                    <span>{{ __('ڕێگە') }}</span>
                                </button>
                                <a href="${prop.url}" class="px-2.5 py-1.5 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white text-xs font-bold transition-all shadow-sm flex items-center gap-1">
                                    <span>{{ __('بینین') }}</span>
                                    <i class="fa-solid fa-arrow-left text-[9px]"></i>
                                </a>
                                ${prop.agency.whatsapp_link ? `<a href="${prop.agency.whatsapp_link}" target="_blank" class="w-8 h-8 rounded-xl bg-[#25D366] hover:bg-[#20ba5a] text-white flex items-center justify-center text-sm shadow-sm transition-all hover:scale-105 active:scale-95" title="{{ __('پەیوەندی بە واتسئاپ') }}"><i class="fa-brands fa-whatsapp"></i></a>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;

            marker.bindPopup(popupContent, { maxWidth: 290 });
            markersLayer.addLayer(marker);
            propertyMarkersMap[prop.id] = marker;
            bounds.push([prop.latitude, prop.longitude]);
        });

        // Smart Zoom & Focus handling based on active sort
        const currentSort = document.getElementById('form_sort')?.value || 'latest';

        if ((currentSort === 'price_asc' || currentSort === 'price_desc') && properties.length > 0) {
            const targetProp = properties[0];
            if (targetProp && targetProp.latitude && targetProp.longitude) {
                setTimeout(() => {
                    focusPropertyOnMap(targetProp.id, targetProp.latitude, targetProp.longitude);
                }, 150);
            }
        } else if (bounds.length && !userMarker) {
            map.fitBounds(bounds, { padding: [40, 40], maxZoom: 14 });
        } else if (userMarker && properties.length) {
            const closest = properties[0];
            if (closest && propertyMarkersMap[closest.id]) {
                setTimeout(() => {
                    focusPropertyOnMap(closest.id, closest.latitude, closest.longitude);
                }, 300);
            }
        }
        setTimeout(() => { if (map) map.invalidateSize(); }, 150);
    }

    function setUserPosition(lat, lng, label = 'شوێنی تۆ') {
        if (!map) initSulaimaniMap();
        if (!map) return;
        if (userMarker) {
            map.removeLayer(userMarker);
            userMarker = null;
        }

        const userIcon = L.divIcon({
            className: 'user-gps-container',
            html: `<div class="user-gps-pin cursor-grab active:cursor-grabbing" title="${label}"></div>`,
            iconSize: [22, 22],
            iconAnchor: [11, 11]
        });

        userMarker = L.marker([lat, lng], { icon: userIcon, draggable: true }).addTo(map);
        userMarker.bindPopup(`
            <div style="text-align: center; font-weight: bold; padding: 4px; font-family: 'Vazirmatn';">
                <div class="text-blue-600 mb-0.5 font-black">📍 ${label}</div>
                <div style="font-size: 11px; color: #64748b; font-weight: normal;">{{ __('خانووەکانی دەوروبەرت پیشان دراون') }}</div>
                <div style="font-size: 10px; color: #3b82f6; margin-top: 3px;">💡 {{ __('دەتوانیت ئەم پینە ڕابکێشیت بۆ هەر شوێنێک') }}</div>
            </div>
        `).openPopup();

        // Drag marker to recalculate route or re-sort closest properties
        userMarker.on('dragend', function (e) {
            const pos = e.target.getLatLng();
            document.getElementById('form_user_lat').value = pos.lat;
            document.getElementById('form_user_lng').value = pos.lng;
            document.getElementById('form_sort').value = 'nearest';

            const banner = document.getElementById('userLocationBanner');
            if (banner) banner.classList.remove('hidden');
            const titleEl = document.getElementById('userLocationTitle');
            if (titleEl) titleEl.innerText = '{{ __('شوێنی ئێوە دیاریکرا: شوێنی دەستنیشانکراو') }}';

            if (currentRouteDest) {
                fetchAndDrawRoute(pos.lat, pos.lng, currentRouteDest.lat, currentRouteDest.lng, currentRouteDest.title, currentRouteDest.neighborhood);
            } else {
                applyFiltersAjax();
            }
        });

        // Update hidden form inputs
        document.getElementById('form_user_lat').value = lat;
        document.getElementById('form_user_lng').value = lng;
        document.getElementById('form_sort').value = 'nearest';

        // Show location banner
        const banner = document.getElementById('userLocationBanner');
        banner.classList.remove('hidden');
        document.getElementById('userLocationTitle').innerText = `شوێنی ئێوە دیاریکرا: ${label}`;

        // Pan to user position
        map.setView([lat, lng], 13);

        if (window.scrollToSulaimaniMap) {
            window.scrollToSulaimaniMap();
        }

        // If user initiated a route before selecting location, draw it now!
        if (pendingRouteDestination) {
            const prd = pendingRouteDestination;
            pendingRouteDestination = null;
            setTimeout(() => {
                fetchAndDrawRoute(lat, lng, prd.lat, prd.lng, prd.title, prd.neighborhood);
            }, 450);
        }
    }

    // Global locate handler invoked from header or hero button
    window.locateAndFilterMap = function (lat, lng, name = 'شوێنی تۆ') {
        setUserPosition(lat, lng, name);
        applyFiltersAjax();
        if (window.scrollToSulaimaniMap) {
            window.scrollToSulaimaniMap();
        }
    };

    // Simulate location for quick testing
    function simulateLocation(lat, lng, name) {
        setUserPosition(lat, lng, name);
        applyFiltersAjax();
    }

    function clearUserLocation() {
        document.getElementById('form_user_lat').value = '';
        document.getElementById('form_user_lng').value = '';
        document.getElementById('form_sort').value = 'latest';
        
        const banner = document.getElementById('userLocationBanner');
        if (banner) banner.classList.add('hidden');
        if (userMarker) {
            map.removeLayer(userMarker);
            userMarker = null;
        }
        clearNavigationRoute();
        applyFiltersAjax();
    }

    function resetSulaimaniView() {
        map.setView(SULAIMANI_CENTER, 12);
    }

    function focusPropertyOnMap(id, lat, lng) {
        if (!map) return;
        lat = parseFloat(lat);
        lng = parseFloat(lng);
        if (!lat || !lng) return;

        const marker = propertyMarkersMap[id] || propertyMarkersMap[parseInt(id, 10)];
        if (marker) {
            const highlightPin = () => {
                try {
                    const el = marker.getElement();
                    if (el) {
                        const pin = el.querySelector('.map-property-pin');
                        if (pin) {
                            pin.classList.add('is-active');
                            setTimeout(() => pin.classList.remove('is-active'), 3500);
                        }
                    }
                } catch(e) {}
            };

            if (markersLayer && typeof markersLayer.zoomToShowLayer === 'function') {
                markersLayer.zoomToShowLayer(marker, function() {
                    marker.openPopup();
                    highlightPin();
                });
            } else {
                map.flyTo([lat, lng], 15.5, { animate: true, duration: 0.8 });
                setTimeout(() => {
                    if (marker) marker.openPopup();
                    highlightPin();
                }, 300);
            }
        } else {
            map.flyTo([lat, lng], 15.5, { animate: true, duration: 0.8 });
        }

        // Smooth scroll up to map on mobile screens
        if (window.innerWidth < 1024) {
            if (window.scrollToSulaimaniMap) {
                window.scrollToSulaimaniMap();
            } else {
                const mapEl = document.getElementById('sulaimaniMap');
                if (mapEl) mapEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }

    function focusTopVisiblePropertyOnMap() {
        const cardsContainer = document.getElementById('propertiesCardsContainer');
        if (!cardsContainer) return;
        const visibleCards = Array.from(cardsContainer.querySelectorAll('.property-card-item')).filter(c => c.style.display !== 'none');
        const targetCard = visibleCards[0];
        if (targetCard && targetCard.dataset.lat && targetCard.dataset.lng) {
            const id = targetCard.dataset.id;
            const lat = parseFloat(targetCard.dataset.lat);
            const lng = parseFloat(targetCard.dataset.lng);
            focusPropertyOnMap(id, lat, lng);
        }
    }

    // -------------------------------------------------------------
    // LIVE NAVIGATION ROUTE TO PROPERTY (ڕێ نیشاندەر تا شوێنی خانوو)
    // -------------------------------------------------------------
    function setHomeRouteStart(lat, lng, name) {
        setUserPosition(lat, lng, name);
        if (currentRouteDest) {
            fetchAndDrawRoute(lat, lng, currentRouteDest.lat, currentRouteDest.lng, currentRouteDest.title, currentRouteDest.neighborhood);
        }
    }

    function showRouteToProperty(destLat, destLng, title, neighborhood) {
        currentRouteDest = { lat: destLat, lng: destLng, title: title, neighborhood: neighborhood };

        const userLatVal = document.getElementById('form_user_lat')?.value;
        const userLngVal = document.getElementById('form_user_lng')?.value;
        let userLat = parseFloat(userLatVal);
        let userLng = parseFloat(userLngVal);

        // If user hasn't chosen location yet -> default immediately to Sulaymaniyah Center
        // NO modal blocking, NO error alerts, instant road drawing!
        if (!userLat || !userLng) {
            userLat = 35.5558;
            userLng = 45.4351;
            setUserPosition(userLat, userLng, 'سەنتەری شار');
        }

        // 1. Draw initial route immediately (<200ms)
        fetchAndDrawRoute(userLat, userLng, destLat, destLng, title, neighborhood);

        // 2. Smoothly scroll down to the map
        if (window.scrollToSulaimaniMap) {
            window.scrollToSulaimaniMap();
        }

        // 3. If GPS is available and location was defaulted, try to refine in background with fast timeout
        if (navigator.geolocation && (!userLatVal || !userLngVal)) {
            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    const curLat = pos.coords.latitude;
                    const curLng = pos.coords.longitude;
                    const dLat = (curLat - 35.5558) * 111;
                    const dLng = (curLng - 45.4351) * 90.3;
                    const distKm = Math.sqrt(dLat * dLat + dLng * dLng);

                    if (distKm <= 45) {
                        setUserPosition(curLat, curLng, 'شوێنی تۆ (GPS)');
                        if (currentRouteDest) {
                            fetchAndDrawRoute(curLat, curLng, currentRouteDest.lat, currentRouteDest.lng, currentRouteDest.title, currentRouteDest.neighborhood);
                        }
                    }
                },
                function(err) {
                    console.info('GPS not active, using default center:', err.message);
                },
                { enableHighAccuracy: false, timeout: 4000, maximumAge: 60000 }
            );
        }
    }

    function fetchAndDrawRoute(startLat, startLng, destLat, destLng, title, neighborhood) {
        if (!map) return;

        // Clear previous route polylines
        if (currentRouteLayer) { map.removeLayer(currentRouteLayer); currentRouteLayer = null; }
        if (currentRouteCasingLayer) { map.removeLayer(currentRouteCasingLayer); currentRouteCasingLayer = null; }

        // Driving road coordinates in Sulaymaniyah via public OSRM router
        const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${startLng},${startLat};${destLng},${destLat}?overview=full&geometries=geojson`;
        const directCoords = [[startLat, startLng], [destLat, destLng]];

        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 4000);

        fetch(osrmUrl, { signal: controller.signal })
            .then(res => res.json())
            .then(data => {
                clearTimeout(timeoutId);
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
                console.warn('OSRM routing fallback:', err);
                fallbackDirectRoute(directCoords, title, neighborhood, startLat, startLng, destLat, destLng);
            });
    }

    function renderRoutePolylines(latLngs, distanceKm, durationMin, title, neighborhood, startLat, startLng, destLat, destLng) {
        if (!map) return;

        // Outer casing (glowing deep blue)
        currentRouteCasingLayer = L.polyline(latLngs, {
            color: '#1d4ed8',
            weight: 7,
            opacity: 0.75,
            lineCap: 'round',
            lineJoin: 'round'
        }).addTo(map);

        // Inner bright dash polyline (vibrant sky blue)
        currentRouteLayer = L.polyline(latLngs, {
            color: '#38bdf8',
            weight: 4,
            opacity: 0.95,
            lineCap: 'round',
            lineJoin: 'round',
            dashArray: '8, 8'
        }).addTo(map);

        // Fit map bounds to view full route with padding
        map.fitBounds(currentRouteLayer.getBounds(), { padding: [60, 60] });

        // Update floating HUD card
        const hud = document.getElementById('mapNavigationHUD');
        const distBadge = document.getElementById('routeDistanceBadge');
        const titleEl = document.getElementById('routeDestTitle');
        const gmapsBtn = document.getElementById('routeGoogleMapsBtn');

        if (distBadge) distBadge.innerText = `🚗 ~${durationMin} خولەک (${distanceKm} کم)`;
        if (titleEl) titleEl.innerText = `${title} • ${neighborhood}`;
        if (gmapsBtn) {
            gmapsBtn.href = `https://www.google.com/maps/dir/?api=1&origin=${startLat},${startLng}&destination=${destLat},${destLng}&travelmode=driving`;
        }
        if (hud) hud.classList.remove('hidden');

        if (window.scrollToSulaimaniMap) window.scrollToSulaimaniMap();
    }

    function fallbackDirectRoute(latLngs, title, neighborhood, startLat, startLng, destLat, destLng) {
        const dLat = (destLat - startLat) * 111;
        const dLng = (destLng - startLng) * 90.3;
        const distKm = Math.sqrt(dLat*dLat + dLng*dLng).toFixed(1);
        const estMin = Math.max(1, Math.round(distKm * 2.2));
        renderRoutePolylines(latLngs, distKm, estMin, title, neighborhood, startLat, startLng, destLat, destLng);
    }

    function clearNavigationRoute() {
        if (homeRouteWatchId !== null) {
            navigator.geolocation.clearWatch(homeRouteWatchId);
            homeRouteWatchId = null;
        }
        currentRouteDest = null;
        lastHomeTrackedCoords = null;
        lastHomeRouteFetchTime = 0;

        if (map) {
            if (currentRouteLayer) {
                map.removeLayer(currentRouteLayer);
                currentRouteLayer = null;
            }
            if (currentRouteCasingLayer) {
                map.removeLayer(currentRouteCasingLayer);
                currentRouteCasingLayer = null;
            }
        }
        const hud = document.getElementById('mapNavigationHUD');
        if (hud) hud.classList.add('hidden');
    }

    // UNIFIED LIVE AJAX FILTER (No Page Reload, Zero Refresh)
    // Instant Client-Side Filter (0 milliseconds response time)
    function instantClientFilter() {
        const form = document.getElementById('searchFilterForm');
        if (!form) return;
        const q = (form.querySelector('input[name="q"]')?.value || '').trim().toLowerCase();
        const neighborhood = form.querySelector('select[name="neighborhood"]')?.value || 'all';
        const type = form.querySelector('select[name="type"]')?.value || 'all';
        const status = form.querySelector('select[name="status"]')?.value || 'all';
        const minPrice = parseFloat(form.querySelector('input[name="min_price"]')?.value) || 0;
        const maxPrice = parseFloat(form.querySelector('input[name="max_price"]')?.value) || 0;
        const bedrooms = form.querySelector('select[name="bedrooms"]')?.value || 'all';

        const cardsContainer = document.getElementById('propertiesCardsContainer');
        if (!cardsContainer) return;
        const cards = cardsContainer.querySelectorAll('.property-card-item');

        let visibleCount = 0;
        cards.forEach(card => {
            let match = true;
            if (q && !(card.dataset.title || '').includes(q)) match = false;
            if (match && neighborhood !== 'all' && card.dataset.neighborhood !== neighborhood) match = false;
            if (match && type !== 'all' && card.dataset.type !== type) match = false;
            if (match && status !== 'all' && card.dataset.status !== status) match = false;
            if (match && minPrice > 0 && (parseFloat(card.dataset.price) || 0) < minPrice) match = false;
            if (match && maxPrice > 0 && (parseFloat(card.dataset.price) || 0) > maxPrice) match = false;
            if (match && bedrooms !== 'all' && (parseInt(card.dataset.bedrooms) || 0) < parseInt(bedrooms)) match = false;

            if (match) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const showingCountEl = document.getElementById('currentShowingCount');
        if (showingCountEl) showingCountEl.innerText = visibleCount;
    }

    // UNIFIED ULTRA-FAST LIVE AJAX FILTER (Single Request + AbortController)
    let filterDebounceTimer = null;
    let filterAbortController = null;

    function applyFiltersAjax(page = null) {
        // 1. Instant client-side feedback (0 milliseconds)
        if (!page) {
            instantClientFilter();
        }

        const form = document.getElementById('searchFilterForm');
        const formData = new FormData(form);
        formData.set('ajax', '1');
        if (page) {
            formData.set('page', page);
        }

        const params = new URLSearchParams(formData).toString();
        const ajaxUrl = `{{ route('properties.index') }}?${params}`;

        // Clean URL for history state without 'ajax' parameter
        formData.delete('ajax');
        const cleanUrl = `{{ route('properties.index') }}?${new URLSearchParams(formData).toString()}`;

        const listing = document.getElementById('propertiesListingContainer');
        const spinner = document.getElementById('sortLoadingSpinner');

        if (spinner) spinner.classList.remove('hidden');

        // Cancel previous pending request to eliminate network congestion
        if (filterAbortController) {
            filterAbortController.abort();
        }
        filterAbortController = new AbortController();

        // 2. Fetch updated properties listing and map data in a single shot
        fetch(ajaxUrl, {
            signal: filterAbortController.signal,
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

                // Update map properties count badge
                const mapCountBadge = document.getElementById('mapPropertiesCountBadge');
                if (mapCountBadge) {
                    mapCountBadge.innerText = `${data.total} خانوو`;
                }

                // Update map markers instantly from single response (0 extra network call!)
                if (data.map_properties && Array.isArray(data.map_properties)) {
                    mapProperties = data.map_properties;
                    renderMarkers(mapProperties);
                }
            }
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                console.warn('AJAX filtering error:', err);
            }
        })
        .finally(() => {
            if (spinner) spinner.classList.add('hidden');
        });
    }

    // Clear all filters without reloading the page
    function clearFiltersAjax() {
        const form = document.getElementById('searchFilterForm');
        if (form) {
            const inputs = form.querySelectorAll('input[type="text"], input[type="number"]');
            inputs.forEach(input => input.value = '');

            const selects = form.querySelectorAll('select');
            selects.forEach(select => select.value = 'all');

            const checkboxes = form.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = false);

            document.getElementById('form_sort').value = 'latest';
            document.getElementById('form_user_lat').value = '';
            document.getElementById('form_user_lng').value = '';

            const banner = document.getElementById('userLocationBanner');
            if (banner) banner.classList.add('hidden');
            if (userMarker) {
                map.removeLayer(userMarker);
                userMarker = null;
            }
            updateMapChipStyles('all');
        }
        applyFiltersAjax();
    }

    // Instant Live Sorting without page reload
    function updateSort(val) {
        if (val === 'nearest') {
            const userLat = document.getElementById('form_user_lat')?.value;
            const userLng = document.getElementById('form_user_lng')?.value;
            if (!userLat || !userLng) {
                triggerNearestSearch();
                return;
            }
        }

        // 1. Sync hidden input in filter form
        const formSort = document.getElementById('form_sort');
        if (formSort) formSort.value = val;

        // 2. INSTANT CLIENT-SIDE REORDER (0 milliseconds, no wait!)
        const cardsContainer = document.getElementById('propertiesCardsContainer');
        if (cardsContainer) {
            const cards = Array.from(cardsContainer.querySelectorAll('.property-card-item'));
            if (cards.length > 1) {
                cards.sort((a, b) => {
                    if (val === 'price_asc') {
                        return (parseFloat(a.dataset.price) || 0) - (parseFloat(b.dataset.price) || 0);
                    } else if (val === 'price_desc') {
                        return (parseFloat(b.dataset.price) || 0) - (parseFloat(a.dataset.price) || 0);
                    } else if (val === 'latest') {
                        return (parseFloat(b.dataset.created) || 0) - (parseFloat(a.dataset.created) || 0);
                    } else if (val === 'nearest') {
                        return (parseFloat(a.dataset.distance) || 999999) - (parseFloat(b.dataset.distance) || 999999);
                    }
                    return 0;
                });

                const fragment = document.createDocumentFragment();
                cards.forEach(card => fragment.appendChild(card));
                cardsContainer.appendChild(fragment);
            }
        }

        // 3. Sync full database sorting and map via AJAX
        applyFiltersAjax();
    }

    // Attach Event Listeners on DOM Ready
    document.addEventListener('DOMContentLoaded', function () {
        const filterForm = document.getElementById('searchFilterForm');
        if (filterForm) {
            // Prevent standard form submission & use AJAX
            filterForm.addEventListener('submit', function (e) {
                e.preventDefault();
                applyFiltersAjax();
            });

            // Instant auto-filter on changing any select (0ms feedback + background sync)
            filterForm.querySelectorAll('select').forEach(sel => {
                sel.addEventListener('change', function () {
                    instantClientFilter();
                    applyFiltersAjax();
                });
            });

            // Instant auto-filter on changing furnished checkbox
            filterForm.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.addEventListener('change', function () {
                    instantClientFilter();
                    applyFiltersAjax();
                });
            });

            // Super fast debounced auto-filter on typing (150ms instead of 400ms!)
            filterForm.querySelectorAll('input[type="text"], input[type="number"]').forEach(inp => {
                inp.addEventListener('input', function () {
                    instantClientFilter();
                    clearTimeout(filterDebounceTimer);
                    filterDebounceTimer = setTimeout(() => {
                        applyFiltersAjax();
                    }, 150);
                });
            });
        }
    });

    // Intercept AJAX pagination link clicks so page doesn't refresh
    document.addEventListener('click', function (e) {
        const pagLink = e.target.closest('#propertiesListingContainer .ajax-pagination-container a');
        if (pagLink && pagLink.href) {
            e.preventDefault();
            const targetUrl = new URL(pagLink.href);
            const pageNum = targetUrl.searchParams.get('page') || 1;
            applyFiltersAjax(pageNum);

            const listing = document.getElementById('propertiesListingContainer');
            if (listing) {
                listing.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    });
</script>
@endpush
