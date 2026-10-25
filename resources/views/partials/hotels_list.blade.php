<div class="flex items-center justify-between pb-1">
    <div class="flex items-center gap-2">
        <h2 class="text-base font-black text-slate-900">ئوتێلەکانی بەردەست</h2>
        <span class="text-xs bg-amber-100 text-amber-900 px-2.5 py-0.5 rounded-full font-bold">
            {{ $hotels->total() }} ئوتێل
        </span>
    </div>
    <span id="hotelsFilterSpinner" class="hidden text-amber-600 text-xs animate-spin">
        <i class="fa-solid fa-circle-notch"></i>
    </span>
</div>

@if($hotels->isEmpty())
    <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 space-y-3">
        <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-3xl mx-auto">
            <i class="fa-solid fa-hotel"></i>
        </div>
        <h3 class="text-base font-bold text-slate-800">هیچ ئوتێلێک بەپێی ئەم فلتەرە نەدۆزرایەوە</h3>
        <p class="text-xs text-slate-500">تکایە دەستکاری فلتەرەکانت بکە یان سڕینەوەی فلتەر دابگرە.</p>
        <button type="button" onclick="clearHotelsFiltersAjax()" class="inline-block px-5 py-2.5 rounded-xl bg-amber-600 text-white text-xs font-bold hover:bg-amber-500 transition-colors">
            سڕینەوەی هەموو فلتەرەکان
        </button>
    </div>
@else
    <div id="hotelsCardsContainer" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($hotels as $hotel)
            <div id="hotelCard_{{ $hotel->id }}" class="bg-white rounded-3xl overflow-hidden border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group" data-hotel-id="{{ $hotel->id }}" data-lat="{{ $hotel->latitude }}" data-lng="{{ $hotel->longitude }}">
                
                <!-- Hotel Image & Badges -->
                <div class="relative h-48 overflow-hidden bg-slate-100">
                    <img src="{{ $hotel->primary_image_url }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent pointer-events-none"></div>

                    <!-- Stars Badge -->
                    <div class="absolute top-3 right-3 bg-slate-900/85 backdrop-blur-md px-2.5 py-1 rounded-xl text-amber-400 font-black text-xs flex items-center gap-1 shadow-md border border-white/10">
                        <span>{{ str_repeat('★', $hotel->stars) }}</span>
                        <span class="text-[10px] text-white/90 mr-0.5">{{ $hotel->stars }} ئەستێرە</span>
                    </div>

                    <!-- Featured Badge -->
                    @if($hotel->is_featured)
                        <div class="absolute top-3 left-3 bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-black text-[10px] px-2.5 py-1 rounded-xl shadow-md">
                            تایبەت
                        </div>
                    @endif

                    <!-- Price per night pill -->
                    <div class="absolute bottom-3 left-3 bg-emerald-600/95 backdrop-blur-sm text-white px-3 py-1 rounded-xl font-black text-xs shadow-md border border-white/20">
                        ${{ number_format($hotel->price_per_night) }} <span class="text-[10px] font-normal text-emerald-100">/ شەو</span>
                    </div>

                    <!-- Quick Focus on Map icon -->
                    <button type="button" onclick="focusHotelOnMap({{ $hotel->id }}, {{ $hotel->latitude }}, {{ $hotel->longitude }})" class="absolute bottom-3 right-3 w-8 h-8 rounded-xl bg-slate-900/80 hover:bg-amber-600 text-white flex items-center justify-center text-xs backdrop-blur-md transition-all shadow-md" title="پیشاندان لەسەر نەخشە">
                        <i class="fa-solid fa-location-dot"></i>
                    </button>
                </div>

                <!-- Hotel Info -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-3.5">
                    <div>
                        <div class="flex items-center justify-between gap-1 text-xs text-slate-500 mb-1 font-medium">
                            <div class="flex items-center gap-1 truncate">
                                <i class="fa-solid fa-location-dot text-amber-500"></i>
                                <span class="truncate">سلێمانی، {{ $hotel->neighborhood }}</span>
                            </div>

                            @if(isset($hotel->distance_km))
                                <span class="shrink-0 inline-flex items-center gap-1 text-[10.5px] font-bold px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-200">
                                    <i class="fa-solid fa-route text-blue-500 text-[9px]"></i>
                                    <span>{{ $hotel->distance_km < 1 ? round($hotel->distance_km * 1000) . ' م' : $hotel->distance_km . ' کم' }}</span>
                                </span>
                            @endif
                        </div>

                        <h3 class="font-black text-slate-900 text-base group-hover:text-amber-600 transition-colors line-clamp-1">
                            <a href="{{ route('hotels.show', $hotel->slug) }}">
                                {{ $hotel->name }}
                            </a>
                        </h3>

                        @if($hotel->description)
                            <p class="text-xs text-slate-600 line-clamp-2 mt-2 leading-relaxed">
                                {{ $hotel->description }}
                            </p>
                        @endif

                        <!-- Amenities icons -->
                        @if(!empty($hotel->amenities) && is_array($hotel->amenities))
                            <div class="flex flex-wrap items-center gap-1.5 mt-3 pt-3 border-t border-slate-100">
                                @foreach(array_slice($hotel->amenities, 0, 4) as $amKey)
                                    @if(isset($amenityDefinitions[$amKey]))
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-slate-50 text-slate-700 border border-slate-200/80 px-2 py-0.5 rounded-lg">
                                            <i class="fa-solid {{ $amenityDefinitions[$amKey]['icon'] }} text-amber-500 text-[9px]"></i>
                                            <span>{{ $amenityDefinitions[$amKey]['label'] }}</span>
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons (Route, View, WhatsApp, Phone) -->
                    <div class="pt-3 border-t border-slate-100 flex items-center gap-2">
                        <!-- Live Route Navigation Button -->
                        <button type="button" onclick="showRouteToHotel({{ $hotel->latitude }}, {{ $hotel->longitude }}, '{{ addslashes($hotel->name) }}', '{{ addslashes($hotel->neighborhood) }}')" class="px-3 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs transition-all shadow-sm flex items-center gap-1.5 hover:scale-105 active:scale-95 cursor-pointer" title="ڕێ نیشاندەر بەسەر نەخشەدا تا ئەم ئوتێلە">
                            <i class="fa-solid fa-diamond-turn-right text-blue-200 text-xs"></i>
                            <span>ڕێگە</span>
                        </button>

                        <!-- View Details Button -->
                        <a href="{{ route('hotels.show', $hotel->slug) }}" class="flex-1 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs text-center transition-colors">
                            بینینی ئوتێل
                        </a>

                        @if($hotel->clean_whatsapp)
                            <a href="https://wa.me/{{ $hotel->clean_whatsapp }}?text={{ urlencode('سڵاو، دەمەوێت لە ئوتێلی ' . $hotel->name . ' حجز بکەم.') }}" target="_blank" class="w-10 h-10 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition-colors" title="واتسئەپی ئوتێل">
                                <i class="fa-brands fa-whatsapp text-base"></i>
                            </a>
                        @endif

                        <a href="tel:{{ $hotel->phone }}" class="w-10 h-10 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-colors" title="پەیوەندی تەلەفۆنی">
                            <i class="fa-solid fa-phone text-xs"></i>
                        </a>
                    </div>

                </div>

            </div>
        @endforeach
    </div>

    <div class="pt-4 ajax-hotels-pagination">
        {{ $hotels->links() }}
    </div>
@endif
