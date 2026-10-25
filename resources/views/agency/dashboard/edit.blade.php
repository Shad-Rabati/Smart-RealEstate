@extends('layouts.app')

@section('title', 'دەستکاریکردنی خانوو: ' . $property->title . ' | پانێڵی نووسینگە')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900">دەستکاریکردنی زانیارییەکانی خانوو</h1>
            <p class="text-xs text-slate-700">{{ $property->title }}</p>
        </div>

        <a href="{{ route('agency.dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
            ← گەڕانەوە بۆ پانێڵ
        </a>
    </div>

    <form action="{{ route('agency.properties.update', $property->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Section 1: Basic Information -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 space-y-5">
            <h2 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-file-pen text-emerald-600"></i> زانیارییە سەرەکییەکان
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">ناونیشانی خانوو / بابەت *</label>
                    <input type="text" name="title" value="{{ old('title', $property->title) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 text-sm outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">جۆری موڵک *</label>
                    <select name="property_type" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm outline-none bg-white font-semibold">
                        <option value="house" {{ old('property_type', $property->property_type) == 'house' ? 'selected' : '' }}>خانوو</option>
                        <option value="apartment" {{ old('property_type', $property->property_type) == 'apartment' ? 'selected' : '' }}>شوقە</option>
                        <option value="villa" {{ old('property_type', $property->property_type) == 'villa' ? 'selected' : '' }}>ڤێلا</option>
                        <option value="floor" {{ old('property_type', $property->property_type) == 'floor' ? 'selected' : '' }}>نهۆم</option>
                        <option value="commercial" {{ old('property_type', $property->property_type) == 'commercial' ? 'selected' : '' }}>شوێنی بازرگانی / دوکان</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">دۆخی موڵک لەسەر نەخشە *</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm outline-none bg-white font-bold">
                        <option value="available" {{ old('status', $property->status) == 'available' ? 'selected' : '' }} class="text-emerald-600">🟢 سەوز: بەردەستە (بەکرێ نەگیراوە)</option>
                        <option value="reserved" {{ old('status', $property->status) == 'reserved' ? 'selected' : '' }} class="text-slate-600">⚪ رەساسی: لە دانوستان / گێراندا</option>
                        <option value="rented" {{ old('status', $property->status) == 'rented' ? 'selected' : '' }} class="text-rose-600">🔴 سور: بەکرێ گیراوە</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">نرخی کرێ *</label>
                    <div class="flex gap-2">
                        <input type="number" step="any" name="rent_price" value="{{ old('rent_price', $property->rent_price) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm outline-none">
                        <select name="price_currency" class="px-3 rounded-xl border border-slate-300 text-sm bg-white font-bold">
                            <option value="USD" {{ old('price_currency', $property->price_currency) == 'USD' ? 'selected' : '' }}>دۆلار ($)</option>
                            <option value="IQD" {{ old('price_currency', $property->price_currency) == 'IQD' ? 'selected' : '' }}>دینار (د.ع)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ماوەی کرێ</label>
                    <select name="rent_period" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white">
                        <option value="monthly" {{ old('rent_period', $property->rent_period) == 'monthly' ? 'selected' : '' }}>مانگانە</option>
                        <option value="yearly" {{ old('rent_period', $property->rent_period) == 'yearly' ? 'selected' : '' }}>ساڵانە</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ژووری خەوتن *</label>
                    <input type="number" name="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" min="0" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">حەمام و دەستشۆر *</label>
                    <input type="number" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" min="0" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ڕووبەر (مەتر دووجا) *</label>
                    <input type="number" name="area_sqm" value="{{ old('area_sqm', $property->area_sqm) }}" min="10" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">نهۆم</label>
                    <input type="number" name="floor_number" value="{{ old('floor_number', $property->floor_number) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 border-t border-slate-100 text-xs font-bold">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_furnished" value="1" {{ old('is_furnished', $property->is_furnished) ? 'checked' : '' }} class="rounded text-emerald-600">
                    <span>مەفروش (بە کەلوپەل)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="has_parking" value="1" {{ old('has_parking', $property->has_parking) ? 'checked' : '' }} class="rounded text-emerald-600">
                    <span>گەراج / پارکینگ</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="has_elevator" value="1" {{ old('has_elevator', $property->has_elevator) ? 'checked' : '' }} class="rounded text-emerald-600">
                    <span>ئەسانسۆر</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="has_generator" value="1" {{ old('has_generator', $property->has_generator) ? 'checked' : '' }} class="rounded text-emerald-600">
                    <span>موەلیدەی بەردەوام</span>
                </label>
            </div>
        </div>

        <!-- Section 2: Interactive Location & Map Picker -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-emerald-600"></i> شوێن لەسەر نەخشەی سلێمانی
                </h2>
                <span class="text-xs text-slate-500">کلیک لەسەر نەخشەکە بکە بۆ گۆڕینی شوێن</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">گەڕەکی سلێمانی *</label>
                    <select name="neighborhood" id="neighborhoodSelect" onchange="panToNeighborhood(this.value)" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm outline-none bg-white">
                        @foreach($neighborhoods as $n)
                            <option value="{{ $n }}" {{ old('neighborhood', $property->neighborhood) == $n ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">کۆڵان / ناونیشان</label>
                    <input type="text" name="street_address" value="{{ old('street_address', $property->street_address) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm outline-none">
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between text-xs text-slate-700">
                    <span>خاڵی سەر نەخشەکە دیاریبکە:</span>
                    <div class="flex items-center gap-2 font-mono text-[11px] bg-slate-100 px-2 py-0.5 rounded">
                        <span>Lat: <span id="displayLat">{{ $property->latitude }}</span></span>
                        <span>Lng: <span id="displayLng">{{ $property->longitude }}</span></span>
                    </div>
                </div>

                <div id="pickerMap" class="w-full h-80 rounded-2xl border border-slate-200 overflow-hidden"></div>

                <input type="hidden" name="latitude" id="latitudeInput" value="{{ old('latitude', $property->latitude) }}" required>
                <input type="hidden" name="longitude" id="longitudeInput" value="{{ old('longitude', $property->longitude) }}" required>
            </div>
        </div>

        <!-- Section 3: Photos & Description -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 space-y-4">
            <h2 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-camera text-emerald-600"></i> وێنە و وەسف
            </h2>

            <div class="flex items-center gap-4 mb-2">
                <img src="{{ $property->primary_image_url }}" alt="Current Photo" class="w-20 h-20 rounded-2xl object-cover border border-slate-200">
                <div class="text-xs text-slate-700">
                    وێنەی ئێستا. بۆ گۆڕینی، فایلی نوێ هەڵبژێرە یان بەستەری نوێ بنووسە.
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">گۆڕینی وێنە (File Upload)</label>
                    <input type="file" name="primary_image" accept="image/*" class="w-full text-xs text-slate-600 file:mr-0 file:ml-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">یان بەستەری وێنەی نوێ (URL)</label>
                    <input type="url" name="primary_image_url" value="{{ old('primary_image_url') }}" placeholder="https://example.com/photo.jpg" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">وەسف و وردەکاری</label>
                <textarea name="description" rows="4" class="w-full p-4 rounded-xl border border-slate-300 text-sm outline-none">{{ old('description', $property->description) }}</textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('agency.dashboard') }}" class="px-6 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm transition-colors">
                پاشگەزبوونەوە
            </a>
            <button type="submit" class="px-8 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md transition-all">
                پاشەکەوتکردنی گۆڕانکارییەکان
            </button>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
    const neighborhoodCoords = {
        'سەرچنار': [35.5685, 45.3950],
        'تووی مەلیک': [35.5640, 45.4410],
        'بەختیاری': [35.5510, 45.4120],
        'گۆیژە': [35.5780, 45.4520],
        'ڕاپەڕین': [35.5820, 45.3620],
        'تاسڵوجە': [35.6020, 45.2850],
        'زەرگەتە': [35.5420, 45.4220],
        'چوارچرا': [35.5720, 45.4260],
        'ئیبراهیم ئەحمەد': [35.5350, 45.4450],
        'شۆڕش': [35.5560, 45.4590],
        'ڕزگاری': [35.5460, 45.4480],
    };

    let pickerMap;
    let pickerMarker;

    document.addEventListener('DOMContentLoaded', function () {
        const initialLat = parseFloat(document.getElementById('latitudeInput').value) || {{ $property->latitude }};
        const initialLng = parseFloat(document.getElementById('longitudeInput').value) || {{ $property->longitude }};

        pickerMap = L.map('pickerMap', {
            center: [initialLat, initialLng],
            zoom: 14
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(pickerMap);

        const customPin = L.divIcon({
            className: 'custom-pin-wrapper',
            html: `<div class="custom-map-pin pin-available"><i class="fa-solid fa-house-chimney"></i></div>`,
            iconSize: [36, 36],
            iconAnchor: [18, 18]
        });

        pickerMarker = L.marker([initialLat, initialLng], {
            icon: customPin,
            draggable: true
        }).addTo(pickerMap);

        pickerMarker.on('dragend', function (e) {
            const pos = e.target.getLatLng();
            updateLocation(pos.lat, pos.lng);
        });

        pickerMap.on('click', function (e) {
            pickerMarker.setLatLng(e.latlng);
            updateLocation(e.latlng.lat, e.latlng.lng);
        });
    });

    function updateLocation(lat, lng) {
        document.getElementById('latitudeInput').value = lat.toFixed(7);
        document.getElementById('longitudeInput').value = lng.toFixed(7);
        document.getElementById('displayLat').innerText = lat.toFixed(5);
        document.getElementById('displayLng').innerText = lng.toFixed(5);
    }

    function panToNeighborhood(name) {
        if (neighborhoodCoords[name]) {
            const coords = neighborhoodCoords[name];
            pickerMap.setView(coords, 15);
            pickerMarker.setLatLng(coords);
            updateLocation(coords[0], coords[1]);
        }
    }
</script>
@endpush
