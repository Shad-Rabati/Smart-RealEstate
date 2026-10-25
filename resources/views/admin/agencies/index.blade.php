@extends('layouts.admin')

@section('title', 'بەڕێوەبردنی نووسینگەکان')
@section('page_title', 'نووسینگەکانی خانوبەرە')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Filter Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900/90 p-5 rounded-3xl border border-slate-800/80">
        
        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
            <a href="{{ route('admin.agencies.index') }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ !request()->has('verified') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-slate-800 text-slate-400 hover:text-white' }}">
                هەموو نووسینگەکان ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.agencies.index', ['verified' => '1']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('verified') === '1' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-slate-800 text-slate-400 hover:text-white' }}">
                باوەڕپێکراو ({{ $counts['verified'] }})
            </a>
            <a href="{{ route('admin.agencies.index', ['verified' => '0']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('verified') === '0' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-slate-800 text-slate-400 hover:text-white' }}">
                ئاسایی ({{ $counts['unverified'] }})
            </a>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <!-- Search Form (Live Instant Search) -->
            <form id="adminAgenciesSearchForm" method="GET" action="{{ route('admin.agencies.index') }}" class="flex items-center gap-2">
                @if(request('verified'))
                    <input type="hidden" name="verified" value="{{ request('verified') }}">
                @endif
                <div class="relative w-full sm:w-60">
                    <input type="text" name="q" id="adminAgenciesSearchInput" value="{{ request('q') }}" placeholder="گەڕان بەپێی ناو، خاوەن، تەلەفۆن..."
                        class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder:text-slate-500 focus:outline-none focus:border-emerald-500 transition-colors">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-500 text-xs"></i>
                </div>
                <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition-colors">
                    گەڕان
                </button>
            </form>

            <!-- Add Agency Button -->
            <a href="{{ route('admin.agencies.create') }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-black shadow-lg shadow-emerald-600/25 transition-all flex items-center justify-center gap-1.5 flex-shrink-0">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>زیادکردنی نووسینگە</span>
            </a>
        </div>

    </div>

    <!-- Agencies Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($agencies as $agency)
            <div class="bg-slate-900/90 rounded-3xl p-5 border border-slate-800/80 hover:border-slate-700 transition-all space-y-4 relative flex flex-col justify-between">
                
                <div class="space-y-3.5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $agency->logo_url }}" alt="{{ $agency->name }}" class="w-12 h-12 rounded-2xl object-cover border border-slate-700 shadow-sm flex-shrink-0">
                            <div>
                                <h4 class="text-sm font-black text-white hover:text-emerald-400 transition-colors">
                                    <a href="{{ route('admin.agencies.show', $agency->id) }}">
                                        {{ $agency->name }}
                                    </a>
                                </h4>
                                <div class="text-xs text-slate-400 font-medium">خاوەن: {{ $agency->owner_name ?? 'دیارینەکراو' }}</div>
                            </div>
                        </div>

                        <!-- Verification Status Badge -->
                        @if($agency->verified)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 shadow-sm">
                                <i class="fa-solid fa-circle-check text-[11px]"></i>
                                <span>باوەڕپێکراو</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-500/15 text-amber-400 border border-amber-500/30">
                                <i class="fa-solid fa-hourglass-half text-[10px]"></i>
                                <span>چاوەڕوانە</span>
                            </span>
                        @endif
                    </div>

                    <div class="text-xs text-slate-400 space-y-1.5 pt-1">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-phone text-slate-500 text-[11px] w-4"></i>
                            <span dir="ltr">{{ $agency->phone }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-slate-500 text-[11px] w-4"></i>
                            <span class="truncate">{{ $agency->address }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2.5">
                        <span class="text-slate-400 font-bold flex items-center gap-1.5">
                            <i class="fa-solid fa-house-chimney text-emerald-400"></i>
                            <span>{{ $agency->properties_count }} خانوو</span>
                        </span>

                        <a href="{{ route('admin.agencies.show', $agency->id) }}" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-mono font-black bg-amber-500/15 hover:bg-amber-500/25 text-amber-400 border border-amber-500/30 transition-all" title="باڵانسی کریدت - کلیک بکە بۆ بەڕێوەبردن">
                            <i class="fa-solid fa-coins text-[10px] text-amber-400"></i>
                            <span>{{ $agency->credits }}</span>
                            <span class="text-[9px] font-normal text-amber-300">کریدت</span>
                        </a>
                    </div>

                    <div class="flex items-center gap-2">
                        @if(!$agency->verified)
                            <!-- دوگمەی سەوزی پەسەندکردن -->
                            <form action="{{ route('admin.agencies.toggle-verify', $agency->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs shadow-md shadow-emerald-600/30 transition-all flex items-center gap-1.5" title="پەسەندکردنی ئەم نووسینگەیە">
                                    <i class="fa-solid fa-check text-xs"></i>
                                    <span>پەسەندکردن</span>
                                </button>
                            </form>
                        @else
                            <!-- دوگمەی هەڵپەساردن -->
                            <form action="{{ route('admin.agencies.toggle-verify', $agency->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2.5 py-1.5 rounded-xl bg-slate-800 hover:bg-amber-500/20 text-slate-400 hover:text-amber-400 text-xs font-bold transition-all flex items-center gap-1" title="لابردنی دۆخی باوەڕپێکراوی">
                                    <i class="fa-solid fa-ban text-[10px]"></i>
                                    <span>هەڵپەساردن</span>
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('admin.agencies.show', $agency->id) }}" class="px-2.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold transition-colors">
                            وردەکاری
                        </a>
                        
                        <form action="{{ route('admin.agencies.destroy', $agency->id) }}" method="POST" onsubmit="return confirm('ئایا دڵنیایت لە سڕینەوەی ئەم نووسینگەیە و سەرجەم خانووەکانی؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 font-bold transition-colors" title="سڕینەوەی نووسینگە">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-500 text-xs">
                هیچ نووسینگەیەک نەدۆزرایەوە بەم مەرجانە.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-4">
        {{ $agencies->links() }}
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('adminAgenciesSearchInput');
        const form = document.getElementById('adminAgenciesSearchForm');

        if (searchInput && form) {
            let searchTimeout = null;

            if (searchInput.value) {
                searchInput.focus();
                const len = searchInput.value.length;
                searchInput.setSelectionRange(len, len);
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    form.submit();
                }, 450);
            });
        }
    });
</script>
@endpush
@endsection
