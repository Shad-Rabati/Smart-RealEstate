@extends('layouts.app')

@section('title', __('نووسینگەکانی خانوبەرە لە سلێمانی | پلاتفۆرمی خانوبەرەی سلێمانی'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <div class="bg-gradient-to-r from-slate-900 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-lg">
        <div class="space-y-2 max-w-xl">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                {{ __('ڕێبەری فەرمی نووسینگەکان') }}
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                {{ __('نووسینگەکانی خانوبەرە لە شاری سلێمانی') }}
            </h1>
            <p class="text-sm text-slate-300 leading-relaxed">
                {{ __('ڕاستەوخۆ پەیوەندی بە باشترین نووسینگەکانی خانوبەرەی سلێمانییەوە بکە و موڵک و خانووەکانیان بەسەربکەرەوە.') }}
            </p>
        </div>

        <div>
            @guest
                <a href="{{ route('agency.register') }}" class="px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-md transition-all inline-flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> {{ __('تۆمارکردنی نووسینگەکەت') }}
                </a>
            @endguest
        </div>
    </div>

    <!-- Agencies Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($agencies as $agency)
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-md hover:border-slate-300 transition-all flex flex-col justify-between group">
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <img src="{{ $agency->logo_url }}" alt="{{ $agency->name }}" class="w-16 h-16 rounded-2xl object-cover border border-slate-200 group-hover:scale-105 transition-transform">
                        <div class="flex-1">
                            <h3 class="font-extrabold text-slate-900 text-base group-hover:text-emerald-700 transition-colors">
                                <a href="{{ route('agencies.show', $agency->slug) }}">
                                    {{ $agency->name }}
                                </a>
                            </h3>
                            @if($agency->owner_name)
                                <span class="text-xs text-slate-700 block">{{ __('بەڕێوەبەر:') }} {{ $agency->owner_name }}</span>
                            @endif
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 mt-1">
                                <i class="fa-solid fa-circle-check"></i> {{ __('نووسینگەی چالاک') }}
                            </span>
                        </div>
                    </div>

                    <p class="text-xs text-slate-700 line-clamp-2 leading-relaxed">
                        {{ $agency->description ?: __('نووسینگەی فەرمی کڕین و فرۆشتن و بەکرێدانی خانوبەرە لە شاری سلێمانی.') }}
                    </p>

                    <div class="text-xs text-slate-700 space-y-1.5 pt-2 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-slate-400"></i>
                            <span class="truncate">{{ $agency->address }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-phone text-slate-400"></i>
                            <span dir="ltr">{{ $agency->phone }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg">
                        {{ $agency->available_properties_count }} {{ __('خانووی بەردەست') }}
                    </span>

                    <div class="flex items-center gap-2">
                        @if($agency->whatsapp_link)
                            <a href="{{ $agency->whatsapp_link }}" target="_blank" class="p-2 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-colors" title="{{ __('واتسئەپ') }}">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                            </a>
                        @endif
                        <a href="{{ route('agencies.show', $agency->slug) }}" class="px-3.5 py-1.5 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-emerald-600 transition-colors">
                            {{ __('بینینی موڵکەکان') }}
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-3xl border border-slate-200">
                <i class="fa-solid fa-building-circle-exclamation text-4xl text-slate-400 mb-3"></i>
                <h3 class="text-base font-bold text-slate-700">{{ __('هیچ نووسینگەیەک تۆمار نەکراوە') }}</h3>
            </div>
        @endforelse
    </div>

    <div>
        {{ $agencies->links() }}
    </div>

</div>
@endsection
