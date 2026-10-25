@extends('layouts.app')

@section('title', $hotel->name . ' | ئوتێل لە سلێمانی')
@section('meta_description', $hotel->name . ' لە شاری سلێمانی، گەڕەکی ' . $hotel->neighborhood . '. نرخی شەوانە: $' . $hotel->price_per_night . '. پلەی ئوتێل: ' . $hotel->stars . ' ئەستێرە.')



@push('styles')
<style>
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
            transform: scale(1.08) translateY(-2px);
            box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7), 0 8px 20px rgba(245, 158, 11, 0.6);
        }
        50% {
            transform: scale(1.15) translateY(-5px);
            box-shadow: 0 0 0 12px rgba(245, 158, 11, 0), 0 14px 28px rgba(245, 158, 11, 0.8);
        }
    }
    .hotel-map-pin:hover, .hotel-map-pin.is-active {
        transform: scale(1.08) translateY(-2px);
        background: #f59e0b;
        color: #0f172a;
        border-color: #ffffff;
        box-shadow: 0 10px 25px rgba(245, 158, 11, 0.6);
        z-index: 1000 !important;
    }
    .hotel-map-pin.is-active {
        animation: pinPulseGlow 1.4s infinite ease-in-out;
    }
    .hotel-map-pin:hover .hotel-pin-stars, .hotel-map-pin.is-active .hotel-pin-stars {
        color: #0f172a;
    }
    .hotel-map-pin:hover .hotel-pin-price, .hotel-map-pin.is-active .hotel-pin-price {
        color: #0f172a;
    }
    .glass-island-dark {
        background: rgba(15, 23, 42, 0.92);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: 0 14px 35px -4px rgba(0, 0, 0, 0.35);
    }
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
        0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
        70% { box-shadow: 0 0 0 14px rgba(59, 130, 246, 0); }
        100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
        <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">{{ __('ماڵپەڕی سەرەکی') }}</a>
        <i class="fa-solid fa-chevron-left rtl:rotate-0 ltr:rotate-180 text-[10px]"></i>
        <a href="{{ route('hotels.index') }}" class="hover:text-slate-900 transition-colors">{{ __('ئوتێلەکان') }}</a>
        <i class="fa-solid fa-chevron-left rtl:rotate-0 ltr:rotate-180 text-[10px]"></i>
        <span class="text-slate-800 font-bold truncate max-w-xs">{{ $hotel->name }}</span>
    </nav>

    <!-- Header Section -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/90 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="space-y-2">
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Stars -->
                <div class="inline-flex items-center gap-1 bg-amber-50 text-amber-600 border border-amber-200 px-3 py-1 rounded-full text-xs font-black">
                    <span>{{ str_repeat('★', $hotel->stars) }}</span>
                    <span>{{ $hotel->stars }} {{ __('ئەستێرە') }}</span>
                </div>

                @if($hotel->is_featured)
                    <span class="bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-black text-xs px-3 py-1 rounded-full shadow-sm">
                        {{ __('ئوتێلی تایبەت') }}
                    </span>
                @endif

                <span class="bg-slate-100 text-slate-700 text-xs px-3 py-1 rounded-full font-bold">
                    {{ __('سلێمانی') }}، {{ __($hotel->neighborhood) }}
                </span>
            </div>

            <h1 class="text-xl sm:text-3xl font-black text-slate-900">
                {{ $hotel->name }}
            </h1>

            @if($hotel->address)
                <p class="text-xs sm:text-sm text-slate-500 flex items-center gap-1.5 font-medium">
                    <i class="fa-solid fa-location-dot text-amber-500"></i>
                    <span>{{ $hotel->address }}</span>
                </p>
            @endif
        </div>

        <!-- Price Callout -->
        <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-4 text-center md:text-left min-w-[180px]">
            <span class="text-xs text-amber-900 font-bold block mb-0.5">{{ __('دەستپێکی نرخ') }}</span>
            <div class="text-2xl sm:text-3xl font-black text-amber-600">
                ${{ number_format($hotel->price_per_night) }}
                <span class="text-xs font-bold text-slate-500">{{ __('/ شەو') }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left: Image Gallery & Details (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Gallery -->
            <div class="bg-white rounded-3xl p-4 shadow-sm border border-slate-200 space-y-3">
                <div class="relative h-[340px] sm:h-[450px] rounded-2xl overflow-hidden bg-slate-900 shadow-md">
                    <img id="mainGalleryImage" src="{{ $hotel->primary_image_url }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover">
                </div>

                @if(!empty($hotel->images) && is_array($hotel->images) && count($hotel->images) > 0)
                    <div class="flex items-center gap-2 overflow-x-auto pb-1">
                        <button type="button" onclick="changeImage('{{ $hotel->primary_image_url }}')" class="w-20 h-16 rounded-xl overflow-hidden border-2 border-amber-500 shrink-0">
                            <img src="{{ $hotel->primary_image_url }}" class="w-full h-full object-cover">
                        </button>
                        @foreach($hotel->images as $img)
                            <button type="button" onclick="changeImage('{{ $img }}')" class="w-20 h-16 rounded-xl overflow-hidden border border-slate-200 shrink-0 hover:opacity-80 transition-opacity">
                                <img src="{{ $img }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Description -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 space-y-3">
                <h2 class="text-base font-black text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-100">
                    <i class="fa-solid fa-circle-info text-amber-500"></i> {{ __('دەربارەی ئوتێلەکە') }}
                </h2>
                <div class="text-sm text-slate-700 leading-relaxed space-y-3">
                    {!! nl2br(e($hotel->description ?? __('زانیاری وەسف نەنووسراوە.'))) !!}
                </div>
            </div>

            <!-- Amenities -->
            @if(!empty($hotel->amenities) && is_array($hotel->amenities))
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 space-y-4">
                    <h2 class="text-base font-black text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-100">
                        <i class="fa-solid fa-bell-concierge text-amber-500"></i> {{ __('خزمەتگوزاری و تایبەتمەندییەکان') }}
                    </h2>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($hotel->amenities as $amKey)
                            @if(isset($amenityDefinitions[$amKey]))
                                <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-200/80">
                                    <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-sm shrink-0">
                                        <i class="fa-solid {{ $amenityDefinitions[$amKey]['icon'] }}"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-800">{{ __($amenityDefinitions[$amKey]['label']) }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Location on Map -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 flex-wrap gap-2">
                    <div>
                        <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-map-location-dot text-amber-500"></i> {{ __('لۆکەیشن لەسەر نەخشەی سلێمانی') }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            {{ __('سلێمانی') }}، {{ __($hotel->neighborhood) }} • {{ $hotel->address ?? __('سلێمانی') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <!-- Modern / Satellite Switcher Dock -->
                        <div class="p-0.5 rounded-xl bg-slate-100 flex items-center gap-0.5 border border-slate-200/80 text-xs">
                            <button type="button" id="btnHotelDetailStreet" onclick="switchHotelDetailTile('street')" class="px-2.5 py-1 rounded-lg bg-slate-900 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1">
                                <i class="fa-solid fa-map text-amber-400 text-[10px]"></i>
                                <span>{{ __('مۆدێرن') }}</span>
                            </button>
                            <button type="button" id="btnHotelDetailSat" onclick="switchHotelDetailTile('satellite')" class="px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 font-bold text-[11px] transition-all flex items-center gap-1">
                                <i class="fa-solid fa-earth-americas text-blue-500 text-[10px]"></i>
                                <span>{{ __('سەتەلایت') }}</span>
                            </button>
                        </div>

                        <!-- One-Click Live Turn-by-Turn Route Button -->
                        <button type="button" id="btnHotelNavigation" onclick="startHotelNavigation()" class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-black text-xs shadow-md shadow-blue-500/30 flex items-center gap-1.5 transition-all hover:scale-105 active:scale-95 cursor-pointer">
                            <i class="fa-solid fa-diamond-turn-right text-xs"></i>
                            <span id="btnHotelNavText">{{ __('ڕێگە لە شوێنی منەوە') }}</span>
                        </button>
                    </div>
                </div>

                <!-- Leaflet Map Container with Floating HUD -->
                <div class="relative rounded-2xl overflow-hidden border border-slate-200/90 shadow-inner group">
                    <div id="singleHotelMap" style="width: 100%; height: 460px; min-height: 460px; z-index: 1;"></div>

                    <!-- Clean Zoom Controls -->
                    <div class="absolute left-3 top-3 z-[400] flex flex-col gap-1 pointer-events-auto">
                        <button type="button" onclick="hotelMapZoomIn()" class="w-8 h-8 rounded-xl bg-white/95 hover:bg-white text-slate-800 flex items-center justify-center text-xs font-black shadow-md border border-slate-200/80 transition-all hover:scale-105 active:scale-95" title="{{ __('نزیککردنەوە') }}">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </button>
                        <button type="button" onclick="hotelMapZoomOut()" class="w-8 h-8 rounded-xl bg-white/95 hover:bg-white text-slate-800 flex items-center justify-center text-xs font-black shadow-md border border-slate-200/80 transition-all hover:scale-105 active:scale-95" title="{{ __('دوورخستنەوە') }}">
                            <i class="fa-solid fa-minus text-xs"></i>
                        </button>
                        <button type="button" onclick="recenterHotelMap()" class="w-8 h-8 rounded-xl bg-white/95 hover:bg-white text-slate-800 flex items-center justify-center text-xs font-black shadow-md border border-slate-200/80 transition-all hover:scale-105 active:scale-95" title="{{ __('گەڕانەوە بۆ ئوتێلەکە') }}">
                            <i class="fa-solid fa-crosshairs text-xs"></i>
                        </button>
                    </div>

                    <!-- Floating Navigation Route HUD Bar -->
                    <div id="hotelDetailNavigationHUD" class="hidden absolute bottom-3 inset-x-3 z-[450] pointer-events-auto">
                        <div class="bg-slate-950/95 backdrop-blur-md text-white p-3 rounded-2xl shadow-2xl border border-white/20 space-y-2">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-2.5">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm shadow-md shadow-blue-500/40 shrink-0 animate-bounce">
                                        <i class="fa-solid fa-diamond-turn-right text-xs"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-[11px] font-bold text-blue-300">{{ __('ڕێگەی گەیشتن:') }}</span>
                                            <span id="hotelRouteDistanceBadge" class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-500/30 text-emerald-300 font-extrabold border border-emerald-400/30"></span>
                                        </div>
                                        <div class="text-xs font-black text-white truncate max-w-[260px]">
                                            {{ $hotel->name }} • {{ __($hotel->neighborhood) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5 shrink-0">
                                    <a id="hotelDetailGoogleMapsBtn" href="https://www.google.com/maps/dir/?api=1&destination={{ $hotel->latitude }},{{ $hotel->longitude }}&travelmode=driving" target="_blank" class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-[11px] font-bold transition-all shadow flex items-center gap-1.5">
                                        <i class="fa-solid fa-location-arrow text-[10px]"></i>
                                        <span>Google Maps</span>
                                    </a>
                                    <button type="button" onclick="startHotelNavigation(true)" class="px-2.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white text-[11px] font-bold transition-colors" title="{{ __('نوێکردنەوەی شوێنی GPS') }}">
                                        <i class="fa-solid fa-crosshairs"></i>
                                    </button>
                                    <button type="button" onclick="clearHotelDetailRoute()" class="px-2.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white text-[11px] font-bold transition-colors" title="{{ __('لابردنی هێڵی ڕێگە') }}">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                
                <div class="text-xs text-slate-500 flex items-center gap-2">
                    <i class="fa-solid fa-location-crosshairs text-amber-500"></i>
                    <span>{{ __('ناونیشان:') }} {{ $hotel->address ?? __('سلێمانی') . '، ' . __($hotel->neighborhood) }}</span>
                </div>
            </div>

        </div>

        <!-- Right: Sticky Booking & Contact Card (4 Cols) -->
        <div class="lg:col-span-4 sticky top-24 space-y-4">
            
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 space-y-5">
                <div class="text-center pb-4 border-b border-slate-100 space-y-1">
                    <span class="text-xs font-bold text-slate-500">{{ __('نرخی مانەوەی یەک شەو') }}</span>
                    <div class="text-3xl font-black text-emerald-600">
                        ${{ number_format($hotel->price_per_night) }}
                        <span class="text-xs font-normal text-slate-500">{{ __('/ شەو') }}</span>
                    </div>
                    <span class="inline-block text-[11px] text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-full font-bold">
                        {{ __('بەپێی ژوور و بەروار دەگۆڕێت') }}
                    </span>
                </div>

                <!-- Direct Action Buttons -->
                <div class="space-y-2.5">
                    @if($hotel->clean_whatsapp)
                        <a href="https://wa.me/{{ $hotel->clean_whatsapp }}?text={{ urlencode('سڵاو، دەمەوێت لە ئوتێلی ' . $hotel->name . ' حجز بکەم. تکایە زانیاری ژووری بەردەست و نرخەکانم پێ بڵێن.') }}" target="_blank" class="w-full py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm text-center shadow-md transition-all flex items-center justify-center gap-2 hover:scale-[1.02] active:scale-[0.98]">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                            <span>{{ __('حجزکردن لە ڕێگەی واتسئەپ') }}</span>
                        </a>
                    @endif

                    <a href="tel:{{ $hotel->phone }}" class="w-full py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm text-center shadow-md transition-all flex items-center justify-center gap-2 hover:scale-[1.02] active:scale-[0.98]">
                        <i class="fa-solid fa-phone text-xs"></i>
                        <span>{{ __('پەیوەندی بە ڕیسێپشن') }} ({{ $hotel->phone }})</span>
                    </a>

                    @if($hotel->website)
                        <a href="{{ $hotel->website }}" target="_blank" class="w-full py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs text-center transition-colors flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-globe text-xs"></i>
                            <span>{{ __('سەردانی وێبسایتی فەرمی') }}</span>
                        </a>
                    @endif
                </div>

                <!-- Hotel Info List -->
                <div class="pt-4 border-t border-slate-100 space-y-2.5 text-xs text-slate-600">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-slate-500">{{ __('شار / گەڕەک:') }}</span>
                        <span class="font-bold text-slate-800">{{ __('سلێمانی') }}، {{ __($hotel->neighborhood) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-slate-500">{{ __('پلەی ئوتێل:') }}</span>
                        <span class="font-bold text-amber-500">{{ str_repeat('★', $hotel->stars) }} ({{ $hotel->stars }} {{ __('ئەستێرە') }})</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-slate-500">{{ __('ڕیسێپشن:') }}</span>
                        <span class="font-bold text-emerald-600">{{ __('٢٤ کاتژمێر کراوەیە') }}</span>
                    </div>
                </div>

                <!-- Share -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-center gap-3">
                    <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('{{ __('بەستەری ئوتێل کۆپیکرا!') }}');" class="text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1">
                        <i class="fa-solid fa-link text-[11px]"></i>
                        <span>{{ __('کۆپیکردنی بەستەر') }}</span>
                    </button>
                </div>

            </div>

        </div>

    </div>

    <!-- Related Hotels -->
    @if(isset($relatedHotels) && $relatedHotels->count() > 0)
        <div class="pt-8 border-t border-slate-200/80 space-y-4">
            <h2 class="text-lg font-black text-slate-900">{{ __('ئوتێلە هاوشێوەکان لە سلێمانی') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($relatedHotels as $rel)
                    <a href="{{ route('hotels.show', $rel->slug) }}" class="bg-white rounded-3xl p-4 border border-slate-200/90 shadow-sm hover:shadow-lg transition-all group flex items-center gap-3">
                        <img src="{{ $rel->primary_image_url }}" alt="{{ $rel->name }}" class="w-16 h-16 rounded-2xl object-cover shrink-0">
                        <div class="overflow-hidden">
                            <div class="text-[10px] text-amber-500 font-bold">{{ str_repeat('★', $rel->stars) }}</div>
                            <h4 class="text-xs font-black text-slate-900 group-hover:text-amber-600 transition-colors truncate">{{ $rel->name }}</h4>
                            <div class="text-[11px] text-slate-500 truncate">{{ __($rel->neighborhood) }}</div>
                            <div class="text-xs font-black text-emerald-600 mt-0.5">${{ number_format($rel->price_per_night) }} {{ __('/ شەو') }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    function changeImage(url) {
        document.getElementById('mainGalleryImage').src = url;
    }

    let singleHotelMapInstance = null;
    let hotelDetailTileStreet = null;
    let hotelDetailTileSat = null;
    let currentDetailTile = null;

    let hotelDetailRouteLayer = null;
    let hotelDetailRouteCasing = null;
    let hotelDetailUserMarker = null;
    let singleHotelMarker = null;

    const hotelTargetLat = parseFloat("{{ $hotel->latitude }}") || 35.5558;
    const hotelTargetLng = parseFloat("{{ $hotel->longitude }}") || 45.4351;
    const hotelTargetTitle = "{{ addslashes($hotel->name) }}";
    const hotelTargetNeighborhood = "{{ addslashes($hotel->neighborhood) }}";

    document.addEventListener('DOMContentLoaded', function () {
        singleHotelMapInstance = L.map('singleHotelMap', {
            center: [hotelTargetLat + 0.0018, hotelTargetLng],
            zoom: 15,
            zoomControl: false,
            scrollWheelZoom: false
        });

        // 1. Street Layer
        hotelDetailTileStreet = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | خانوبەرەی سلێمانی'
        });

        // 2. Satellite Layer
        hotelDetailTileSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: '&copy; Esri World Imagery | سلێمانی'
        });

        currentDetailTile = hotelDetailTileStreet;
        currentDetailTile.addTo(singleHotelMapInstance);

        const customIcon = L.divIcon({
            className: 'custom-pin-wrapper',
            html: `
                <div class="hotel-map-pin is-active" title="{{ addslashes($hotel->name) }}">
                    <i class="fa-solid fa-hotel text-amber-400 text-xs"></i>
                    <span class="hotel-pin-stars">{{ str_repeat('★', $hotel->stars) }}</span>
                    <span class="hotel-pin-price">\${{ number_format($hotel->price_per_night) }}</span>
                </div>
            `,
            iconSize: [110, 32],
            iconAnchor: [55, 32],
            popupAnchor: [0, -36]
        });

        singleHotelMarker = L.marker([hotelTargetLat, hotelTargetLng], { icon: customIcon }).addTo(singleHotelMapInstance);

        const popupContent = `
            <div class="p-3.5 space-y-3" dir="rtl" style="font-family: 'Vazirmatn', sans-serif; min-width: 275px; max-width: 310px;">
                <!-- Top Row: Thumbnail + Hotel Title + Stars -->
                <div class="flex items-start gap-3">
                    <img src="{{ $hotel->primary_image_url }}" alt="{{ addslashes($hotel->name) }}" class="w-16 h-16 rounded-2xl object-cover shadow-sm border border-slate-200/80 shrink-0">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-1 mb-1">
                            <span class="inline-flex items-center gap-0.5 text-[10px] font-black text-amber-500 bg-amber-50 border border-amber-200/70 px-2 py-0.5 rounded-full">
                                <span>{{ str_repeat('★', $hotel->stars) }}</span>
                                <span>{{ $hotel->stars }}★</span>
                            </span>
                            <span class="text-xs font-black text-emerald-600 bg-emerald-50 border border-emerald-200/70 px-2 py-0.5 rounded-full">
                                \${{ number_format($hotel->price_per_night) }} <span class="text-[9px] font-normal text-emerald-700">/شەو</span>
                            </span>
                        </div>
                        <h4 class="font-black text-xs sm:text-sm text-slate-900 leading-snug truncate">{{ addslashes($hotel->name) }}</h4>
                        <div class="flex items-center gap-1 text-[11px] text-slate-500 mt-1 truncate">
                            <i class="fa-solid fa-location-dot text-amber-500 text-[10px]"></i>
                            <span class="truncate">سلێمانی، {{ addslashes($hotel->neighborhood) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Action Buttons -->
                <div class="flex items-center gap-1.5 pt-2.5 border-t border-slate-100">
                    <button type="button" onclick="startHotelNavigation()" class="flex-1 py-1.5 px-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold transition-all shadow-sm flex items-center justify-center gap-1.5 hover:scale-105 active:scale-95 cursor-pointer" title="ڕێ نیشاندەر بەسەر نەخشەدا">
                        <i class="fa-solid fa-diamond-turn-right text-[10px] text-blue-200"></i>
                        <span>ڕێگە</span>
                    </button>

                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $hotel->latitude }},{{ $hotel->longitude }}&travelmode=driving" target="_blank" class="py-1.5 px-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-[11px] font-bold transition-all shadow-sm flex items-center gap-1" title="Google Maps">
                        <i class="fa-solid fa-location-arrow text-[9px] text-amber-400"></i>
                        <span>Google</span>
                    </a>

                    @if($hotel->clean_whatsapp)
                        <a href="https://wa.me/{{ $hotel->clean_whatsapp }}?text={{ urlencode('سڵاو، دەمەوێت لە ئوتێلی ' . $hotel->name . ' حجز بکەم.') }}" target="_blank" class="w-8 h-8 rounded-xl bg-[#25D366] hover:bg-[#20ba5a] text-white flex items-center justify-center text-sm shadow-sm transition-all hover:scale-105 active:scale-95" title="واتسئەپ">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    @endif

                    <a href="tel:{{ $hotel->phone }}" class="w-8 h-8 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center text-xs shadow-sm transition-all hover:scale-105 active:scale-95" title="پەیوەندی">
                        <i class="fa-solid fa-phone"></i>
                    </a>
                </div>
            </div>
        `;

        singleHotelMarker.bindPopup(popupContent, {
            maxWidth: 320,
            minWidth: 275,
            autoPan: true,
            autoPanPaddingTopLeft: [20, 50],
            autoPanPaddingBottomRight: [20, 30]
        }).openPopup();

        setTimeout(function () {
            if (singleHotelMapInstance) {
                singleHotelMapInstance.invalidateSize();
                singleHotelMapInstance.setView([hotelTargetLat + 0.0018, hotelTargetLng], 15);
            }
        }, 250);

        setupMobileFriendlyMapGestures(singleHotelMapInstance, 'singleHotelMap');
    });

    // Layer switch
    function switchHotelDetailTile(type) {
        if (!singleHotelMapInstance) return;
        const btnStreet = document.getElementById('btnHotelDetailStreet');
        const btnSat = document.getElementById('btnHotelDetailSat');

        const activeClass = 'px-2.5 py-1 rounded-lg bg-slate-900 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1';
        const inactiveClass = 'px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 font-bold text-[11px] transition-all flex items-center gap-1';

        if (type === 'satellite') {
            if (singleHotelMapInstance.hasLayer(hotelDetailTileStreet)) singleHotelMapInstance.removeLayer(hotelDetailTileStreet);
            hotelDetailTileSat.addTo(singleHotelMapInstance);
            if (btnSat) btnSat.className = activeClass;
            if (btnStreet) btnStreet.className = inactiveClass;
        } else {
            if (singleHotelMapInstance.hasLayer(hotelDetailTileSat)) singleHotelMapInstance.removeLayer(hotelDetailTileSat);
            hotelDetailTileStreet.addTo(singleHotelMapInstance);
            if (btnStreet) btnStreet.className = activeClass;
            if (btnSat) btnSat.className = inactiveClass;
        }
    }

    // Zoom controls
    function hotelMapZoomIn() {
        if (singleHotelMapInstance) singleHotelMapInstance.zoomIn();
    }
    function hotelMapZoomOut() {
        if (singleHotelMapInstance) singleHotelMapInstance.zoomOut();
    }
    function recenterHotelMap() {
        if (singleHotelMapInstance) {
            singleHotelMapInstance.setView([hotelTargetLat + 0.0018, hotelTargetLng], 15);
            if (singleHotelMarker) singleHotelMarker.openPopup();
        }
    }

    // Navigation Route Handling
    function startHotelNavigation(forceRefresh = false) {
        const btnText = document.getElementById('btnHotelNavText');
        if (btnText) btnText.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>دیاریکردنی ڕێگە...</span>';

        // 1. Instant fallback route from Sulaymaniyah center (zero delay!)
        drawFallbackRouteFromCenter();

        // 2. Background GPS check
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    let curLat = pos.coords.latitude;
                    let curLng = pos.coords.longitude;
                    let label = 'شوێنی تۆ (GPS)';

                    const dLat = (curLat - 35.5558) * 111;
                    const dLng = (curLng - 45.4351) * 90.3;
                    const distKm = Math.sqrt(dLat * dLat + dLng * dLng);

                    if (distKm > 45) {
                        curLat = 35.5558;
                        curLng = 45.4351;
                        label = 'سەنتەری سلێمانی';
                    }

                    setDetailUserMarker(curLat, curLng, label);
                    fetchAndDrawSingleHotelRoute(curLat, curLng, hotelTargetLat, hotelTargetLng);
                    if (btnText) btnText.innerHTML = '<i class="fa-solid fa-diamond-turn-right text-xs"></i> <span>نوێکردنەوەی ڕێگە</span>';
                },
                function (err) {
                    console.warn('GPS location unavailable, using Sulaymaniyah center:', err);
                    if (btnText) btnText.innerHTML = '<i class="fa-solid fa-diamond-turn-right text-xs"></i> <span>ڕێگە لە سەنتەری شار</span>';
                },
                { enableHighAccuracy: true, timeout: 5000, maximumAge: 60000 }
            );
        }
    }

    function drawFallbackRouteFromCenter() {
        const centerLat = 35.5558;
        const centerLng = 45.4351;
        setDetailUserMarker(centerLat, centerLng, 'سەنتەری سلێمانی');
        fetchAndDrawSingleHotelRoute(centerLat, centerLng, hotelTargetLat, hotelTargetLng);
    }

    function setDetailUserMarker(lat, lng, label) {
        if (!singleHotelMapInstance) return;
        if (hotelDetailUserMarker) {
            singleHotelMapInstance.removeLayer(hotelDetailUserMarker);
        }

        const userIcon = L.divIcon({
            className: 'user-gps-container',
            html: `<div class="user-gps-pin cursor-grab active:cursor-grabbing" title="${label}"></div>`,
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        hotelDetailUserMarker = L.marker([lat, lng], { icon: userIcon, draggable: true }).addTo(singleHotelMapInstance);
        hotelDetailUserMarker.bindPopup(`
            <div style="text-align: center; font-weight: bold; padding: 4px; font-family: 'Vazirmatn', sans-serif;" dir="rtl">
                <div class="text-blue-600 mb-0.5 font-black">📍 ${label}</div>
                <div style="font-size: 11px; color: #64748b;">دەتوانیت پینەکە ڕابکێشیت بۆ گۆڕینی خاڵی دەستپێک</div>
            </div>
        `).openPopup();

        hotelDetailUserMarker.on('dragend', function (e) {
            const pos = e.target.getLatLng();
            fetchAndDrawSingleHotelRoute(pos.lat, pos.lng, hotelTargetLat, hotelTargetLng);
        });
    }

    function fetchAndDrawSingleHotelRoute(startLat, startLng, destLat, destLng) {
        if (!singleHotelMapInstance) return;

        if (hotelDetailRouteLayer) { singleHotelMapInstance.removeLayer(hotelDetailRouteLayer); hotelDetailRouteLayer = null; }
        if (hotelDetailRouteCasing) { singleHotelMapInstance.removeLayer(hotelDetailRouteCasing); hotelDetailRouteCasing = null; }

        const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${startLng},${startLat};${destLng},${destLat}?overview=full&geometries=geojson`;
        const directCoords = [[startLat, startLng], [destLat, destLng]];

        fetch(osrmUrl)
            .then(res => res.json())
            .then(data => {
                if (data.code === 'Ok' && data.routes && data.routes.length > 0) {
                    const route = data.routes[0];
                    const distanceKm = (route.distance / 1000).toFixed(1);
                    const durationMin = Math.max(1, Math.round(route.duration / 60));
                    const latLngs = route.geometry.coordinates.map(c => [c[1], c[0]]);
                    renderSingleRoute(latLngs, distanceKm, durationMin, startLat, startLng, destLat, destLng);
                } else {
                    renderSingleRouteDirect(directCoords, startLat, startLng, destLat, destLng);
                }
            })
            .catch(err => {
                console.warn('OSRM route fallback:', err);
                renderSingleRouteDirect(directCoords, startLat, startLng, destLat, destLng);
            });
    }

    function renderSingleRoute(latLngs, distanceKm, durationMin, startLat, startLng, destLat, destLng) {
        hotelDetailRouteCasing = L.polyline(latLngs, {
            color: '#1d4ed8',
            weight: 7,
            opacity: 0.75,
            lineCap: 'round',
            lineJoin: 'round'
        }).addTo(singleHotelMapInstance);

        hotelDetailRouteLayer = L.polyline(latLngs, {
            color: '#38bdf8',
            weight: 4,
            opacity: 0.95,
            lineCap: 'round',
            lineJoin: 'round',
            dashArray: '8, 8'
        }).addTo(singleHotelMapInstance);

        singleHotelMapInstance.fitBounds(hotelDetailRouteLayer.getBounds(), { padding: [50, 50] });

        const hud = document.getElementById('hotelDetailNavigationHUD');
        if (hud) {
            hud.classList.remove('hidden');
            document.getElementById('hotelRouteDistanceBadge').innerText = `${distanceKm} کم • ~${durationMin} خولەک بە ئۆتۆمبێل`;
            const gmaps = document.getElementById('hotelDetailGoogleMapsBtn');
            if (gmaps) {
                gmaps.href = `https://www.google.com/maps/dir/?api=1&origin=${startLat},${startLng}&destination=${destLat},${destLng}&travelmode=driving`;
            }
        }
    }

    function renderSingleRouteDirect(coords, startLat, startLng, destLat, destLng) {
        const dLat = (destLat - startLat) * 111;
        const dLng = (destLng - startLng) * 90.3;
        const directDistKm = Math.sqrt(dLat * dLat + dLng * dLng).toFixed(1);
        const estMin = Math.max(2, Math.round(directDistKm * 2.2));

        hotelDetailRouteCasing = L.polyline(coords, {
            color: '#1e3a8a',
            weight: 5,
            opacity: 0.6,
            dashArray: '6, 6'
        }).addTo(singleHotelMapInstance);

        hotelDetailRouteLayer = L.polyline(coords, {
            color: '#f59e0b',
            weight: 3,
            opacity: 0.9,
            dashArray: '6, 6'
        }).addTo(singleHotelMapInstance);

        singleHotelMapInstance.fitBounds(hotelDetailRouteLayer.getBounds(), { padding: [50, 50] });

        const hud = document.getElementById('hotelDetailNavigationHUD');
        if (hud) {
            hud.classList.remove('hidden');
            document.getElementById('hotelRouteDistanceBadge').innerText = `${directDistKm} کم (ڕاستەوخۆ) • ~${estMin} خولەک`;
            const gmaps = document.getElementById('hotelDetailGoogleMapsBtn');
            if (gmaps) {
                gmaps.href = `https://www.google.com/maps/dir/?api=1&origin=${startLat},${startLng}&destination=${destLat},${destLng}&travelmode=driving`;
            }
        }
    }

    function clearHotelDetailRoute() {
        if (hotelDetailRouteLayer && singleHotelMapInstance) {
            singleHotelMapInstance.removeLayer(hotelDetailRouteLayer);
            hotelDetailRouteLayer = null;
        }
        if (hotelDetailRouteCasing && singleHotelMapInstance) {
            singleHotelMapInstance.removeLayer(hotelDetailRouteCasing);
            hotelDetailRouteCasing = null;
        }
        if (hotelDetailUserMarker && singleHotelMapInstance) {
            singleHotelMapInstance.removeLayer(hotelDetailUserMarker);
            hotelDetailUserMarker = null;
        }
        const hud = document.getElementById('hotelDetailNavigationHUD');
        if (hud) hud.classList.add('hidden');
        recenterHotelMap();
    }

    // Touch gesture helper for mobile scrolling
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
        toggleBtn.className = 'absolute top-3 right-3 z-[400] flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/95 text-slate-800 text-[11px] font-bold shadow-md border border-slate-200/90 active:scale-95 transition-all lg:hidden pointer-events-auto select-none';
        toggleBtn.innerHTML = '<i class="fa-solid fa-hand-pointer text-amber-500 text-xs"></i> <span>دەستکاری نەخشە</span>';

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
                toggleBtn.className = 'absolute top-3 right-3 z-[400] flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500 text-slate-950 font-black text-[11px] shadow-lg border border-amber-300 active:scale-95 transition-all lg:hidden pointer-events-auto select-none';
                toggleBtn.innerHTML = '<i class="fa-solid fa-lock-open text-xs"></i> <span>نەخشە چالاکە</span>';
            } else {
                mapInstance.dragging.disable();
                mapInstance.touchZoom.disable();
                mapInstance.doubleClickZoom.disable();
                toggleBtn.className = 'absolute top-3 right-3 z-[400] flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/95 text-slate-800 text-[11px] font-bold shadow-md border border-slate-200/90 active:scale-95 transition-all lg:hidden pointer-events-auto select-none';
                toggleBtn.innerHTML = '<i class="fa-solid fa-hand-pointer text-amber-500 text-xs"></i> <span>دەستکاری نەخشە</span>';
            }
        });

        container.parentElement.appendChild(toggleBtn);
    }
</script>
@endpush
