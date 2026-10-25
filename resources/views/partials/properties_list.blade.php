<div class="flex items-center justify-between pb-3 border-b border-slate-200/80 mb-3">
    <div>
        <h2 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
            <span>خانووەکانی کرێ</span>
            @if(request('neighborhood') && request('neighborhood') !== 'all')
                <span class="text-emerald-600 font-bold text-base">لە {{ request('neighborhood') }}</span>
            @endif
        </h2>
        <p class="text-xs text-slate-500 font-medium mt-0.5">
            پیشاندانی <span id="currentShowingCount" class="font-bold text-slate-700">{{ $properties->count() }}</span> لە کۆی <span id="totalPropertiesCount" class="font-bold text-slate-700">{{ $properties->total() }}</span> خانووی بەردەست
        </p>
    </div>

    <!-- Sort Dropdown -->
    <div class="flex items-center gap-2">
        <span id="sortLoadingSpinner" class="hidden text-emerald-600 text-xs animate-spin">
            <i class="fa-solid fa-circle-notch"></i>
        </span>
        <div class="relative inline-flex items-center">
            <i class="fa-solid fa-arrow-down-short-wide absolute right-3 text-slate-400 text-xs pointer-events-none"></i>
            <select id="sortSelectDropdown" onchange="updateSort(this.value)" class="pr-8 pl-8 py-2 rounded-2xl border border-slate-200 text-xs font-bold bg-white text-slate-700 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition-all cursor-pointer shadow-sm hover:border-slate-300 appearance-none">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>تازەترینەکان</option>
                <option value="nearest" {{ request('sort') == 'nearest' ? 'selected' : '' }}>📍 نزیکترین لە منەوە</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>نزمترین نرخ (هەرزانترین)</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>بەرزترین نرخ (گرانترین)</option>
            </select>
            <i class="fa-solid fa-chevron-down absolute left-3 text-slate-400 text-[10px] pointer-events-none"></i>
        </div>
    </div>
</div>

