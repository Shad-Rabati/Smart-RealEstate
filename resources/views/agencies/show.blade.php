@extends('layouts.app')

@section('title', $agency->name . ' | پلاتفۆرمی خانوبەرەی سلێمانی')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Agency Header Box -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-start sm:items-center gap-5">
                <img src="{{ $agency->logo_url }}" alt="{{ $agency->name }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl object-cover border-2 border-emerald-500 shadow-md">
                
                <div class="space-y-1.5">
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900">{{ $agency->name }}</h1>
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">باوەڕپێکراو</span>
                    </div>

                    @if($agency->owner_name)
                        <div class="text-xs text-slate-700 font-medium">بەڕێوەبەر: {{ $agency->owner_name }}</div>
                    @endif

                    <div class="flex items-center gap-2 text-xs text-slate-700">
                        <i class="fa-solid fa-location-dot text-emerald-600"></i>
                        <span>{{ $agency->address }}</span>
                    </div>
                </div>
            </div>

            <!-- Contact Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                @if($agency->whatsapp_link)
                    <a href="{{ $agency->whatsapp_link }}" target="_blank" class="px-5 py-3 rounded-2xl bg-[#25D366] hover:bg-[#20ba5a] text-white font-bold text-xs sm:text-sm flex items-center gap-2 shadow-sm transition-all">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                        <span>نامە لە واتسئاپ</span>
                    </a>
                @endif

                <a href="tel:{{ $agency->phone }}" class="px-5 py-3 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm flex items-center gap-2 shadow-sm transition-all">
                    <i class="fa-solid fa-phone"></i>
                    <span dir="ltr">{{ $agency->phone }}</span>
                </a>
            </div>
        </div>

        @if($agency->description)
            <div class="mt-6 pt-6 border-t border-slate-100 text-xs sm:text-sm text-slate-700 leading-relaxed">
                {{ $agency->description }}
            </div>
        @endif

        <!-- Agency Property Counters -->
        <div class="mt-6 pt-4 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-4 gap-3 text-center text-xs">
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                <span class="text-slate-700 block font-medium">کۆی موڵکەکان</span>
                <span class="text-lg font-black text-slate-900">{{ $agency->properties_count }}</span>
            </div>
            <div class="bg-emerald-50 p-3 rounded-xl border border-emerald-100">
                <span class="text-emerald-800 block font-medium">🟢 بەردەستە (سەوز)</span>
                <span class="text-lg font-black text-emerald-700">{{ $agency->available_properties_count }}</span>
            </div>
            <div class="bg-slate-100 p-3 rounded-xl border border-slate-200">
                <span class="text-slate-800 block font-medium">⚪ دانوستان (رەساسی)</span>
                <span class="text-lg font-black text-slate-700">{{ $agency->reserved_properties_count }}</span>
            </div>
            <div class="bg-rose-50 p-3 rounded-xl border border-rose-100">
                <span class="text-rose-800 block font-medium">🔴 گیراوە (سور)</span>
                <span class="text-lg font-black text-rose-700">{{ $agency->rented_properties_count }}</span>
            </div>
        </div>
    </div>

    <!-- Agency Properties Filter & List -->
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-lg font-black text-slate-900">
                خانووە تۆمارکراوەکانی ئەم نووسینگەیە
            </h2>

            <div class="flex items-center gap-2 text-xs">
                <a href="{{ route('agencies.show', $agency->slug) }}" class="px-3 py-1.5 rounded-xl {{ !request('status') || request('status') === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }} font-bold">
                    هەمووی ({{ $agency->properties_count }})
                </a>
                <a href="{{ route('agencies.show', [$agency->slug, 'status' => 'available']) }}" class="px-3 py-1.5 rounded-xl {{ request('status') === 'available' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700' }} font-bold">
                    🟢 بەردەستە ({{ $agency->available_properties_count }})
                </a>
                <a href="{{ route('agencies.show', [$agency->slug, 'status' => 'reserved']) }}" class="px-3 py-1.5 rounded-xl {{ request('status') === 'reserved' ? 'bg-slate-700 text-white' : 'bg-slate-100 text-slate-700' }} font-bold">
                    ⚪ لە دانوستاندا ({{ $agency->reserved_properties_count }})
                </a>
                <a href="{{ route('agencies.show', [$agency->slug, 'status' => 'rented']) }}" class="px-3 py-1.5 rounded-xl {{ request('status') === 'rented' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-700' }} font-bold">
                    🔴 گیراوە ({{ $agency->rented_properties_count }})
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($properties as $prop)
                <div class="bg-white rounded-3xl p-4 shadow-sm border border-slate-200 hover:shadow-md transition-all group flex flex-col justify-between">
                    <div>
                        <div class="h-48 rounded-2xl overflow-hidden bg-slate-100 relative mb-3">
                            <img src="{{ $prop->primary_image_url }}" alt="{{ $prop->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            
                            <div class="absolute top-2 right-2">
                                @if($prop->status === 'available')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-emerald-500 text-white shadow-sm">
                                        🟢 سەوز (بەردەست)
                                    </span>
                                @elseif($prop->status === 'reserved')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-slate-600 text-white shadow-sm">
                                        ⚪ رەساسی (دانوستان)
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-rose-600 text-white shadow-sm">
                                        🔴 سور (گیراو)
                                    </span>
                                @endif
                            </div>
                        </div>

                        <h3 class="font-bold text-slate-900 text-sm group-hover:text-emerald-700 line-clamp-1 mb-1">
                            <a href="{{ route('properties.show', $prop->slug) }}">
                                {{ $prop->title }}
                            </a>
                        </h3>

                        <div class="text-emerald-600 font-extrabold text-base mb-2">
                            {{ $prop->formatted_price }}
                        </div>

                        <div class="flex items-center gap-3 text-xs text-slate-700 mb-3">
                            <span><i class="fa-solid fa-location-dot ml-1 text-slate-400"></i> {{ $prop->neighborhood }}</span>
                            <span><i class="fa-solid fa-bed ml-1 text-slate-400"></i> {{ $prop->bedrooms }} ژوور</span>
                            <span><i class="fa-solid fa-ruler-combined ml-1 text-slate-400"></i> {{ $prop->area_sqm }}م²</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-700">{{ $prop->property_type_label }}</span>
                        <a href="{{ route('properties.show', $prop->slug) }}" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white text-xs font-bold transition-colors">
                            وردەکاری خانوو
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-3xl border border-slate-200">
                    <p class="text-sm text-slate-700">هیچ خانوویەک لەم دۆخەدا نییە.</p>
                </div>
            @endforelse
        </div>

        <div>
            {{ $properties->links() }}
        </div>
    </div>

</div>
@endsection
