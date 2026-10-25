@extends('layouts.app')

@section('title', 'زیادکردنی خانووی نوێ | پانێڵی نووسینگە')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900">زیادکردنی خانووی نوێ بۆ کرێ</h1>
            <p class="text-xs text-slate-700">تکایە زانیاری و خاڵی سەر نەخشەی سلێمانی بە دروستی دیاریبکە</p>
        </div>

        <a href="{{ route('agency.dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
            ← گەڕانەوە بۆ پانێڵ
        </a>
    </div>

    <!-- Credit Info Notice -->
    <div class="bg-gradient-to-r from-amber-500/15 via-amber-500/5 to-white border border-amber-500/30 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center text-lg font-bold shadow-sm flex-shrink-0">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div>
                <span class="text-xs font-black text-amber-950 block">تێچووی دانانی ئەم خانووە: {{ $requiredCredits }} کریدت</span>
                <span class="text-[11px] text-slate-600">باڵانسی ئێستات: <strong class="font-mono text-slate-900">{{ $agency->credits }} کریدت</strong> (دوای دانانی ئەم خانووە دەبێت بە: <strong class="font-mono text-emerald-700">{{ max(0, $agency->credits - $requiredCredits) }} کریدت</strong>)</span>
            </div>
        </div>
        <a href="{{ route('agency.credits') }}" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-white border border-amber-300 hover:bg-amber-50 text-amber-800 text-xs font-bold transition-all shadow-sm flex-shrink-0 text-center">
            + کڕینی کریدتی زیاتر
        </a>
    </div>

    <form action="{{ route('agency.properties.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Section 1: Basic Information -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 space-y-5">
            <h2 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-file-pen text-emerald-600"></i> زانیارییە سەرەکییەکان
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">ناونیشانی خانوو / بابەت *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="بۆ نموونە: خانووی دوو نهۆمی مۆدێرن لە سەرچنار" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">جۆری موڵک *</label>
                    <select name="property_type" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 text-sm outline-none bg-white font-semibold">
                        <option value="house">خانوو</option>
                        <option value="apartment">شوقە</option>
                        <option value="villa">ڤێلا</option>
                        <option value="floor">نهۆم</option>
                        <option value="commercial">شوێنی بازرگانی / دوکان</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">دۆخی سەرەتایی لەسەر نەخشە *</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 text-sm outline-none bg-white font-bold">
                        <option value="available" class="text-emerald-600">🟢 سەوز: بەردەستە (بەکرێ نەگیراوە)</option>
                        <option value="reserved" class="text-slate-600">⚪ رەساسی: لە دانوستان / گێراندا</option>
                        <option value="rented" class="text-rose-600">🔴 سور: بەکرێ گیراوە</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">نرخی کرێ *</label>
                    <div class="flex gap-2">
                        <input type="number" step="any" name="rent_price" value="{{ old('rent_price', 400) }}" required placeholder="نرخ" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 text-sm outline-none">
                        <select name="price_currency" class="px-3 rounded-xl border border-slate-300 text-sm bg-white font-bold">
                            <option value="USD">دۆلار ($)</option>
                            <option value="IQD">دینار (د.ع)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ماوەی کرێ</label>
                    <select name="rent_period" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white">
                        <option value="monthly">مانگانە</option>
                        <option value="yearly">ساڵانە</option>
                    </select>
                </div>
            </div>

            <!-- Specs: Bedrooms, Bathrooms, Area -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ژووری خەوتن *</label>
                    <input type="number" name="bedrooms" value="{{ old('bedrooms', 3) }}" min="0" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">حەمام و دەستشۆر *</label>
                    <input type="number" name="bathrooms" value="{{ old('bathrooms', 2) }}" min="0" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ڕووبەر (مەتر دووجا) *</label>
                    <input type="number" name="area_sqm" value="{{ old('area_sqm', 150) }}" min="10" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">نهۆم (ئەگەر شوقەیە)</label>
                    <input type="number" name="floor_number" value="{{ old('floor_number') }}" placeholder="بۆ نموونە: ٢" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm outline-none">
                </div>
            </div>

            <!-- Checkbox amenities -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 border-t border-slate-100 text-xs font-bold">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_furnished" value="1" class="rounded text-emerald-600">
                    <span>مەفروش (بە کەلوپەل)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="has_parking" value="1" checked class="rounded text-emerald-600">
                    <span>گەراج / پارکینگ</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="has_elevator" value="1" class="rounded text-emerald-600">
                    <span>ئەسانسۆر</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="has_generator" value="1" checked class="rounded text-emerald-600">
                    <span>موەلیدەی بەردەوام</span>
                </label>
            </div>
        </div>

        <!-- Section 2: Interactive Location & Map Picker -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-emerald-600"></i> شوێن و دیاریکردن لەسەر نەخشەی سلێمانی *
                </h2>
                <span class="text-xs text-slate-500">کلیک لەسەر نەخشەکە بکە یان نیشانەکە ڕابکێشە</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">گەڕەکی سلێمانی *</label>
                    <select name="neighborhood" id="neighborhoodSelect" onchange="panToNeighborhood(this.value)" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 text-sm outline-none bg-white">
                        <option value="">هەڵبژاردنی گەڕەک</option>
                        @foreach($neighborhoods as $n)
                            <option value="{{ $n }}" {{ old('neighborhood') == $n ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">کۆڵان / ناونیشانی وردتر</label>
                    <input type="text" name="street_address" value="{{ old('street_address') }}" placeholder="بۆ نموونە: کۆڵانی ٨، نزیک مزگەوت" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm outline-none">
                </div>
            </div>

            <!-- Leaflet Map Picker -->
            <div class="space-y-2">
                <div class="flex items-center justify-between text-xs text-slate-700">
                    <span>خاڵەکە ڕابکێشە یان کلیک لەسەر شەقام/کۆڵانەکە بکە:</span>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-mono bg-slate-100 px-2 py-0.5 rounded">Lat: <span id="displayLat">35.5558</span></span>
                        <span class="text-[11px] font-mono bg-slate-100 px-2 py-0.5 rounded">Lng: <span id="displayLng">45.4351</span></span>
                    </div>
                </div>

                <div id="pickerMap" class="w-full h-80 rounded-2xl border border-slate-200 overflow-hidden"></div>

                <!-- Hidden Lat/Lng Inputs -->
                <input type="hidden" name="latitude" id="latitudeInput" value="{{ old('latitude', '35.5558000') }}" required>
                <input type="hidden" name="longitude" id="longitudeInput" value="{{ old('longitude', '45.4351000') }}" required>
            </div>
        </div>

        <!-- Section 3: Photos & Description -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 space-y-4">
            <h2 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-camera text-emerald-600"></i> وێنە و وەسف
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">بارکردنی وێنەی سەرەکی (File Upload)</label>
                    <input type="file" name="primary_image" accept="image/*" class="w-full text-xs text-slate-600 file:mr-0 file:ml-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">یان بەستەری وێنە (Image URL)</label>
                    <input type="url" name="primary_image_url" value="{{ old('primary_image_url') }}" placeholder="https://example.com/image.jpg" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs outline-none">
                </div>
            </div>

            <!-- Video Tour / Reels Field -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-play text-rose-500"></i>
                        <span>لینکی ڤیدیۆ یان ڕیڵزی خانوو (Video Tour / Reels)</span>
                        <span class="text-[10px] font-normal text-slate-400">(ئارەزوومەندانە)</span>
                    </span>
                    <span class="text-[10px] text-slate-500 font-medium">YouTube / Shorts / TikTok / Reels</span>
                </label>
                <div class="relative">
                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        <i class="fa-solid fa-link text-xs"></i>
                    </span>
                    <input type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=... یان https://youtube.com/shorts/..." class="w-full pr-9 pl-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition-all font-mono" dir="ltr">
                </div>
                <p class="text-[11px] text-slate-500 mt-1">ئەگەر لینکی ڤیدیۆی خانوو دابنێیت، نیشانەی تایبەتی «ڤیدیۆ» لەسەر کارتەکەی دەردەکەوێت و کڕیار دەتوانێت ڕاستەوخۆ سەیری بکات.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">وەسف و تایبەتمەندی زیاتر</label>
                <textarea name="description" rows="4" placeholder="وەسفی خانوو، کەشوهەوا، مەتبەخ، کاشی، هتد بنووسە..." class="w-full p-4 rounded-xl border border-slate-300 text-sm outline-none">{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('agency.dashboard') }}" class="px-6 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm transition-colors">
                پاشگەزبوونەوە
            </a>
            <button type="submit" class="px-8 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md transition-all">
                بڵاوکردنەوەی خانوو لەسەر نەخشە
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
        const initialLat = parseFloat(document.getElementById('latitudeInput').value) || 35.5558;
        const initialLng = parseFloat(document.getElementById('longitudeInput').value) || 45.4351;

        pickerMap = L.map('pickerMap', {
            center: [initialLat, initialLng],
            zoom: 13
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
