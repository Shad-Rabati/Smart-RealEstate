@extends('layouts.admin')

@section('title', 'بەڕێوەبردنی خانووەکان')
@section('page_title', 'سەرجەم خانووە تۆمارکراوەکان')

@section('content')
<div class="space-y-6">

    <!-- Filters Bar -->
    <div class="bg-slate-900/90 rounded-3xl p-5 border border-slate-800 space-y-4 shadow-xl">
        
        <!-- Filter Tabs by Status -->
        <div id="statusTabsContainer" class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
            <button type="button" onclick="selectStatusFilter('all')"
                class="status-tab-btn px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ !request('status') || request('status') === 'all' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-slate-800 text-slate-400 hover:text-white' }}">
                هەموو ({{ $counts['all'] }})
            </button>
            <button type="button" onclick="selectStatusFilter('available')"
                class="status-tab-btn px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('status') === 'available' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-slate-800 text-slate-400 hover:text-white' }}">
                بەردەست ({{ $counts['available'] }})
            </button>
            <button type="button" onclick="selectStatusFilter('reserved')"
                class="status-tab-btn px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('status') === 'reserved' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-slate-800 text-slate-400 hover:text-white' }}">
                دانوستان ({{ $counts['reserved'] }})
            </button>
            <button type="button" onclick="selectStatusFilter('rented')"
                class="status-tab-btn px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('status') === 'rented' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-slate-800 text-slate-400 hover:text-white' }}">
                گیراوە ({{ $counts['rented'] }})
            </button>
            <button type="button" onclick="selectFeaturedFilter('1')"
                class="status-tab-btn px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('featured') === '1' ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-black shadow-md shadow-amber-500/30' : 'bg-slate-800 text-amber-400 hover:text-white border border-amber-500/20' }}">
                <i class="fa-solid fa-crown text-[10px]"></i> VIP ({{ $counts['featured'] ?? 0 }})
            </button>
        </div>

        <!-- Search & Dropdown Filters (Instant Live Filter) -->
        <form id="filterPropertiesForm" method="GET" action="{{ route('admin.properties.index') }}" onsubmit="event.preventDefault(); applyFiltersInstant();" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <input type="hidden" name="status" id="filterStatusInput" value="{{ request('status', 'all') }}">
            <input type="hidden" name="featured" id="filterFeaturedInput" value="{{ request('featured', 'all') }}">

            <!-- Search Title / Neighborhood -->
            <div class="relative">
                <input type="text" name="q" id="filterSearchInput" value="{{ request('q') }}" placeholder="گەڕان بەپێی ناونیشان..."
                    class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder:text-slate-500 focus:outline-none focus:border-emerald-500 transition-colors">
            </div>

            <!-- Filter by Agency (Instant) -->
            <div>
                <select name="agency_id" id="filterAgencySelect" onchange="applyFiltersInstant()" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:border-emerald-500 cursor-pointer">
                    <option value="all">هەموو نووسینگەکان</option>
                    @foreach($agencies as $ag)
                        <option value="{{ $ag->id }}" {{ request('agency_id') == $ag->id ? 'selected' : '' }}>{{ $ag->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by Property Type (Instant) -->
            <div>
                <select name="type" id="filterTypeSelect" onchange="applyFiltersInstant()" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:border-emerald-500 cursor-pointer">
                    <option value="all">هەموو جۆرەکان</option>
                    <option value="house" {{ request('type') === 'house' ? 'selected' : '' }}>خانوو</option>
                    <option value="apartment" {{ request('type') === 'apartment' ? 'selected' : '' }}>شوقە</option>
                    <option value="villa" {{ request('type') === 'villa' ? 'selected' : '' }}>ڤێلا</option>
                </select>
            </div>

            <!-- Filter by Neighborhood (Instant) -->
            <div>
                <select name="neighborhood" id="filterNeighborhoodSelect" onchange="applyFiltersInstant()" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:border-emerald-500 cursor-pointer">
                    <option value="all">هەموو گەڕەکەکان</option>
                    @foreach($neighborhoods as $nh)
                        <option value="{{ $nh }}" {{ request('neighborhood') === $nh ? 'selected' : '' }}>{{ $nh }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Reset & Loading Status -->
            <div class="flex items-center gap-2">
                <button type="button" onclick="resetAllFilters()" class="flex-1 py-2 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-bold text-xs transition-colors flex items-center justify-center gap-1.5 border border-slate-700/60" title="پاککردنەوەی سەرجەم فلتەرەکان">
                    <i class="fa-solid fa-arrow-rotate-left text-xs text-slate-400"></i>
                    <span>پاککردنەوە</span>
                </button>
            </div>
        </form>

    </div>

    <!-- Properties Table Container -->
    <div id="propertiesTableContainer" class="bg-slate-900/90 rounded-3xl border border-slate-800 overflow-hidden shadow-xl relative transition-opacity duration-150">
        
        <!-- Subtle Non-Blocking Loading Spinner Overlay -->
        <div id="tableLoadingOverlay" class="hidden absolute inset-0 bg-slate-950/40 backdrop-blur-[1px] z-10 flex items-center justify-center">
            <div class="px-4 py-2 rounded-2xl bg-slate-900 border border-slate-700 text-emerald-400 font-bold text-xs shadow-2xl flex items-center gap-2">
                <i class="fa-solid fa-spinner fa-spin text-sm"></i>
                <span>فلتەرکردنی خێرا...</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-950/70 border-b border-slate-800 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">خانوو</th>
                        <th class="py-3.5 px-4">نووسینگە</th>
                        <th class="py-3.5 px-4">گەڕەک</th>
                        <th class="py-3.5 px-4">نرخ</th>
                        <th class="py-3.5 px-4">دۆخ (گۆڕین)</th>
                        <th class="py-3.5 px-4 text-center">VIP (سەرەکی)</th>
                        <th class="py-3.5 px-4 text-center">کردارەکان</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-slate-300">
                    @forelse($properties as $prop)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3 min-w-[220px]">
                                    <img src="{{ $prop->primary_image_url }}" alt="{{ $prop->title }}" class="w-11 h-11 rounded-xl object-cover border border-slate-700 flex-shrink-0">
                                    <div class="min-w-0">
                                        <a href="{{ route('properties.show', $prop->slug) }}" target="_blank" class="font-bold text-slate-200 hover:text-emerald-400 truncate block">
                                            {{ $prop->title }}
                                        </a>
                                        <span class="text-[10px] text-slate-500 font-medium">{{ $prop->type_text }} • {{ $prop->bedrooms }} ژوور</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($prop->agency)
                                    <a href="{{ route('admin.agencies.show', $prop->agency->id) }}" class="font-bold text-slate-300 hover:text-emerald-400">
                                        {{ $prop->agency->name }}
                                    </a>
                                @else
                                    <span class="text-slate-500">نادیار</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-950 border border-slate-800 text-slate-300 text-[11px] font-bold">
                                    {{ $prop->neighborhood }}
                                </span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="font-black text-emerald-400 font-mono text-sm" dir="ltr">
                                    ${{ number_format($prop->rent_price) }}
                                </span>
                                <span class="text-[10px] text-slate-500">/مانگانە</span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <!-- Fast Status Switcher Form -->
                                <form action="{{ route('admin.properties.status', $prop->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()"
                                        class="px-2.5 py-1 rounded-xl text-[11px] font-bold border transition-colors cursor-pointer {{ $prop->status === 'available' ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30' : ($prop->status === 'reserved' ? 'bg-amber-500/15 text-amber-400 border-amber-500/30' : 'bg-rose-500/15 text-rose-400 border-rose-500/30') }}">
                                        <option value="available" {{ $prop->status === 'available' ? 'selected' : '' }}>بەردەست</option>
                                        <option value="reserved" {{ $prop->status === 'reserved' ? 'selected' : '' }}>دانوستان</option>
                                        <option value="rented" {{ $prop->status === 'rented' ? 'selected' : '' }}>گیراوە</option>
                                    </select>
                                </form>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-center">
                                <!-- VIP Toggle Button -->
                                <form action="{{ route('admin.properties.toggle-featured', $prop->id) }}" method="POST" class="inline">
                                    @csrf
                                    @if($prop->is_featured)
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gradient-to-r from-amber-500/20 to-amber-600/20 text-amber-300 border border-amber-500/50 text-[11px] font-black hover:bg-amber-500/30 hover:scale-105 transition-all shadow-sm shadow-amber-500/20 cursor-pointer" title="کرتە بکە بۆ لادان لە VIP">
                                            <i class="fa-solid fa-crown text-amber-400"></i>
                                            <span>👑 VIP (چالاکە)</span>
                                        </button>
                                    @else
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800 text-slate-400 border border-slate-700 hover:border-amber-500/50 hover:text-amber-300 hover:bg-slate-750 text-[11px] font-bold transition-all hover:scale-105 cursor-pointer" title="کرتە بکە بۆ دانان وەک VIP لە لاپەڕەی سەرەکی">
                                            <i class="fa-regular fa-star text-slate-400"></i>
                                            <span>ئاسایی (بیکە بە VIP)</span>
                                        </button>
                                    @endif
                                </form>
                            </td>
                            <td class="py-3 px-4 text-center min-w-[100px]">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('properties.show', $prop->slug) }}" target="_blank" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-colors" title="بینین لە ماڵپەڕ">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.properties.destroy', $prop->id) }}" method="POST" onsubmit="return confirm('ئایا دڵنیایت لە سڕینەوەی ئەم خانووە لە سیستەم؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition-colors" title="سڕینەوە">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500 text-xs">
                                هیچ خانوویەک نەدۆزرایەوە بەپێی ئەم فلتەرانە.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div id="paginationContainer" class="pt-2">
        {{ $properties->links() }}
    </div>

</div>

@push('scripts')
<script>
    let activeAbortController = null;
    let liveSearchDebounce = null;

    function selectStatusFilter(statusVal) {
        document.getElementById('filterStatusInput').value = statusVal;
        document.getElementById('filterFeaturedInput').value = 'all';
        applyFiltersInstant();
    }

    function selectFeaturedFilter(featuredVal) {
        document.getElementById('filterFeaturedInput').value = featuredVal;
        document.getElementById('filterStatusInput').value = 'all';
        applyFiltersInstant();
    }

    function resetAllFilters() {
        document.getElementById('filterSearchInput').value = '';
        document.getElementById('filterAgencySelect').value = 'all';
        document.getElementById('filterTypeSelect').value = 'all';
        document.getElementById('filterNeighborhoodSelect').value = 'all';
        document.getElementById('filterStatusInput').value = 'all';
        document.getElementById('filterFeaturedInput').value = 'all';
        applyFiltersInstant();
    }

    function applyFiltersInstant(customUrl = null) {
        const form = document.getElementById('filterPropertiesForm');
        const tableContainer = document.getElementById('propertiesTableContainer');
        const overlay = document.getElementById('tableLoadingOverlay');
        const tabsContainer = document.getElementById('statusTabsContainer');
        const paginationContainer = document.getElementById('paginationContainer');

        if (!form) return;

        // Cancel pending request if user changed input rapidly
        if (activeAbortController) {
            activeAbortController.abort();
        }
        activeAbortController = new AbortController();

        let targetUrl = customUrl;
        if (!targetUrl) {
            const formData = new FormData(form);
            const params = new URLSearchParams();

            for (const [key, val] of formData.entries()) {
                if (val && val !== 'all') {
                    params.append(key, val);
                }
            }
            targetUrl = `${form.action}${params.toString() ? '?' + params.toString() : ''}`;
        }

        // Show subtle non-blocking loading state
        if (overlay) overlay.classList.remove('hidden');
        if (tableContainer) tableContainer.classList.add('opacity-75');

        fetch(targetUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            signal: activeAbortController.signal
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newTable = doc.getElementById('propertiesTableContainer');
            const newPagination = doc.getElementById('paginationContainer');
            const newTabs = doc.getElementById('statusTabsContainer');

            if (newTable && tableContainer) {
                tableContainer.innerHTML = newTable.innerHTML;
            }
            if (newPagination && paginationContainer) {
                paginationContainer.innerHTML = newPagination.innerHTML;
                attachPaginationEvents();
            }
            if (newTabs && tabsContainer) {
                tabsContainer.innerHTML = newTabs.innerHTML;
            }

            // Sync URL in address bar without reloading
            window.history.replaceState(null, '', targetUrl);
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                console.error('Fast filter error, fallback to normal submit', err);
                form.submit();
            }
        })
        .finally(() => {
            if (overlay) overlay.classList.add('hidden');
            if (tableContainer) tableContainer.classList.remove('opacity-75');
        });
    }

    function attachPaginationEvents() {
        const pagination = document.getElementById('paginationContainer');
        if (!pagination) return;
        pagination.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                applyFiltersInstant(this.href);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('filterSearchInput');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(liveSearchDebounce);
                // 180ms debounce for lightning fast responsive typing
                liveSearchDebounce = setTimeout(function() {
                    applyFiltersInstant();
                }, 180);
            });
        }

        attachPaginationEvents();
    });
</script>
@endpush
@endsection
