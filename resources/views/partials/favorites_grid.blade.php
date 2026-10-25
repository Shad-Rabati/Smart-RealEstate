<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($properties as $property)
        <article id="favorite-card-{{ $property->id }}" class="bg-white rounded-3xl overflow-hidden border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group relative">
            
            <!-- Remove from Favorites Quick Button (Top Left) -->
            <div class="absolute top-3 left-3 z-20">
                <button type="button" 
                        onclick="toggleFavorite({{ $property->id }}, '{{ addslashes($property->title) }}')"
                        class="w-9 h-9 rounded-full bg-white/95 backdrop-blur-md text-rose-500 hover:bg-rose-50 flex items-center justify-center shadow-md border border-slate-200 hover:scale-110 active:scale-95 transition-all"
                        title="{{ __('لابردن لە دڵخوازەکان') }}">
                    <i class="fa-solid fa-heart text-base"></i>
                </button>
            </div>

            <!-- Status / Type Badge (Top Right) -->
            <div class="absolute top-3 right-3 z-20 flex items-center gap-1.5">
                @if($property->status === 'available')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-white/95 text-emerald-800 backdrop-blur-md shadow-md border border-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>{{ __('بەردەست') }}</span>
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-white/95 text-slate-700 backdrop-blur-md shadow-md border border-white/60">
                        <span>{{ __($property->status_label) }}</span>
                    </span>
                @endif

                @if($property->has_video)
                    <span class="px-2 py-0.5 rounded-full bg-rose-600 text-white text-[10px] font-black shadow-md flex items-center gap-1">
                        <i class="fa-solid fa-play text-[8px]"></i> {{ __('ڤیدیۆ') }}
                    </span>
                @endif
            </div>

            <!-- Image Cover -->
            <a href="{{ route('properties.show', $property->slug) }}" class="relative aspect-[16/10] overflow-hidden bg-slate-100 block">
                <img src="{{ $property->primary_image_url }}" alt="{{ $property->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>
                <div class="absolute bottom-3 right-3 text-white text-xs font-bold flex items-center gap-1.5 drop-shadow">
                    <i class="fa-solid fa-location-dot text-rose-400"></i>
                    <span>{{ __($property->neighborhood) }}</span>
                </div>
                <div class="absolute bottom-3 left-3 text-white text-xs font-mono font-black px-2.5 py-1 rounded-xl bg-slate-900/80 backdrop-blur-md">
                    {{ $property->formatted_price }}
                </div>
            </a>

            <!-- Content Details -->
            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                <div>
                    <h3 class="font-black text-slate-900 text-base line-clamp-1 group-hover:text-emerald-700 transition-colors">
                        <a href="{{ route('properties.show', $property->slug) }}">
                            {{ __($property->title) }}
                        </a>
                    </h3>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                        {{ __($property->description) ?: __('خانوویەکی مۆدێرن و گونجاو لە گەڕەکی دڵخوازی سلێمانی.') }}
                    </p>
                </div>

                <!-- Specs Row -->
                <div class="grid grid-cols-3 gap-2 py-2 border-y border-slate-150/80 text-center text-xs font-bold text-slate-700">
                    <div class="flex items-center justify-center gap-1.5" title="{{ __('ژووری خەوتن') }}">
                        <i class="fa-solid fa-bed text-emerald-600"></i>
                        <span>{{ $property->bedrooms }} {{ __('خەوتن') }}</span>
                    </div>
                    <div class="flex items-center justify-center gap-1.5 border-x border-slate-200" title="{{ __('گەرماو') }}">
                        <i class="fa-solid fa-bath text-teal-600"></i>
                        <span>{{ $property->bathrooms }} {{ __('گەرماو') }}</span>
                    </div>
                    <div class="flex items-center justify-center gap-1.5" title="{{ __('ڕووبەر') }}">
                        <i class="fa-solid fa-ruler-combined text-blue-600"></i>
                        <span class="font-mono">{{ $property->area_sqm }} {{ __('م²') }}</span>
                    </div>
                </div>

                <!-- Agency & Actions -->
                <div class="flex items-center justify-between pt-1">
                    <div class="flex items-center gap-2">
                        <img src="{{ $property->agency?->logo_url ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=100&q=80' }}" alt="{{ $property->agency?->name }}" class="w-7 h-7 rounded-full object-cover border border-slate-200">
                        <span class="text-xs font-bold text-slate-800 truncate max-w-[120px]">{{ __($property->agency?->name) ?? __('نووسینگە') }}</span>
                    </div>

                    <div class="flex items-center gap-1.5">
                        @if($property->agency?->whatsapp_link)
                            <a href="{{ $property->agency->whatsapp_link }}?text={{ urlencode('سڵاو، دەمەوێت سەبارەت بەم خانوە پرسیار بکەم: ' . route('properties.show', $property->slug)) }}" target="_blank" rel="noopener" class="w-8 h-8 rounded-xl bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-colors shadow-sm" title="{{ __('پەیوەندی لە واتسئەپ') }}">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                            </a>
                        @endif
                        <a href="{{ route('properties.show', $property->slug) }}" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white text-xs font-black transition-colors">
                            {{ __('وردەکاری') }}
                        </a>
                    </div>
                </div>
            </div>
        </article>
    @empty
        <!-- Handled dynamically by JS or server -->
    @endforelse
</div>