<!-- Property Cards Grid -->
<div id="propertiesCardsContainer" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
@forelse($properties as $prop)
    <div class="property-card-item bg-white rounded-3xl p-3 shadow-sm hover:shadow-xl border border-slate-200/90 hover:border-slate-300 transition-all duration-300 flex flex-col justify-between group" 
         id="property-card-{{ $prop->id }}"
         data-id="{{ $prop->id }}"
         data-price="{{ $prop->rent_price }}"
         data-neighborhood="{{ $prop->neighborhood }}"
         data-type="{{ $prop->property_type }}"
         data-status="{{ $prop->status }}"
         data-bedrooms="{{ $prop->bedrooms }}"
         data-title="{{ mb_strtolower($prop->title) }}"
         data-created="{{ $prop->created_at ? $prop->created_at->timestamp : 0 }}"
         data-distance="{{ isset($prop->distance_km) ? $prop->distance_km : 999999 }}"
         data-lat="{{ $prop->latitude }}"
         data-lng="{{ $prop->longitude }}">
        
        <div>
            <!-- Card Image -->
            <div class="h-44 w-full relative rounded-2xl overflow-hidden bg-slate-100">
                <a href="{{ route('properties.show', $prop->slug) }}" class="block w-full h-full">
                    <img src="{{ $prop->primary_image_url }}" alt="{{ $prop->title }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </a>

                <!-- Status Glass Badge (Top Right) -->
                <div class="absolute top-2.5 right-2.5 pointer-events-none">
                    @if($prop->status === 'available')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-white/95 text-emerald-800 backdrop-blur-md shadow-md border border-white/60">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>بەردەست</span>
                        </span>
                    @elseif($prop->status === 'reserved')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-white/95 text-slate-700 backdrop-blur-md shadow-md border border-white/60">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                            <span>دانوستان</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-white/95 text-rose-700 backdrop-blur-md shadow-md border border-white/60">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            <span>گیراوە</span>
                        </span>
                    @endif
                </div>

                <!-- Type & Video Badges (Top Left) -->
                <div class="absolute top-2.5 left-2.5 pointer-events-none flex items-center gap-1.5">
                    @if($prop->has_video)
                        <span class="px-2 py-0.5 rounded-xl text-[10px] font-black bg-rose-600 text-white border border-white/20 shadow-md flex items-center gap-1">
                            <i class="fa-solid fa-play text-[8px]"></i> ڤیدیۆ
                        </span>
                    @endif
                    <span class="px-2.5 py-1 rounded-xl text-[10.5px] font-extrabold bg-slate-900/80 backdrop-blur-md text-white border border-white/20 shadow-sm">
                        {{ $prop->property_type_label }}
                    </span>
                </div>
            </div>

            <!-- Card Content -->
            <div class="pt-3 px-1">
                <div class="flex items-center justify-between gap-2">
                    <div class="text-xl font-black text-slate-900 tracking-tight">
                        {{ $prop->formatted_price }}
                    </div>
                    <button type="button" 
                            onclick="toggleFavorite({{ $prop->id }}, '{{ addslashes($prop->title) }}')"
                            data-favorite-id="{{ $prop->id }}"
                            class="w-8 h-8 rounded-full bg-slate-50 hover:bg-rose-50 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-all border border-slate-200/80 active:scale-90"
                            title="پاشەکەوتکردن لە دڵخوازەکان">
                        <i class="fa-regular fa-heart text-xs"></i>
                    </button>
                </div>

                <h3 class="font-extrabold text-slate-900 text-sm group-hover:text-emerald-600 transition-colors line-clamp-1 mt-1 leading-snug">
                    <a href="{{ route('properties.show', $prop->slug) }}">
                        {{ $prop->title }}
                    </a>
                </h3>

                <!-- Key Specs Chips -->
                <div class="flex items-center gap-1.5 text-xs text-slate-600 mt-2.5 flex-wrap">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-slate-50 text-slate-700 font-semibold border border-slate-100">
                        <i class="fa-solid fa-location-dot text-emerald-600 text-[10px]"></i>
                        <span>{{ $prop->neighborhood }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-slate-50 text-slate-700 font-semibold border border-slate-100">
                        <i class="fa-solid fa-bed text-slate-400 text-[10px]"></i>
                        <span>{{ $prop->bedrooms }} ژوور</span>
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-slate-50 text-slate-700 font-semibold border border-slate-100">
                        <i class="fa-solid fa-ruler-combined text-slate-400 text-[10px]"></i>
                        <span>{{ $prop->area_sqm }} م²</span>
                    </span>
                </div>

                <!-- Distance if calculated -->
                @if(isset($prop->distance_km))
                    <div class="mt-2">
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-xl bg-blue-50 text-blue-700 border border-blue-200/60">
                            <i class="fa-solid fa-location-crosshairs text-blue-500 animate-pulse"></i>
                            @if($prop->distance_km < 1)
                                {{ round($prop->distance_km * 1000) }} مەتر لە تۆوە دوورە
                            @else
                                {{ $prop->distance_km }} کم لە تۆوە دوورە
                            @endif
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer: Agency & Actions -->
        <div class="pt-3 px-1 mt-3 border-t border-slate-100 flex items-center justify-between text-xs">
            <a href="{{ route('agencies.show', $prop->agency->slug) }}" class="text-slate-500 hover:text-slate-900 font-semibold truncate max-w-[130px] flex items-center gap-1.5" title="{{ $prop->agency->name }}">
                <i class="fa-solid fa-building text-slate-400 text-[11px]"></i>
                <span class="truncate">{{ $prop->agency->name }}</span>
            </a>

            <div class="flex items-center gap-1.5">
                <button type="button" onclick="showRouteToProperty({{ $prop->latitude }}, {{ $prop->longitude }}, '{{ addslashes($prop->title) }}', '{{ $prop->neighborhood }}')" class="px-2.5 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white transition-all font-bold text-xs flex items-center gap-1 shadow-sm" title="ڕێ نیشاندان">
                    <i class="fa-solid fa-diamond-turn-right text-xs"></i>
                    <span>ڕێگە</span>
                </button>

                <button type="button" onclick="focusPropertyOnMap({{ $prop->id }}, {{ $prop->latitude }}, {{ $prop->longitude }})" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 transition-all font-bold text-xs flex items-center gap-1 shadow-sm" title="پیشاندان لەسەر نەخشە">
                    <i class="fa-solid fa-map-pin text-emerald-600 text-xs"></i>
                    <span>نەخشە</span>
                </button>
            </div>
        </div>

    </div>
@empty
    <div class="sm:col-span-2 bg-white rounded-3xl p-10 text-center border border-slate-200">
        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-slate-400 text-2xl mb-3">
            <i class="fa-solid fa-house-circle-xmark"></i>
        </div>
        <h3 class="text-base font-bold text-slate-800 mb-1">هیچ خانوویەک بەم فلتەرانە نەدۆزرایەوە</h3>
        <p class="text-xs text-slate-500 mb-4">تکایە فلتەرەکان بگۆڕە یان سڕینەوەی فلتەر لێبدە بۆ بینینی هەموو خانووەکانی سلێمانی.</p>
        <button type="button" onclick="clearFiltersAjax()" class="px-4 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs inline-flex items-center gap-2 shadow-md transition-all">
            <i class="fa-solid fa-arrow-rotate-right"></i>
            <span>پیشاندانی هەموو موڵکەکان</span>
        </button>
    </div>
@endforelse
</div>

<!-- Pagination -->
<div class="mt-6 ajax-pagination-container">
    {{ $properties->links() }}
</div>
