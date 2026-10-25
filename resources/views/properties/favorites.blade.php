@extends('layouts.app')

@section('title', __('messages.favorites_title') . ' | ' . __('messages.app_name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 text-rose-600 border border-rose-200 text-xs font-black mb-2">
                <i class="fa-solid fa-heart text-rose-500"></i>
                <span>{{ __('messages.favorites') }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                <span>{{ __('messages.favorites_title') }}</span>
                <span id="favPageCount" class="px-3 py-0.5 rounded-full bg-slate-900 text-white text-xs font-mono font-black">
                    {{ $properties->count() }}
                </span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-1">
                {{ __('messages.favorites_subtitle') }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" 
                    id="clearAllFavoritesBtn"
                    onclick="clearAllFavorites()" 
                    class="hidden px-4 py-2.5 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-black border border-rose-200/80 transition-all flex items-center gap-1.5">
                <i class="fa-solid fa-trash-can text-xs"></i>
                <span>{{ __('messages.clear_all') }}</span>
            </button>
            <a href="{{ route('properties.index') }}" class="px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black transition-all shadow-md flex items-center gap-2">
                <i class="fa-solid fa-map-location-dot text-xs"></i>
                <span>{{ __('messages.explore_more') }}</span>
            </a>
        </div>
    </div>

    <!-- Loading State -->
    <div id="favoritesLoading" class="py-16 text-center">
        <div class="inline-flex items-center gap-3 px-6 py-3 rounded-2xl bg-white border border-slate-200 shadow-sm text-slate-700 text-xs font-bold">
            <i class="fa-solid fa-circle-notch fa-spin text-rose-500 text-base"></i>
            <span>{{ __('messages.favorites') }}...</span>
        </div>
    </div>

    <!-- Empty State -->
    <div id="favoritesEmptyState" class="hidden py-16 text-center bg-white rounded-3xl border border-slate-200/90 p-8 shadow-sm space-y-4">
        <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-400 flex items-center justify-center text-3xl mx-auto shadow-sm">
            <i class="fa-regular fa-heart"></i>
        </div>
        <div class="space-y-1">
            <h3 class="text-base sm:text-lg font-black text-slate-900">{{ __('messages.favorites_empty') }}</h3>
            <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                {{ __('messages.favorites_empty_desc') }}
            </p>
        </div>
        <div class="pt-2">
            <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-slate-900 hover:bg-emerald-600 text-white text-xs font-black transition-all shadow-md">
                <i class="fa-solid fa-map-location-dot"></i>
                <span>{{ __('messages.explore_more') }}</span>
            </a>
        </div>
    </div>

    <!-- Dynamic Favorites Grid Container -->
    <div id="favoritesGridContainer">
        @if($properties->isNotEmpty())
            @include('partials.favorites_grid', ['properties' => $properties])
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        loadFavoritesPage();
    });

    function loadFavoritesPage() {
        const loading = document.getElementById('favoritesLoading');
        const emptyState = document.getElementById('favoritesEmptyState');
        const gridContainer = document.getElementById('favoritesGridContainer');
        const countBadge = document.getElementById('favPageCount');
        const clearBtn = document.getElementById('clearAllFavoritesBtn');

        const favorites = getFavorites();

        if (countBadge) {
            countBadge.innerText = favorites.length;
        }

        if (favorites.length === 0) {
            if (loading) loading.classList.add('hidden');
            if (emptyState) emptyState.classList.remove('hidden');
            if (gridContainer) gridContainer.innerHTML = '';
            if (clearBtn) clearBtn.classList.add('hidden');
            return;
        }

        if (clearBtn) clearBtn.classList.remove('hidden');

        // Check if server already rendered properties
        if (gridContainer && gridContainer.children.length > 0) {
            if (loading) loading.classList.add('hidden');
            return;
        }

        // Fetch from API
        fetch("{{ route('api.favorites.properties') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids: favorites })
        })
        .then(res => res.json())
        .then(data => {
            if (loading) loading.classList.add('hidden');

            if (data.success && data.count > 0) {
                if (gridContainer) gridContainer.innerHTML = data.html;
                if (emptyState) emptyState.classList.add('hidden');
                if (countBadge) countBadge.innerText = data.count;
            } else {
                if (emptyState) emptyState.classList.remove('hidden');
                if (gridContainer) gridContainer.innerHTML = '';
                if (countBadge) countBadge.innerText = '0';
            }
        })
        .catch(err => {
            console.error('Error loading favorites:', err);
            if (loading) loading.classList.add('hidden');
            if (emptyState) emptyState.classList.remove('hidden');
        });
    }

    function clearAllFavorites() {
        if (!confirm('ئایا دڵنیایت لە سڕینەوەی سەرجەم خانووەکان لە لیستی دڵخوازەکانت؟')) {
            return;
        }

        localStorage.removeItem(FAVORITES_KEY);
        updateFavoriteNavbarCount();
        updateAllFavoriteButtons();

        const gridContainer = document.getElementById('favoritesGridContainer');
        const emptyState = document.getElementById('favoritesEmptyState');
        const countBadge = document.getElementById('favPageCount');
        const clearBtn = document.getElementById('clearAllFavoritesBtn');

        if (gridContainer) gridContainer.innerHTML = '';
        if (emptyState) emptyState.classList.remove('hidden');
        if (countBadge) countBadge.innerText = '0';
        if (clearBtn) clearBtn.classList.add('hidden');

        showFavoriteToast(false, 'سەرجەم دڵخوازەکان سڕدرانەوە');
    }
</script>
@endpush
