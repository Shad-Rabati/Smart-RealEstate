@extends('layouts.app')

@section('title', $property->title . ' | خانوبەرەی سلێمانی')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-700 font-medium flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-emerald-700">سەرەکی</a>
        <i class="fa-solid fa-chevron-left text-[10px] text-slate-400"></i>
        <a href="{{ route('properties.index') }}" class="hover:text-emerald-700">گەڕانی خانوو</a>
        <i class="fa-solid fa-chevron-left text-[10px] text-slate-400"></i>
        <a href="{{ route('properties.index', ['neighborhood' => $property->neighborhood]) }}" class="hover:text-emerald-700">{{ $property->neighborhood }}</a>
        <i class="fa-solid fa-chevron-left text-[10px] text-slate-400"></i>
        <span class="text-slate-900 font-bold truncate max-w-xs">{{ $property->title }}</span>
    </nav>

    <!-- Top Headline & Price Header -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            <div class="flex flex-wrap items-center gap-2">
                <!-- Status Badge -->
                @if($property->status === 'available')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        {{ __('messages.available_status') }}
                    </span>
                @elseif($property->status === 'reserved')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-slate-100 text-slate-800 border border-slate-300">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-500"></span>
                        {{ __('messages.reserved_status') }}
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-800 border border-rose-300">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        {{ __('messages.rented_status') }}
                    </span>
                @endif

                <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                    {{ $property->property_type_label }}
                </span>

                <span class="text-xs text-slate-600">
                    <i class="fa-solid fa-eye ml-1"></i> {{ $property->views_count }} {{ __('messages.views') }}
                </span>

                @if($property->has_video)
                    <a href="#videoTourSection" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 transition-colors">
                        <i class="fa-solid fa-circle-play text-rose-600"></i>
                        <span>{{ __('messages.video_tour') }}</span>
                    </a>
                @endif
            </div>

            <h1 class="text-xl sm:text-2xl md:text-3xl font-black text-slate-900 leading-tight">
                {{ $property->title }}
            </h1>

            <div class="flex items-center gap-2 text-sm text-slate-700">
                <i class="fa-solid fa-location-dot text-emerald-600"></i>
                <span>سلێمانی، {{ $property->neighborhood }}</span>
                @if($property->street_address)
                    <span class="text-slate-400">|</span>
                    <span class="text-slate-700">{{ $property->street_address }}</span>
                @endif
            </div>
        </div>

        <!-- Price Tag & Actions -->
        <div class="flex flex-col sm:flex-row md:flex-col items-stretch sm:items-end gap-3 flex-shrink-0">
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 sm:p-5 text-center sm:text-left">
                <div class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-1">{{ __('messages.rent_price') }}</div>
                <div class="text-3xl font-black text-emerald-600">
                    {{ $property->formatted_price }}
                </div>
            </div>

            <!-- Favorite Button -->
            <button type="button" 
                    onclick="toggleFavorite({{ $property->id }}, '{{ addslashes($property->title) }}')"
                    data-favorite-id="{{ $property->id }}"
                    class="px-4 py-2.5 rounded-2xl bg-white hover:bg-rose-50 text-slate-700 hover:text-rose-600 border border-slate-200/90 shadow-sm transition-all flex items-center justify-center gap-2 text-xs font-bold active:scale-95">
                <i class="fa-regular fa-heart text-sm text-rose-500"></i>
                <span class="favorite-btn-text">پاشەکەوتکردن لە دڵخوازەکان</span>
            </button>
        </div>
    </div>

    <!-- Gallery & Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Images, Specs, Description & Map (col 8) -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- Main Photo & Gallery -->
            <div class="bg-white rounded-3xl p-4 shadow-sm border border-slate-200 space-y-3">
                <div class="w-full h-80 sm:h-96 md:h-[420px] rounded-2xl overflow-hidden bg-slate-100">
                    <img id="mainDisplayImage" src="{{ $property->primary_image_url }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                </div>

                @if($property->gallery_images && count($property->gallery_images) > 1)
                    <div class="flex items-center gap-3 overflow-x-auto pb-2">
                        @foreach($property->gallery_images as $img)
                            <button type="button" onclick="document.getElementById('mainDisplayImage').src = '{{ $img }}'" class="w-20 h-16 rounded-xl overflow-hidden border-2 border-transparent hover:border-emerald-500 flex-shrink-0 transition-all">
                                <img src="{{ $img }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Specs Grid (Rooms, Area, etc.) -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200">
                <h3 class="text-base font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-emerald-600"></i> تایبەتمەندییە سەرەکییەکان
                </h3>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                    <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                        <i class="fa-solid fa-bed text-emerald-600 text-lg mb-1 block"></i>
                        <span class="text-xs text-slate-700 block font-medium">ژووری خەوتن</span>
                        <span class="text-base font-black text-slate-900">{{ $property->bedrooms }} ژوور</span>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                        <i class="fa-solid fa-bath text-emerald-600 text-lg mb-1 block"></i>
                        <span class="text-xs text-slate-700 block font-medium">حەمام و دەستشۆر</span>
                        <span class="text-base font-black text-slate-900">{{ $property->bathrooms }}</span>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                        <i class="fa-solid fa-ruler-combined text-emerald-600 text-lg mb-1 block"></i>
                        <span class="text-xs text-slate-700 block font-medium">ڕووبەر</span>
                        <span class="text-base font-black text-slate-900">{{ $property->area_sqm }} مەتر²</span>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                        <i class="fa-solid fa-couch text-emerald-600 text-lg mb-1 block"></i>
                        <span class="text-xs text-slate-700 block font-medium">کەلوپەل</span>
                        <span class="text-base font-black text-slate-900">{{ $property->is_furnished ? 'مەفروش' : 'بێ کەلوپەل' }}</span>
                    </div>
                </div>

                <!-- Secondary Badges -->
                <div class="flex flex-wrap items-center gap-2 mt-4 pt-4 border-t border-slate-100 text-xs">
                    <span class="px-3 py-1.5 rounded-xl {{ $property->has_parking ? 'bg-emerald-50 text-emerald-800' : 'bg-slate-100 text-slate-400' }} font-bold flex items-center gap-1.5">
                        <i class="fa-solid fa-square-parking"></i> گەراج / پارکینگ: {{ $property->has_parking ? 'هەیەتی' : 'نییەتی' }}
                    </span>
                    <span class="px-3 py-1.5 rounded-xl {{ $property->has_elevator ? 'bg-emerald-50 text-emerald-800' : 'bg-slate-100 text-slate-400' }} font-bold flex items-center gap-1.5">
                        <i class="fa-solid fa-elevator"></i> ئەسانسۆر: {{ $property->has_elevator ? 'هەیەتی' : 'نییەتی' }}
                    </span>
                    <span class="px-3 py-1.5 rounded-xl {{ $property->has_generator ? 'bg-emerald-50 text-emerald-800' : 'bg-slate-100 text-slate-400' }} font-bold flex items-center gap-1.5">
                        <i class="fa-solid fa-bolt"></i> موەلیدەی بەردەوام: {{ $property->has_generator ? 'هەیەتی' : 'نییەتی' }}
                    </span>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 space-y-4">
                <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-align-right text-emerald-600"></i> وەسف و وردەکاری خانوو
                </h3>
                <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $property->description ?: 'هیچ وەسفێکی زیادە دانەنراوە.' }}
                </div>

                @if($property->features && count($property->features) > 0)
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <h4 class="text-xs font-bold text-slate-800 mb-2">خزمەتگوزارییەکانی تر:</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($property->features as $feature)
                                <span class="px-3 py-1 rounded-xl bg-slate-100 text-slate-700 text-xs font-medium flex items-center gap-1.5">
                                    <i class="fa-solid fa-check text-emerald-600 text-xs"></i> {{ $feature }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            @if($property->has_video)
                <!-- Video Tour / Reels Section -->
                <div id="videoTourSection" class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-circle-play"></i>
                            </span>
                            <span>گەشتی ڤیدیۆیی خانوو (Video Tour / Reels)</span>
                        </h3>
                        <a href="{{ $property->video_url }}" target="_blank" rel="noopener" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1">
                            <span>کردنەوە لە سەرچاوە</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>

                    @if($property->video_embed_url)
                        <!-- Responsive Embedded Player -->
                        <div class="relative w-full aspect-video rounded-2xl overflow-hidden bg-slate-950 shadow-inner">
                            <iframe 
                                src="{{ $property->video_embed_url }}" 
                                title="Video Tour" 
                                class="w-full h-full border-0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        </div>
                    @else
                        <!-- Direct / Social Link Card (Instagram / TikTok / others) -->
                        <div class="p-6 rounded-2xl bg-gradient-to-r from-slate-900 via-slate-850 to-slate-900 text-white flex flex-col sm:flex-row items-center justify-between gap-4 border border-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-rose-500/20 text-rose-400 flex items-center justify-center text-xl flex-shrink-0">
                                    <i class="fa-solid fa-play"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-white">ڤیدیۆی تەواوی ئەم خانووە ببینە</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">بە کوالێتی بەرز لە {{ ucfirst($property->video_type) }} بڵاوکراوەتەوە</p>
                                </div>
                            </div>
                            <a href="{{ $property->video_url }}" target="_blank" rel="noopener" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs flex items-center gap-2 transition-all hover:scale-105 shadow-md shrink-0">
                                <i class="fa-solid fa-circle-play"></i>
                                <span>سەیرکردنی ڤیدیۆ</span>
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Location Interactive Map & Live Navigation Route -->
            <div class="bg-white rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200 space-y-4" id="propertyMapCard">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-map-location-dot text-emerald-600"></i>
                            <span>شوێنی ورد لەسەر نەخشەی سلێمانی</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            سلێمانی، گەڕەکی {{ $property->neighborhood }} • {{ $property->street_address }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <!-- Modern / Satellite Switcher Dock -->
                        <div class="p-0.5 rounded-xl bg-slate-100 flex items-center gap-0.5 border border-slate-200/80 text-xs">
                            <button type="button" id="btnDetailTileStreet" onclick="switchDetailMapTile('street')" class="px-2.5 py-1 rounded-lg bg-slate-900 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1">
                                <i class="fa-solid fa-map text-emerald-400 text-[10px]"></i>
                                <span>مۆدێرن</span>
                            </button>
                            <button type="button" id="btnDetailTileSat" onclick="switchDetailMapTile('satellite')" class="px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 font-bold text-[11px] transition-all flex items-center gap-1">
                                <i class="fa-solid fa-earth-americas text-blue-500 text-[10px]"></i>
                                <span>سەتەلایت</span>
                            </button>
                        </div>

                        <!-- One-Click Live Turn-by-Turn Route Button -->
                        <button type="button" id="btnDetailNavigation" onclick="startDetailNavigation()" class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-black text-xs shadow-md shadow-blue-500/30 flex items-center gap-1.5 transition-all hover:scale-105 active:scale-95 cursor-pointer">
                            <i class="fa-solid fa-diamond-turn-right text-xs"></i>
                            <span id="btnDetailNavText">ڕێگە نیشاندان لە شوێنی تۆوە</span>
                        </button>
                    </div>
                </div>

                <!-- Leaflet Map Container with Floating HUD -->
                <div class="relative rounded-2xl overflow-hidden border border-slate-200/90 shadow-inner group">
                    <div id="propertyDetailMap" class="w-full h-96 sm:h-[440px] rounded-2xl z-0"></div>

                    <!-- Clean Zoom Controls -->
                    <div class="absolute left-3 top-3 z-[400] flex flex-col gap-1 pointer-events-auto">
                        <button type="button" onclick="detailMapZoomIn()" class="w-8 h-8 rounded-xl bg-white/95 hover:bg-white text-slate-800 flex items-center justify-center text-xs font-black shadow-md border border-slate-200/80 transition-all hover:scale-105 active:scale-95" title="نزیککردنەوە">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </button>
                        <button type="button" onclick="detailMapZoomOut()" class="w-8 h-8 rounded-xl bg-white/95 hover:bg-white text-slate-800 flex items-center justify-center text-xs font-black shadow-md border border-slate-200/80 transition-all hover:scale-105 active:scale-95" title="دوورخستنەوە">
                            <i class="fa-solid fa-minus text-xs"></i>
                        </button>
                        <button type="button" onclick="recenterDetailMap()" class="w-8 h-8 rounded-xl bg-white/95 hover:bg-white text-slate-800 flex items-center justify-center text-xs font-black shadow-md border border-slate-200/80 transition-all hover:scale-105 active:scale-95" title="گەڕانەوە بۆ خانووەکە">
                            <i class="fa-solid fa-crosshairs text-xs"></i>
                        </button>
                    </div>

                    <!-- Floating Navigation Route HUD Bar -->
                    <div id="detailNavigationHUD" class="hidden absolute bottom-3 inset-x-3 z-[450] pointer-events-auto">
                        <div class="glass-island-dark text-white p-3 rounded-2xl shadow-2xl border border-white/20 space-y-2">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-2.5">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm shadow-md shadow-blue-500/40 flex-shrink-0 animate-bounce">
                                        <i class="fa-solid fa-diamond-turn-right text-xs"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-[11px] font-bold text-blue-300">ڕێگەی گەیشتن:</span>
                                            <span id="detailRouteDistanceBadge" class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-500/30 text-emerald-300 font-extrabold border border-emerald-400/30"></span>
                                        </div>
                                        <div class="text-xs font-black text-white truncate max-w-[260px]">
                                            {{ $property->title }} • {{ $property->neighborhood }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    <a id="detailGoogleMapsBtn" href="https://www.google.com/maps/dir/?api=1&destination={{ $property->latitude }},{{ $property->longitude }}&travelmode=driving" target="_blank" class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-[11px] font-bold transition-all shadow flex items-center gap-1.5">
                                        <i class="fa-solid fa-location-arrow text-[10px]"></i>
                                        <span>Google Maps</span>
                                    </a>
                                    <button type="button" onclick="startDetailNavigation(true)" class="px-2.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white text-[11px] font-bold transition-colors" title="نوێکردنەوەی شوێنی GPS">
                                        <i class="fa-solid fa-crosshairs"></i>
                                    </button>
                                    <button type="button" onclick="clearDetailRoute()" class="px-2.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white text-[11px] font-bold transition-colors" title="لابردنی هێڵی ڕێگە">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Helper Bar -->
                <div class="flex items-center justify-between text-xs text-slate-500 pt-1">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-location-dot text-emerald-600"></i>
                        <span>سلێمانی، گەڕەکی {{ $property->neighborhood }} (شوێن لەسەر نەخشە دیاریکراوە)</span>
                    </span>
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $property->latitude }},{{ $property->longitude }}&travelmode=driving" target="_blank" class="text-blue-600 hover:text-blue-800 font-bold hover:underline flex items-center gap-1">
                        <span>کردنەوە لە Google Maps</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                </div>
            </div>

        </div>

        <!-- Right: Agency Contact Sidebar (col 4) -->
        <div class="lg:col-span-4 sticky top-24 space-y-6">
            
            <!-- Agency Box -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 space-y-5">
                <div class="text-xs font-bold text-slate-600 uppercase tracking-wider">
                    نووسینگەی بەرپرسی ئەم موڵکە
                </div>

                <div class="flex items-center gap-4">
                    <img src="{{ $property->agency->logo_url }}" alt="{{ $property->agency->name }}" class="w-16 h-16 rounded-2xl object-cover border border-slate-200">
                    <div>
                        <h4 class="font-bold text-slate-900 text-base">
                            <a href="{{ route('agencies.show', $property->agency->slug) }}" class="hover:text-emerald-700 transition-colors">
                                {{ $property->agency->name }}
                            </a>
                        </h4>
                        @if($property->agency->owner_name)
                            <span class="text-xs text-slate-700 block">خاوەنکار: {{ $property->agency->owner_name }}</span>
                        @endif
                        <span class="text-xs text-emerald-700 font-bold block mt-0.5">
                            <i class="fa-solid fa-circle-check text-xs"></i> نووسینگەی باوەڕپێکراو
                        </span>
                    </div>
                </div>

                <div class="text-xs text-slate-700 space-y-2 pt-2 border-t border-slate-100">
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-map-pin text-slate-400 mt-0.5"></i>
                        <span>{{ $property->agency->address }}</span>
                    </div>
                </div>

                <!-- Action Buttons: Phone Call & WhatsApp -->
                <div class="space-y-2.5 pt-2">
                    @if($property->agency->whatsapp_link)
                        <a href="{{ $property->agency->whatsapp_link }}?text={{ urlencode('سڵاو، لەسەر ئەم خانووە پەیوەندیتان پێوە دەکەم: ' . $property->title . ' لە پلاتفۆرمی خانوبەرەی سلێمانی ' . url()->current()) }}" target="_blank" class="w-full py-3.5 px-4 rounded-2xl bg-[#25D366] hover:bg-[#20ba5a] text-white font-bold text-sm flex items-center justify-center gap-2 shadow-sm transition-all hover:scale-[1.02]">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                            <span>نامە بنێرە لە واتسئاپ</span>
                        </a>
                    @endif

                    <a href="tel:{{ $property->agency->phone }}" class="w-full py-3.5 px-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm flex items-center justify-center gap-2 shadow-sm transition-all hover:scale-[1.02]">
                        <i class="fa-solid fa-phone text-base"></i>
                        <span>پەیوەندی تەلەفۆنی ({{ $property->agency->phone }})</span>
                    </a>
                </div>

                <div class="pt-3 border-t border-slate-100 text-center">
                    <a href="{{ route('agencies.show', $property->agency->slug) }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800">
                        بینینی هەموو خانووەکانی ئەم نووسینگەیە ←
                    </a>
                </div>
            </div>

            <!-- Safety Note -->
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-xs text-amber-900 space-y-1.5">
                <div class="font-bold flex items-center gap-1.5">
                    <i class="fa-solid fa-shield-halved text-amber-600"></i> ڕێنمایی بۆ کرێچییە بەڕێزەکان
                </div>
                <p class="leading-relaxed text-[11px] text-amber-800">
                    پێش ئەنجامدانی هەر مامەڵەیەک یان پێدانی هەر بڕە پارەیەک، دڵنیابەرەوە لە بینینی خانوو لە نزیکەوە و واژووکردنی گرێبەستی فەرمی لە نووسینگەی خانوبەرە.
                </p>
            </div>

        </div>

    </div>

    <!-- Similar Nearby Properties in Sulaymaniyah -->
    @if($nearbyProperties->count() > 0)
        <div class="space-y-4 pt-8 border-t border-slate-200">
            <h3 class="text-lg font-black text-slate-900">
                خانووی هاوشێوەی بەردەست لە سلێمانی
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($nearbyProperties as $np)
                    <div class="bg-white rounded-2xl p-3.5 shadow-sm border border-slate-200 hover:shadow-md transition-all group">
                        <div class="h-44 rounded-xl overflow-hidden bg-slate-100 relative mb-3">
                            <img src="{{ $np->primary_image_url }}" alt="{{ $np->title }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <span class="absolute top-2 right-2 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-500 text-white shadow-sm">
                                🟢 بەردەستە
                            </span>
                        </div>
                        <h4 class="font-bold text-sm text-slate-900 line-clamp-1 mb-1 group-hover:text-emerald-700">
                            <a href="{{ route('properties.show', $np->slug) }}">
                                {{ $np->title }}
                            </a>
                        </h4>
                        <div class="text-emerald-600 font-extrabold text-sm mb-2">
                            {{ $np->formatted_price }}
                        </div>
                        <div class="flex items-center justify-between text-xs text-slate-700 pt-2 border-t border-slate-100">
                            <span><i class="fa-solid fa-location-dot ml-1 text-slate-400"></i> {{ $np->neighborhood }}</span>
                            <a href="{{ route('properties.show', $np->slug) }}" class="font-bold text-slate-900 hover:text-emerald-600">
                                بینین ←
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    let detailMap = null;
    let detailTileStreet = null;
    let detailTileSat = null;
    let detailRouteLayer = null;
    let detailRouteCasingLayer = null;
    let detailUserMarker = null;
    let detailWatchId = null;
    let lastTrackedCoords = null;
    let lastRouteFetchTime = 0;

    const PROP_LAT = {{ $property->latitude }};
    const PROP_LNG = {{ $property->longitude }};
    const PROP_STATUS = '{{ $property->status }}';

    document.addEventListener('DOMContentLoaded', function () {
        initDetailMap();
    });

    function initDetailMap() {
        if (typeof L === 'undefined') {
            setTimeout(initDetailMap, 150);
            return;
        }

        const mapEl = document.getElementById('propertyDetailMap');
        if (!mapEl) return;

        detailMap = L.map('propertyDetailMap', {
            center: [PROP_LAT, PROP_LNG],
            zoom: 14.5,
            zoomControl: false,
            scrollWheelZoom: false
        });

        detailTileStreet = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap | خانوبەرەی سلێمانی'
        });

        detailTileSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: '&copy; Esri World Imagery | سلێمانی'
        });

        detailTileStreet.addTo(detailMap);

        // Luxury Airbnb-style price pill
        let pinClass = 'pin-badge-available';
        if (PROP_STATUS === 'rented') pinClass = 'pin-badge-rented';
        else if (PROP_STATUS === 'reserved') pinClass = 'pin-badge-reserved';

        const customIcon = L.divIcon({
            className: 'custom-pin-wrapper',
            html: `<div class="map-property-pin ${pinClass}"><span class="pin-dot"></span><span>{{ $property->formatted_price }}</span></div>`,
            iconSize: [64, 28],
            iconAnchor: [32, 28],
            popupAnchor: [0, -28]
        });

        const marker = L.marker([PROP_LAT, PROP_LNG], { icon: customIcon }).addTo(detailMap);

        marker.bindPopup(`
            <div class="p-3 text-right" style="font-family: 'Vazirmatn'; min-width: 220px;">
                <div class="font-extrabold text-sm text-slate-900 mb-1 leading-snug">{{ $property->title }}</div>
                <div class="text-xs text-slate-500 mb-2 flex items-center gap-1">
                    <i class="fa-solid fa-location-dot text-emerald-600"></i>
                    <span>سلێمانی، {{ $property->neighborhood }}</span>
                </div>
                <div class="text-base font-black text-emerald-600 border-t border-slate-100 pt-1.5">{{ $property->formatted_price }}</div>
            </div>
        `, { autoPan: false }).openPopup();

        setTimeout(() => { if (detailMap) detailMap.invalidateSize(); }, 200);
        setTimeout(() => { if (detailMap) detailMap.invalidateSize(); }, 600);

        setupMobileFriendlyMapGestures(detailMap, 'propertyDetailMap');
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
        toggleBtn.className = 'absolute top-3 right-3 z-[400] flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/95 text-slate-800 text-[11px] font-bold shadow-md border border-slate-200/90 active:scale-95 transition-all lg:hidden pointer-events-auto select-none';
        toggleBtn.innerHTML = '<i class="fa-solid fa-hand text-emerald-600 text-[11px]"></i> <span>جووڵاندنی نەخشە</span>';

        toggleBtn.onclick = function (e) {
            e.stopPropagation();
            mapActive = !mapActive;
            if (mapActive) {
                mapInstance.dragging.enable();
                toggleBtn.className = 'absolute top-3 right-3 z-[400] flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-[11px] font-bold shadow-lg ring-2 ring-emerald-300 active:scale-95 transition-all lg:hidden pointer-events-auto select-none';
                toggleBtn.innerHTML = '<i class="fa-solid fa-lock-open text-[11px]"></i> <span>نەخشە کراوەیە (تەواو)</span>';
            } else {
                mapInstance.dragging.disable();
                toggleBtn.className = 'absolute top-3 right-3 z-[400] flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/95 text-slate-800 text-[11px] font-bold shadow-md border border-slate-200/90 active:scale-95 transition-all lg:hidden pointer-events-auto select-none';
                toggleBtn.innerHTML = '<i class="fa-solid fa-hand text-emerald-600 text-[11px]"></i> <span>جووڵاندنی نەخشە</span>';
            }
        };

        const parent = container.parentElement;
        if (parent) parent.appendChild(toggleBtn);

        // On mobile, start with dragging disabled so 1-finger swipe effortlessly scrolls the page!
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

    // Zoom and Recenter Controls
    function detailMapZoomIn() { if (detailMap) detailMap.zoomIn(); }
    function detailMapZoomOut() { if (detailMap) detailMap.zoomOut(); }
    function recenterDetailMap() { if (detailMap) detailMap.setView([PROP_LAT, PROP_LNG], 15); }

    // Switch between Street and Satellite
    function switchDetailMapTile(type) {
        if (!detailMap || !detailTileStreet || !detailTileSat) return;
        const btnStreet = document.getElementById('btnDetailTileStreet');
        const btnSat = document.getElementById('btnDetailTileSat');

        const activeClass = 'px-2.5 py-1 rounded-lg bg-slate-900 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1';
        const inactiveClass = 'px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 font-bold text-[11px] transition-all flex items-center gap-1';

        if (type === 'satellite') {
            if (detailMap.hasLayer(detailTileStreet)) detailMap.removeLayer(detailTileStreet);
            detailTileSat.addTo(detailMap);
            if (btnSat) btnSat.className = activeClass;
            if (btnStreet) btnStreet.className = inactiveClass;
        } else {
            if (detailMap.hasLayer(detailTileSat)) detailMap.removeLayer(detailTileSat);
            detailTileStreet.addTo(detailMap);
            if (btnStreet) btnStreet.className = activeClass;
            if (btnSat) btnSat.className = inactiveClass;
        }
    }

    // Distance calculation helper in meters
    function calcDistanceMeters(lat1, lon1, lat2, lon2) {
        const R = 6371e3;
        const p1 = lat1 * Math.PI / 180;
        const p2 = lat2 * Math.PI / 180;
        const dp = (lat2 - lat1) * Math.PI / 180;
        const dl = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dp/2) * Math.sin(dp/2) + Math.cos(p1) * Math.cos(p2) * Math.sin(dl/2) * Math.sin(dl/2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    // Starting coordinates (Default Sulaymaniyah Center, dynamically updated by user or GPS)
    let currentStartLat = 35.5558;
    let currentStartLng = 45.4351;
    let currentStartName = 'سەنتەری شار';

    // Quick Neighborhood Start Point Switcher
    function setDetailStartLocation(lat, lng, name) {
        currentStartLat = lat;
        currentStartLng = lng;
        currentStartName = name;

        updateDetailUserMarker(lat, lng, name);
        fetchDetailRoute(lat, lng);

        const btnText = document.getElementById('btnDetailNavText');
        if (btnText) {
            btnText.innerHTML = `<i class="fa-solid fa-route text-emerald-400 ml-1"></i> ڕێگە لە ${name}ەوە`;
        }
    }

    // LIVE ROUTE NAVIGATION & INSTANT ROUTE CALCULATION
    function startDetailNavigation(forceRecenter = false) {
        const btnText = document.getElementById('btnDetailNavText');

        // If route is already drawn and user just wants to re-center
        if (!forceRecenter && detailRouteLayer && detailUserMarker) {
            detailMap.fitBounds(detailRouteLayer.getBounds(), { padding: [50, 50] });
            return;
        }

        // 1. Immediately draw road route from current start point (0 delay, instant feedback!)
        updateDetailUserMarker(currentStartLat, currentStartLng, currentStartName);
        fetchDetailRoute(currentStartLat, currentStartLng);

        if (btnText) {
            btnText.innerHTML = `<span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-ping ml-1"></span> ڕێگە کراوەیە (${currentStartName})`;
        }

        // 2. Query GPS in the background without freezing or blocking the user!
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    const uLat = pos.coords.latitude;
                    const uLng = pos.coords.longitude;

                    // Check if user is anywhere in Kurdistan / Sulaymaniyah region (< 45km)
                    const dLat = (uLat - 35.5558) * 111;
                    const dLng = (uLng - 45.4351) * 90.3;
                    const distKm = Math.sqrt(dLat * dLat + dLng * dLng);

                    if (distKm <= 45) {
                        currentStartLat = uLat;
                        currentStartLng = uLng;
                        currentStartName = 'شوێنی تۆ (GPS)';
                        updateDetailUserMarker(uLat, uLng, 'شوێنی ئێستای تۆ (GPS)');
                        fetchDetailRoute(uLat, uLng);
                        if (btnText) {
                            btnText.innerHTML = '<span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-ping ml-1"></span> شوێنی ڕاستەقینەت (GPS)';
                        }
                    }
                },
                function (err) {
                    console.info('GPS not active, using default starting point:', err.message);
                },
                { enableHighAccuracy: false, timeout: 4000, maximumAge: 60000 }
            );
        }
    }

    function updateDetailUserMarker(userLat, userLng, label = 'شوێنی دەستپێک') {
        if (!detailMap) return;

        const popupHtml = `
            <div class="p-1 font-bold text-xs text-center" style="font-family: 'Vazirmatn'">
                <div class="text-blue-600 mb-0.5">📍 ${label}</div>
                <div class="text-[10px] text-slate-500 font-normal">دەتوانیت ئەم پینە ڕابکێشیت بۆ هەر شوێنێک</div>
            </div>
        `;

        if (!detailUserMarker) {
            const userIcon = L.divIcon({
                className: 'custom-user-gps-wrapper',
                html: `
                    <div class="relative flex items-center justify-center cursor-grab active:cursor-grabbing">
                        <span class="animate-ping absolute inline-flex h-8 w-8 rounded-full bg-blue-400 opacity-75"></span>
                        <div class="relative w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs shadow-lg border-2 border-white font-bold hover:scale-110 transition-transform">
                            <i class="fa-solid fa-person-walking text-xs"></i>
                        </div>
                    </div>
                `,
                iconSize: [32, 32],
                iconAnchor: [16, 16],
                popupAnchor: [0, -16]
            });

            detailUserMarker = L.marker([userLat, userLng], {
                icon: userIcon,
                draggable: true
            }).addTo(detailMap);

            detailUserMarker.bindPopup(popupHtml);

            // Dragend event: recalculate route immediately when user drags the marker!
            detailUserMarker.on('dragend', function (e) {
                const newPos = e.target.getLatLng();
                currentStartLat = newPos.lat;
                currentStartLng = newPos.lng;
                currentStartName = 'شوێنی دەستنیشانکراو';
                fetchDetailRoute(newPos.lat, newPos.lng);

                const btnText = document.getElementById('btnDetailNavText');
                if (btnText) {
                    btnText.innerHTML = '<i class="fa-solid fa-location-dot text-blue-400 ml-1"></i> ڕێگە لە شوێنی دیاریکراوەوە';
                }
            });
        } else {
            detailUserMarker.setLatLng([userLat, userLng]);
            detailUserMarker.setPopupContent(popupHtml);
        }
    }

    function fetchDetailRoute(userLat, userLng) {
        const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${userLng},${userLat};${PROP_LNG},${PROP_LAT}?overview=full&geometries=geojson`;

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
                    drawDetailRoute(latLngs, distanceKm, durationMin, userLat, userLng);
                } else {
                    fallbackDirectDetailRoute(userLat, userLng);
                }
            })
            .catch(() => {
                clearTimeout(timeoutId);
                fallbackDirectDetailRoute(userLat, userLng);
            });
    }

    function drawDetailRoute(latLngs, distanceKm, durationMin, userLat, userLng) {
        if (!detailMap) return;

        if (detailRouteLayer) { detailMap.removeLayer(detailRouteLayer); detailRouteLayer = null; }
        if (detailRouteCasingLayer) { detailMap.removeLayer(detailRouteCasingLayer); detailRouteCasingLayer = null; }

        detailRouteCasingLayer = L.polyline(latLngs, {
            color: '#1d4ed8',
            weight: 7,
            opacity: 0.8,
            lineCap: 'round',
            lineJoin: 'round'
        }).addTo(detailMap);

        detailRouteLayer = L.polyline(latLngs, {
            color: '#38bdf8',
            weight: 4,
            opacity: 0.95,
            lineCap: 'round',
            lineJoin: 'round',
            dashArray: '8, 8'
        }).addTo(detailMap);

        detailMap.fitBounds(detailRouteLayer.getBounds(), { padding: [50, 50] });

        const hud = document.getElementById('detailNavigationHUD');
        const distBadge = document.getElementById('detailRouteDistanceBadge');
        const gmapsBtn = document.getElementById('detailGoogleMapsBtn');

        if (distBadge) distBadge.innerText = `🚗 ~${durationMin} خولەک (${distanceKm} کم)`;
        if (gmapsBtn) {
            gmapsBtn.href = `https://www.google.com/maps/dir/?api=1&origin=${userLat},${userLng}&destination=${PROP_LAT},${PROP_LNG}&travelmode=driving`;
        }
        if (hud) hud.classList.remove('hidden');
    }

    function fallbackDirectDetailRoute(startLat, startLng) {
        const dLat = (PROP_LAT - startLat) * 111;
        const dLng = (PROP_LNG - startLng) * 90.3;
        const distKm = Math.sqrt(dLat*dLat + dLng*dLng).toFixed(1);
        const estMin = Math.max(1, Math.round(distKm * 2.2));
        drawDetailRoute([[startLat, startLng], [PROP_LAT, PROP_LNG]], distKm, estMin, startLat, startLng);
    }

    function clearDetailRoute() {
        if (detailWatchId !== null) {
            navigator.geolocation.clearWatch(detailWatchId);
            detailWatchId = null;
        }
        lastTrackedCoords = null;
        lastRouteFetchTime = 0;

        if (detailMap) {
            if (detailRouteLayer) { detailMap.removeLayer(detailRouteLayer); detailRouteLayer = null; }
            if (detailRouteCasingLayer) { detailMap.removeLayer(detailRouteCasingLayer); detailRouteCasingLayer = null; }
            if (detailUserMarker) { detailMap.removeLayer(detailUserMarker); detailUserMarker = null; }
        }
        const hud = document.getElementById('detailNavigationHUD');
        if (hud) hud.classList.add('hidden');

        const btnText = document.getElementById('btnDetailNavText');
        if (btnText) btnText.innerHTML = 'ڕێگە نیشاندان لە شوێنی تۆوە';
    }
</script>
@endpush
