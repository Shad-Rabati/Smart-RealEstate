@extends('layouts.admin')

@section('title', 'زیادکردنی ئوتێلی نوێ | پانێڵی سەرپەرشتیار')
@section('page_title', 'زیادکردنی ئوتێلی نوێ')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css"/>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-slate-900">فۆرمی زیادکردنی ئوتێل</h1>
            <p class="text-xs text-slate-500">زانیارییە سەرەکییەکان و وێنەی ئوتێلەکە بنووسە بۆ بڵاوکردنەوە لە بەشی ئوتێلەکان.</p>
        </div>
        <a href="{{ route('admin.hotels.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
            ← گەڕانەوە بۆ لیست
        </a>
    </div>

    <form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Section 1: Basic Information -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 space-y-4">
            <h2 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-hotel text-amber-500"></i> زانیاری سەرەکی
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">ناوی تەواوی ئوتێل *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="بۆ نموونە: ئوتێل گراند میلینیۆم" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-amber-500 text-xs outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">پلەی ئوتێل (ئەستێرە) *</label>
                    <select name="stars" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs outline-none bg-white">
                        <option value="5" {{ old('stars') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ ٥ ئەستێرە</option>
                        <option value="4" {{ old('stars') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ ٤ ئەستێرە</option>
                        <option value="3" {{ old('stars', '3') == '3' ? 'selected' : '' }}>⭐⭐⭐ ٣ ئەستێرە</option>
                        <option value="2" {{ old('stars') == '2' ? 'selected' : '' }}>⭐⭐ ٢ ئەستێرە</option>
                        <option value="1" {{ old('stars') == '1' ? 'selected' : '' }}>⭐ ١ ئەستێرە</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">نرخی دەستپێک بۆ شەوێک ($) *</label>
                    <div class="flex items-center gap-2">
                        <input type="number" step="0.01" name="price_per_night" value="{{ old('price_per_night') }}" required placeholder="بۆ نموونە: 80" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs outline-none">
                        <input type="hidden" name="price_currency" value="USD">
                        <span class="px-3 py-2.5 bg-slate-100 rounded-xl text-xs font-bold text-slate-600">USD $</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Contact & Links -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 space-y-4">
            <h2 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-address-book text-amber-500"></i> پەیوەندی و بەستەرەکان
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ژمارەی تەلەفۆنی ڕیسێپشن *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="0770 123 4567" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ژمارەی واتسئەپ (بۆ حجز)</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="0770 123 4567" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ئیمەیڵ (ئارەزوومەندانە)</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="info@hotel.com" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ماڵپەڕی فەرمی (ئارەزوومەندانە)</label>
                    <input type="url" name="website" value="{{ old('website') }}" placeholder="https://hotel.com" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs outline-none">
                </div>
            </div>
        </div>

        <!-- Section 3: Location on Map -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 space-y-4">
            <h2 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-map-location-dot text-amber-500"></i> شوێن و ناونیشان لە سلێمانی
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">گەڕەک / ناوچە *</label>
                    <select name="neighborhood" id="neighborhoodSelect" onchange="panToNeighborhood(this.value)" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs outline-none bg-white">
                        <option value="">هەڵبژاردنی گەڕەک</option>
                        @foreach($neighborhoods as $n)
                            <option value="{{ $n }}" {{ old('neighborhood') == $n ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ناونیشانی وردتر (شەقام، کۆڵان)</label>
                    <input type="text" name="address" value="{{ old('address') }}" placeholder="بۆ نموونە: شەقامی سالم، نزیک باخی گشتی" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs outline-none">
                </div>
            </div>

            <!-- Leaflet Picker Map -->
            <div class="space-y-2 pt-2">
                <div class="flex items-center justify-between text-xs text-slate-600">
                    <span>کلیک لەسەر شوێنی ئوتێلەکە بکە لەسەر نەخشەکە:</span>
                    <span class="font-mono text-[11px] bg-slate-100 px-2 py-0.5 rounded">
                        Lat: <span id="displayLat">35.5558</span>, Lng: <span id="displayLng">45.4351</span>
                    </span>
                </div>
                <div id="pickerMap" class="w-full h-72 rounded-2xl border border-slate-200 overflow-hidden shadow-inner" style="min-height: 288px; z-index: 1;"></div>
                <input type="hidden" name="latitude" id="latitudeInput" value="{{ old('latitude', '35.5558000') }}" required>
                <input type="hidden" name="longitude" id="longitudeInput" value="{{ old('longitude', '45.4351000') }}" required>
            </div>
        </div>

        <!-- Section 4: Image & Amenities -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 space-y-4">
            <h2 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-camera text-amber-500"></i> وێنە و خزمەتگوزارییەکان
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">بارکردنی وێنەی سەرەکی (File)</label>
                    <input type="file" name="primary_image" accept="image/*" class="w-full text-xs text-slate-600 file:mr-0 file:ml-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">یان بەستەری وێنە (Image URL)</label>
                    <input type="url" name="primary_image_url" value="{{ old('primary_image_url') }}" placeholder="https://example.com/hotel.jpg" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs outline-none">
                </div>
            </div>

            <!-- Amenities Checkboxes -->
            <div class="space-y-2 pt-3 border-t border-slate-100">
                <label class="block text-xs font-bold text-slate-700">خزمەتگوزارییە بەردەستەکان:</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($amenityDefinitions as $key => $amenity)
                        <label class="flex items-center gap-2.5 p-3 rounded-2xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-amber-50/50 transition-colors">
                            <input type="checkbox" name="amenities[]" value="{{ $key }}" {{ is_array(old('amenities')) && in_array($key, old('amenities')) ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500">
                            <i class="fa-solid {{ $amenity['icon'] }} text-amber-600 text-xs"></i>
                            <span class="text-xs font-bold text-slate-700">{{ $amenity['label'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Description -->
            <div class="pt-3 border-t border-slate-100">
                <label class="block text-xs font-bold text-slate-700 mb-1">وەسف و تایبەتمەندییەکان</label>
                <textarea name="description" rows="4" placeholder="دەربارەی ئوتێلەکە، خزمەتگوزارییەکان، جۆری ژوورەکان بنووسە..." class="w-full p-4 rounded-xl border border-slate-300 text-xs outline-none">{{ old('description') }}</textarea>
            </div>

            <!-- Status & Featured -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500">
                    <span>دیاریکردن وەک ئوتێلی تایبەت (Featured)</span>
                </label>

                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-700">دۆخ:</span>
                    <select name="status" class="px-3 py-1.5 rounded-xl border border-slate-300 text-xs bg-white">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>چالاک</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>ناچالاک</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.hotels.index') }}" class="px-6 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                پاشگەزبوونەوە
            </a>
            <button type="submit" class="px-8 py-3 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-md transition-all">
                پاشەکەوتکردن و بڵاوکردنەوە
            </button>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
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

    let pickerMap, pickerMarker;

    document.addEventListener('DOMContentLoaded', function () {
        const initialLat = parseFloat(document.getElementById('latitudeInput').value) || 35.5558;
        const initialLng = parseFloat(document.getElementById('longitudeInput').value) || 45.4351;

        pickerMap = L.map('pickerMap', {
            center: [initialLat, initialLng],
            zoom: 13,
            scrollWheelZoom: false
        });

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(pickerMap);

        setTimeout(() => {
            if (pickerMap) {
                pickerMap.invalidateSize();
            }
        }, 250);

        pickerMarker = L.marker([initialLat, initialLng], { draggable: true }).addTo(pickerMap);

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
        document.getElementById('displayLat').innerText = lat.toFixed(4);
        document.getElementById('displayLng').innerText = lng.toFixed(4);
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
