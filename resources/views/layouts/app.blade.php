<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ku', 'ar']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.app_name') . ' | ' . __('messages.app_tagline'))</title>

    <!-- DNS Prefetch & Preconnect for all CDN origins (speeds up external resource loading) -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//unpkg.com">
    <link rel="dns-prefetch" href="//tile.openstreetmap.org">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.tailwindcss.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://unpkg.com" crossorigin>

    <!-- Google Fonts: Vazirmatn & Inter for Kurdish, Arabic & English typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Vazirmatn', 'Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            900: '#064e3b',
                        },
                        sulaimani: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0284c7',
                            600: '#0369a1',
                            700: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 Icons (non-blocking load for faster first paint) -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    <!-- Leaflet Map CSS & JS (Cloudflare Edge CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    <!-- Leaflet MarkerCluster (Groups nearby pins into elegant circles so map never gets cluttered) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.Default.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster.js"></script>

    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
            background-color: #f8fafc;
        }

        /* Leaflet popup customization - modern luxury card */
        .leaflet-popup-content-wrapper {
            direction: rtl;
            text-align: right;
            border-radius: 24px !important;
            padding: 0 !important;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.35), 0 0 0 1px rgba(226, 232, 240, 0.8) !important;
            background: #ffffff !important;
        }
        .leaflet-popup-content {
            margin: 0 !important;
            line-height: 1.5;
            font-family: 'Vazirmatn', sans-serif;
        }
        .leaflet-popup-tip {
            background: #ffffff !important;
            box-shadow: 0 10px 20px -3px rgba(0, 0, 0, 0.2) !important;
        }
        .leaflet-container {
            font-family: 'Vazirmatn', sans-serif !important;
        }
        .leaflet-bar {
            border: none !important;
            box-shadow: none !important;
        }

        /* Modern Real Estate Map Pins (Teardrop Price Badges) */
        /* Modern Real Estate Map Pins (Clean Airbnb Luxury White Pills) */
        .custom-cluster-wrapper {
            background: transparent !important;
            border: none !important;
        }
        .cluster-pill-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #ffffff;
            color: #0f172a;
            font-weight: 900;
            font-size: 13px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.15), 0 0 0 3px #10b981;
            border: 1px solid rgba(226, 232, 240, 0.9);
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            user-select: none;
        }
        .cluster-pill-badge:hover {
            transform: scale(1.15);
            background: #0f172a;
            color: #ffffff;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.3), 0 0 0 3px #10b981;
        }

        .custom-pin-wrapper {
            background: transparent !important;
            border: none !important;
        }
        .map-property-pin {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 9px;
            border-radius: 9999px;
            background: #ffffff;
            color: #0f172a;
            font-weight: 800;
            font-size: 12px;
            letter-spacing: -0.01em;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.14), 0 1px 3px rgba(15, 23, 42, 0.08);
            border: 1.5px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            white-space: nowrap;
            user-select: none;
        }
        .map-property-pin::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            border-width: 5px 4.5px 0 4.5px;
            border-style: solid;
            border-color: #ffffff transparent transparent transparent;
            filter: drop-shadow(0 1.5px 1px rgba(0,0,0,0.1));
        }
        .map-property-pin:hover,
        .map-property-pin.is-active {
            transform: scale(1.15) translateY(-3px);
            background: #0f172a !important;
            color: #ffffff !important;
            border-color: #0f172a !important;
            box-shadow: 0 12px 24px -2px rgba(15, 23, 42, 0.35);
            z-index: 99999 !important;
        }
        .map-property-pin:hover::after,
        .map-property-pin.is-active::after {
            border-color: #0f172a transparent transparent transparent;
        }
        .map-property-pin .pin-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
            display: inline-block;
        }
        .map-property-pin.pin-badge-available .pin-dot {
            background-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }
        .map-property-pin.pin-badge-reserved .pin-dot {
            background-color: #94a3b8;
        }
        .map-property-pin.pin-badge-rented .pin-dot {
            background-color: #f43f5e;
            box-shadow: 0 0 0 2px rgba(244, 63, 94, 0.2);
        }

        /* Floating Glass Island Effect */
        .glass-island {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 12px 30px -4px rgba(15, 23, 42, 0.18), 0 4px 10px -2px rgba(15, 23, 42, 0.08);
        }
        .glass-island-dark {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 14px 35px -4px rgba(0, 0, 0, 0.35);
        }

        /* User GPS Location Pin */
        .user-gps-pin {
            width: 24px;
            height: 24px;
            background: radial-gradient(circle, #3b82f6 35%, #1d4ed8 100%);
            border: 3.5px solid white;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(29, 78, 216, 0.5);
            animation: pulse-gps 2s infinite;
        }
        @keyframes pulse-gps {
            0% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.65); }
            70% { box-shadow: 0 0 0 18px rgba(37, 99, 235, 0); }
            100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen flex flex-col text-slate-800 antialiased">

    <!-- Top Navigation Header -->
    <header class="bg-white/80 backdrop-blur-xl border-b border-slate-200/70 sticky top-0 z-50 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-[72px]">
                
                <!-- Logo & Brand (Right in RTL) -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="relative w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 via-emerald-500 to-teal-400 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20 ring-1 ring-emerald-500/20 group-hover:scale-105 group-hover:shadow-emerald-500/35 transition-all duration-300">
                            <i class="fa-solid fa-house-chimney text-base"></i>
                            <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-white shadow-sm"></span>
                        </div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1.5">
                                <span class="text-lg font-black text-slate-900 tracking-tight leading-none group-hover:text-emerald-700 transition-colors">
                                    {{ __('messages.app_name') }}
                                </span>
                            </div>
                            <span class="text-[11px] text-slate-500 font-semibold flex items-center gap-1.5 mt-1 leading-none">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                {{ __('messages.app_tagline') }}
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Center Navigation: Sleek Capsule Dock -->
                <nav class="hidden md:flex items-center p-1 rounded-full bg-slate-100/80 backdrop-blur-md border border-slate-200/60 shadow-[inset_0_1px_2px_rgba(0,0,0,0.04)] gap-0.5">
                    <!-- 1. سەرەکی -->
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-full text-xs font-bold transition-all flex items-center gap-2 {{ request()->routeIs('home') ? 'bg-white text-emerald-800 shadow-sm shadow-slate-900/5 font-black ring-1 ring-slate-900/5' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                        <i class="fa-solid fa-house {{ request()->routeIs('home') ? 'text-emerald-600' : 'text-slate-400' }} text-xs"></i>
                        <span>{{ __('messages.home') }}</span>
                    </a>

                    <!-- 2. گەڕانی خانوو -->
                    <a href="{{ route('properties.index') }}" class="px-4 py-2 rounded-full text-xs font-bold transition-all flex items-center gap-2 {{ request()->routeIs('properties.*') ? 'bg-white text-emerald-800 shadow-sm shadow-slate-900/5 font-black ring-1 ring-slate-900/5' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                        <i class="fa-solid fa-map-location-dot {{ request()->routeIs('properties.*') ? 'text-emerald-600' : 'text-slate-400' }} text-xs"></i>
                        <span>{{ __('messages.search_properties') }}</span>
                    </a>

                    <!-- 3. نووسینگەکان -->
                    <a href="{{ route('agencies.index') }}" class="px-4 py-2 rounded-full text-xs font-bold transition-all flex items-center gap-2 {{ request()->routeIs('agencies.*') ? 'bg-white text-emerald-800 shadow-sm shadow-slate-900/5 font-black ring-1 ring-slate-900/5' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                        <i class="fa-solid fa-building {{ request()->routeIs('agencies.*') ? 'text-emerald-600' : 'text-slate-400' }} text-xs"></i>
                        <span>{{ __('messages.agencies') }}</span>
                    </a>

                    <!-- 4. ئوتێلەکان -->
                    <a href="{{ route('hotels.index') }}" class="px-4 py-2 rounded-full text-xs font-bold transition-all flex items-center gap-2 {{ request()->routeIs('hotels.*') ? 'bg-white text-amber-800 shadow-sm shadow-slate-900/5 font-black ring-1 ring-slate-900/5' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                        <i class="fa-solid fa-hotel {{ request()->routeIs('hotels.*') ? 'text-amber-500' : 'text-slate-400' }} text-xs"></i>
                        <span>{{ __('messages.hotels') }}</span>
                    </a>

                    <!-- 5. دڵخوازەکان -->
                    <a href="{{ route('favorites.index') }}" class="px-3.5 py-2 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 {{ request()->routeIs('favorites.*') ? 'bg-white text-rose-700 shadow-sm shadow-slate-900/5 font-black ring-1 ring-slate-900/5' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}" title="{{ __('messages.favorites') }}">
                        <i class="fa-solid fa-heart {{ request()->routeIs('favorites.*') ? 'text-rose-600' : 'text-rose-400' }} text-xs"></i>
                        <span>{{ __('messages.favorites') }}</span>
                        <span class="favorites-badge-count hidden px-1.5 py-0.5 rounded-full bg-rose-500 text-white text-[10px] font-mono font-black">0</span>
                    </a>

                    <div class="w-px h-3.5 bg-slate-300/70 mx-1"></div>

                    <!-- 6. نزیکترین لە منەوە -->
                    <button type="button" onclick="triggerNearestSearch()" class="px-3.5 py-2 rounded-full text-xs font-bold text-slate-700 hover:text-blue-700 hover:bg-white/80 transition-all flex items-center gap-2 group/near">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                        </span>
                        <i class="fa-solid fa-location-crosshairs text-blue-600 text-xs group-hover/near:scale-110 transition-transform"></i>
                        <span>{{ __('messages.nearest') }}</span>
                    </button>
                </nav>

                <!-- Left Side: Language Switcher & Agency Authentication Area -->
                <div class="flex items-center gap-2">
                    <!-- Language Selector Dropdown -->
                    <div class="relative" id="langDropdownWrapper">
                        <button type="button" 
                                onclick="toggleLangDropdown(event)" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-slate-100 hover:bg-slate-200/80 text-slate-700 border border-slate-200/80 transition-all shadow-sm active:scale-95 cursor-pointer"
                                title="{{ __('messages.language') }}">
                            @if(app()->getLocale() === 'en')
                                <span class="text-xs">🇬🇧</span>
                                <span class="font-extrabold text-[11px] font-mono">EN</span>
                            @elseif(app()->getLocale() === 'ar')
                                <span class="text-xs">🇸🇦</span>
                                <span class="font-extrabold text-[11px]">عربي</span>
                            @else
                                <span class="text-xs">☀️</span>
                                <span class="font-extrabold text-[11px]">کوردی</span>
                            @endif
                            <i class="fa-solid fa-chevron-down text-[8px] text-slate-400"></i>
                        </button>

                        <div id="langDropdownMenu" class="hidden absolute top-full mt-2 {{ in_array(app()->getLocale(), ['ku', 'ar']) ? 'left-0' : 'right-0' }} w-36 bg-white rounded-2xl shadow-xl border border-slate-200 py-1.5 z-50">
                            <a href="{{ route('locale.switch', 'ku') }}" class="flex items-center justify-between px-3.5 py-2 text-xs font-bold transition-colors {{ app()->getLocale() === 'ku' ? 'bg-emerald-50 text-emerald-700 font-black' : 'text-slate-700 hover:bg-slate-50' }}">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm">☀️</span>
                                    <span>کوردی</span>
                                </div>
                                @if(app()->getLocale() === 'ku')
                                    <i class="fa-solid fa-check text-emerald-600 text-xs"></i>
                                @endif
                            </a>
                            <a href="{{ route('locale.switch', 'en') }}" class="flex items-center justify-between px-3.5 py-2 text-xs font-bold transition-colors {{ app()->getLocale() === 'en' ? 'bg-emerald-50 text-emerald-700 font-black' : 'text-slate-700 hover:bg-slate-50' }}">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm">🇬🇧</span>
                                    <span>English</span>
                                </div>
                                @if(app()->getLocale() === 'en')
                                    <i class="fa-solid fa-check text-emerald-600 text-xs"></i>
                                @endif
                            </a>
                            <a href="{{ route('locale.switch', 'ar') }}" class="flex items-center justify-between px-3.5 py-2 text-xs font-bold transition-colors {{ app()->getLocale() === 'ar' ? 'bg-emerald-50 text-emerald-700 font-black' : 'text-slate-700 hover:bg-slate-50' }}">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm">🇸🇦</span>
                                    <span>العربية</span>
                                </div>
                                @if(app()->getLocale() === 'ar')
                                    <i class="fa-solid fa-check text-emerald-600 text-xs"></i>
                                @endif
                            </a>
                        </div>
                    </div>

                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-black bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white shadow-md shadow-emerald-600/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                                <i class="fa-solid fa-shield-halved text-xs"></i>
                                <span>{{ __('messages.admin_panel') }}</span>
                            </a>
                            <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="w-9 h-9 rounded-full text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors" title="{{ __('messages.logout') }}">
                                    <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                                </button>
                            </form>
                        @elseif(Auth::user()->agency)
                            @if(Auth::user()->agency->verified)
                                <a href="{{ route('agency.credits') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black bg-amber-500/10 hover:bg-amber-500/20 text-amber-800 border border-amber-500/30 transition-all hover:scale-105 shadow-sm" title="{{ __('messages.credits') }}">
                                    <i class="fa-solid fa-coins text-amber-500 text-xs"></i>
                                    <span class="font-mono">{{ Auth::user()->agency->credits }}</span>
                                    <span class="text-[10px] text-amber-700 font-bold">{{ __('messages.credits') }}</span>
                                </a>
                                <a href="{{ route('agency.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                                    <i class="fa-solid fa-gauge-high"></i>
                                    <span>{{ __('messages.agency_dashboard') }}</span>
                                </a>
                            @else
                                <a href="{{ route('agency.pending') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold bg-amber-500 hover:bg-amber-600 text-white shadow-md shadow-amber-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]" title="{{ __('messages.pending_approval') }}">
                                    <i class="fa-solid fa-hourglass-half"></i>
                                    <span>{{ __('messages.pending_approval') }}</span>
                                </a>
                            @endif
                            <form action="{{ route('agency.logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="w-9 h-9 rounded-full text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors" title="{{ __('messages.logout') }}">
                                    <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                                </button>
                            </form>
                        @endif
                    @else
                        <!-- چوونەژوورەوە Action Button -->
                        <a href="{{ route('agency.login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-black bg-slate-900 hover:bg-emerald-600 text-white shadow-sm transition-all hover:scale-[1.02] active:scale-[0.98]">
                            <i class="fa-solid fa-arrow-right-to-bracket text-emerald-400 text-xs"></i>
                            <span>{{ __('messages.login') }}</span>
                        </a>
                    @endauth
                </div>

            </div>
        </div>

        <!-- Mobile Quick Subbar: Clean Capsule Floating Dock -->
        <div class="md:hidden border-t border-slate-100/90 py-2 px-3 bg-white/95 backdrop-blur-xl">
            <div class="flex items-center justify-around text-xs font-bold text-slate-700 max-w-md mx-auto">
                <a href="{{ route('home') }}" class="px-2.5 py-1.5 rounded-full transition-all flex items-center gap-1 {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700 font-extrabold shadow-sm' : 'hover:text-emerald-600' }}">
                    <i class="fa-solid fa-house text-xs"></i>
                    <span>{{ __('messages.home') }}</span>
                </a>
                <a href="{{ route('properties.index') }}" class="px-2.5 py-1.5 rounded-full transition-all flex items-center gap-1 {{ request()->routeIs('properties.*') ? 'bg-emerald-50 text-emerald-700 font-extrabold shadow-sm' : 'hover:text-emerald-600' }}">
                    <i class="fa-solid fa-map-location-dot text-xs"></i>
                    <span>{{ __('messages.search_properties') }}</span>
                </a>
                <a href="{{ route('agencies.index') }}" class="px-2 py-1.5 rounded-full transition-all flex items-center gap-1 {{ request()->routeIs('agencies.*') ? 'bg-emerald-50 text-emerald-700 font-extrabold shadow-sm' : 'hover:text-emerald-600' }}">
                    <i class="fa-solid fa-building text-xs"></i>
                    <span>{{ __('messages.agency') }}</span>
                </a>
                <a href="{{ route('hotels.index') }}" class="px-2 py-1.5 rounded-full transition-all flex items-center gap-1 {{ request()->routeIs('hotels.*') ? 'bg-amber-50 text-amber-700 font-extrabold shadow-sm' : 'hover:text-amber-600' }}">
                    <i class="fa-solid fa-hotel text-xs"></i>
                    <span>{{ __('messages.hotel') }}</span>
                </a>
                <a href="{{ route('favorites.index') }}" class="px-2 py-1.5 rounded-full transition-all flex items-center gap-1 relative {{ request()->routeIs('favorites.*') ? 'bg-rose-50 text-rose-700 font-extrabold shadow-sm' : 'hover:text-rose-600' }}" title="{{ __('messages.favorites') }}">
                    <i class="fa-solid fa-heart text-xs text-rose-500"></i>
                    <span>{{ __('messages.favorites') }}</span>
                    <span class="favorites-badge-count hidden px-1.5 py-0.2 rounded-full bg-rose-500 text-white text-[9px] font-mono font-black">0</span>
                </a>
                <button type="button" onclick="triggerNearestSearch()" class="px-2 py-1.5 rounded-full text-blue-600 hover:bg-blue-50 transition-colors flex items-center gap-1 font-bold">
                    <i class="fa-solid fa-location-crosshairs text-xs"></i>
                    <span>{{ __('messages.nearest_short') }}</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                <div class="text-sm font-semibold flex-1">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-lg"></i>
                <div class="text-sm font-semibold flex-1">{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
                <div class="flex items-center gap-2 font-bold mb-1 text-sm">
                    <i class="fa-solid fa-triangle-exclamation"></i> تکایە ئەم هەڵانە چاکبکەوە:
                </div>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1 w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 mt-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10 items-start">
                
                <!-- Col 1: About Platform & Quick Links -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white shadow-md shadow-emerald-500/20">
                            <i class="fa-solid fa-house-chimney text-lg"></i>
                        </div>
                        <span class="text-xl font-black text-white">
                            {{ __('messages.app_name') }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        {{ __('پلاتفۆرمی سەردەمیانەی کرێی خانوو لە سلێمانی. بەبێ پێویستی سەردانیکردنی دەیان نووسینگەی جیاواز، ڕاستەوخۆ لەسەر نەخشەی شارەکە خانووی دڵخوازی خۆت بدۆزەرەوە یان نزیکترین شوێن لە خۆتەوە هەڵبژێرە.') }}
                    </p>
                    <div class="flex items-center gap-2.5 text-xs text-slate-400 pt-1 flex-wrap">
                        <a href="{{ route('home') }}" class="hover:text-emerald-400 transition-colors flex items-center gap-1 font-semibold">
                            <i class="fa-solid fa-house text-emerald-500"></i> {{ __('messages.home') }}
                        </a>
                        <span>•</span>
                        <a href="{{ route('properties.index') }}" class="hover:text-emerald-400 transition-colors flex items-center gap-1 font-semibold">
                            <i class="fa-solid fa-map-location-dot text-emerald-500"></i> {{ __('messages.search_properties') }}
                        </a>
                        <span>•</span>
                        <a href="{{ route('agencies.index') }}" class="hover:text-emerald-400 transition-colors flex items-center gap-1 font-semibold">
                            <i class="fa-solid fa-building text-emerald-500"></i> {{ __('messages.agencies') }}
                        </a>
                        <span>•</span>
                        <a href="{{ route('hotels.index') }}" class="hover:text-amber-400 transition-colors flex items-center gap-1 font-semibold">
                            <i class="fa-solid fa-hotel text-amber-500"></i> {{ __('messages.hotels') }}
                        </a>
                    </div>
                </div>

                <!-- Col 2: Real Estate Agencies Portal -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-emerald-400 text-base shadow-sm">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                        <h4 class="text-white font-black text-base">{{ __('نووسینگەکانی خانوبەرە') }}</h4>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        {{ __('خاوەنی نووسینگەی خانوبەرەیت لە سلێمانی؟ هەژماری خۆت تۆمار بکە و خانووەکانت لەسەر نەخشەی شار دابنێ تا کڕیاران و کرێچییەکان ڕاستەوخۆ پەیوەندیت پێوە بکەن.') }}
                    </p>
                    <div class="pt-1">
                        <a href="{{ route('agency.register') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md shadow-emerald-600/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            <i class="fa-solid fa-plus-circle text-xs"></i>
                            <span>{{ __('تۆمارکردنی نووسینگە بە خۆڕایی') }}</span>
                        </a>
                    </div>
                </div>

                <!-- Col 3: Hotels & Stays in Sulaymaniyah -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-amber-400 text-base shadow-sm">
                            <i class="fa-solid fa-hotel"></i>
                        </div>
                        <h4 class="text-white font-black text-base">{{ __('ئوتێلەکانی سلێمانی') }}</h4>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        {{ __('گەشتیاریت یان بەدوای شوێنی مانەوە دەگەڕێیت؟ هەموو ئوتێلە ناودارەکانی سلێمانی لەسەر نەخشەی شارەکە ببینە، بەپێی ئەستێرە و نرخ فلتەریان بکە و حجز بکە.') }}
                    </p>
                    <div class="pt-1 space-y-2">
                        <a href="{{ route('hotels.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black shadow-md shadow-amber-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            <i class="fa-solid fa-map-location-dot text-xs"></i>
                            <span>{{ __('نەخشە و لیستی ئوتێلەکان') }}</span>
                        </a>
                        <div>
                            <a href="https://wa.me/9647701234567?text={{ urlencode('سڵاو، دەمەوێت ئوتێلەکەم لە پلاتفۆرمی سلێمانی تۆمار بکەم') }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-amber-400 transition-colors font-medium">
                                <i class="fa-brands fa-whatsapp text-emerald-400 text-sm"></i>
                                <span>{{ __('تۆمارکردنی ئوتێلی نوێ لە پلاتفۆرم') }}</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Col 4: Social Media & Contacts (WhatsApp, Instagram, TikTok) -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-emerald-400 text-base shadow-sm">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-black text-base">{{ __('پەیوەندی و تۆڕەکانمان') }}</h4>
                            <span class="text-[11px] text-slate-400 block">{{ __('ڕاستەوخۆ قسەمان لەگەڵ بکە') }}</span>
                        </div>
                    </div>
                    
                    <div class="space-y-2 pt-1">
                        <!-- WhatsApp Link -->
                        <a href="https://wa.me/9647701234567?text={{ urlencode('سڵاو، لە پلاتفۆرمی خانوبەرەی سلێمانییەوە پەیوەندیتان پێوە دەکەم') }}" target="_blank" class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-[#25D366]/15 hover:bg-[#25D366] text-[#25D366] hover:text-white border border-[#25D366]/30 text-xs font-bold transition-all group shadow-sm">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                            <span class="flex-1">WhatsApp</span>
                            <i class="fa-solid fa-arrow-left rtl:rotate-0 ltr:rotate-180 text-[10px] group-hover:translate-x-[-3px] transition-transform"></i>
                        </a>

                        <!-- Instagram Link -->
                        <a href="https://instagram.com" target="_blank" class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-gradient-to-r from-[#833ab4]/20 via-[#fd1d1d]/20 to-[#fcb045]/20 hover:from-[#833ab4] hover:via-[#fd1d1d] hover:to-[#fcb045] text-rose-300 hover:text-white border border-rose-500/30 text-xs font-bold transition-all group shadow-sm">
                            <i class="fa-brands fa-instagram text-base"></i>
                            <span class="flex-1">Instagram</span>
                            <i class="fa-solid fa-arrow-left rtl:rotate-0 ltr:rotate-180 text-[10px] group-hover:translate-x-[-3px] transition-transform"></i>
                        </a>

                        <!-- TikTok Link -->
                        <a href="https://tiktok.com" target="_blank" class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-white/5 hover:bg-black text-slate-300 hover:text-white border border-white/10 hover:border-cyan-400/50 text-xs font-bold transition-all group shadow-sm">
                            <i class="fa-brands fa-tiktok text-base text-cyan-400"></i>
                            <span class="flex-1">TikTok</span>
                            <i class="fa-solid fa-arrow-left rtl:rotate-0 ltr:rotate-180 text-[10px] group-hover:translate-x-[-3px] transition-transform"></i>
                        </a>
                    </div>
                </div>

            </div>

            <div class="border-t border-slate-800 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <div>
                    © {{ date('Y') }} {{ __('messages.app_name') }}. {{ __('هەموو مافەکان پارێزراوە') }}.
                </div>
                <div class="flex items-center gap-4">
                    <span>{{ __('دیزاینکراوە بە تایبەتی بۆ خەڵکی خۆشەویستی شاری سلێمانی') }}</span>
                    <span>•</span>
                    <a href="{{ route('admin.login') }}" class="text-slate-500 hover:text-emerald-400 transition-colors flex items-center gap-1">
                        <i class="fa-solid fa-lock text-[10px]"></i>
                        <span>{{ __('سەرپەرشتیار') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Location Picker Modal (GPS & Sulaymaniyah Neighborhoods) -->
    <div id="locationPickerModal" class="hidden fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 space-y-5 animate-in fade-in zoom-in-95 duration-200">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg border border-blue-100">
                        <i class="fa-solid fa-location-crosshairs animate-pulse"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 text-base">{{ __('دیاریکردنی شوێنی ئێوە') }}</h3>
                        <p class="text-xs text-slate-500">{{ __('بۆ دۆزینەوەی نزیکترین خانووەکانی کرێ') }}</p>
                    </div>
                </div>
                <button type="button" onclick="closeLocationModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-sm transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Option 1: Live GPS -->
            <div class="space-y-2">
                <button type="button" id="modalGPSBtn" onclick="requestDeviceGPS()" class="w-full flex items-center justify-center gap-2.5 py-3.5 px-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition-all hover:scale-[1.01] active:scale-[0.99]">
                    <i class="fa-solid fa-location-arrow text-base"></i>
                    <span>{{ __('دۆزینەوە لە ڕێگەی GPS ی ئامێرەکەم') }}</span>
                </button>
                <div id="gpsStatusMessage" class="hidden text-center text-xs font-semibold py-1"></div>
            </div>

            <div class="relative flex py-1 items-center">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink mx-3 text-slate-400 text-xs font-bold">{{ __('یان گەڕەکەکەت لە سلێمانی دیاریبکە') }}</span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>

            <!-- Option 2: Neighborhood Buttons Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                <button type="button" onclick="selectNeighborhoodAndLocate(35.5685, 45.3950, 'سەرچنار')" class="p-2.5 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50 text-start text-xs font-bold text-slate-700 hover:text-emerald-800 transition-all">
                    📍 {{ __('سەرچنار') }}
                </button>
                <button type="button" onclick="selectNeighborhoodAndLocate(35.5640, 45.4410, 'تووی مەلیک')" class="p-2.5 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50 text-start text-xs font-bold text-slate-700 hover:text-emerald-800 transition-all">
                    📍 {{ __('تووی مەلیک') }}
                </button>
                <button type="button" onclick="selectNeighborhoodAndLocate(35.5510, 45.4120, 'بەختیاری')" class="p-2.5 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50 text-start text-xs font-bold text-slate-700 hover:text-emerald-800 transition-all">
                    📍 {{ __('بەختیاری') }}
                </button>
                <button type="button" onclick="selectNeighborhoodAndLocate(35.5780, 45.4520, 'بناری گۆیژە')" class="p-2.5 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50 text-start text-xs font-bold text-slate-700 hover:text-emerald-800 transition-all">
                    📍 {{ __('گۆیژە') }}
                </button>
                <button type="button" onclick="selectNeighborhoodAndLocate(35.5820, 45.3620, 'ڕاپەڕین')" class="p-2.5 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50 text-start text-xs font-bold text-slate-700 hover:text-emerald-800 transition-all">
                    📍 {{ __('ڕاپەڕین') }}
                </button>
                <button type="button" onclick="selectNeighborhoodAndLocate(35.5420, 45.4220, 'زەرگەتە')" class="p-2.5 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50 text-start text-xs font-bold text-slate-700 hover:text-emerald-800 transition-all">
                    📍 {{ __('زەرگەتە') }}
                </button>
                <button type="button" onclick="selectNeighborhoodAndLocate(35.6020, 45.2850, 'تاسڵوجە')" class="p-2.5 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50 text-start text-xs font-bold text-slate-700 hover:text-emerald-800 transition-all">
                    📍 {{ __('تاسڵوجە') }}
                </button>
                <button type="button" onclick="selectNeighborhoodAndLocate(35.5720, 45.4260, 'چوارچرا')" class="p-2.5 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50 text-start text-xs font-bold text-slate-700 hover:text-emerald-800 transition-all">
                    📍 {{ __('چوارچرا') }}
                </button>
                <button type="button" onclick="selectNeighborhoodAndLocate(35.5558, 45.4351, 'ناوەندی سلێمانی')" class="p-2.5 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50 text-start text-xs font-bold text-slate-700 hover:text-emerald-800 transition-all">
                    📍 {{ __('سەنتەری شار') }}
                </button>
            </div>

            <div class="pt-2 border-t border-slate-100 text-center">
                <p class="text-[11px] text-slate-400">
                    💡 ئەگەر لەسەر کۆمپیوتەریت یان لە دەرەوەی سلێمانییت، هەڵبژاردنی یەکێک لەم گەڕەکانە دەستبەجێ نزیکترین خانووەکانی ئەو گەڕەکەت پیشان دەدات.
                </p>
            </div>

        </div>
    </div>


    <!-- Global Geolocation & Location Script -->
    <script>
        function scrollToSulaimaniMap() {
            const target = document.getElementById('hotelMapSectionWrapper') ||
                           document.getElementById('hotelsMap') ||
                           document.getElementById('mapSectionWrapper') ||
                           document.getElementById('sulaimaniMap');
            if (target) {
                const headerOffset = 85;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        }

        function triggerNearestSearch() {
            // Check if on hotels page
            if (document.getElementById('hotelsMap') || document.getElementById('hotelMapSectionWrapper')) {
                scrollToSulaimaniMap();
                const modal = document.getElementById('locationPickerModal');
                if (modal) {
                    const subtitle = modal.querySelector('p.text-xs.text-slate-500');
                    if (subtitle) subtitle.innerText = 'بۆ دۆزینەوەی نزیکترین ئوتێل و شوێنی مانەوە';
                    modal.classList.remove('hidden');
                    const msg = document.getElementById('gpsStatusMessage');
                    if (msg) msg.classList.add('hidden');
                }
                return;
            }

            // Immediately take the user directly to the map on home page!
            if (document.getElementById('sulaimaniMap')) {
                scrollToSulaimaniMap();
                const modal = document.getElementById('locationPickerModal');
                if (modal) {
                    const subtitle = modal.querySelector('p.text-xs.text-slate-500');
                    if (subtitle) subtitle.innerText = 'بۆ دۆزینەوەی نزیکترین خانووەکانی کرێ';
                    modal.classList.remove('hidden');
                    const msg = document.getElementById('gpsStatusMessage');
                    if (msg) msg.classList.add('hidden');
                }
                return;
            }

            // If on another page, redirect straight to the map on properties page
            window.location.href = `{{ route('properties.index') }}?action=nearest#mapSectionWrapper`;
        }

        function closeLocationModal() {
            const modal = document.getElementById('locationPickerModal');
            if (modal) modal.classList.add('hidden');
        }

        function selectNeighborhoodAndLocate(lat, lng, name) {
            closeLocationModal();
            scrollToSulaimaniMap();

            if (window.locateAndFilterHotelsMap) {
                window.locateAndFilterHotelsMap(lat, lng, name);
                return;
            }

            if (window.locateAndFilterMap) {
                window.locateAndFilterMap(lat, lng, name);
                return;
            }

            window.location.href = `{{ route('properties.index') }}?user_lat=${lat}&user_lng=${lng}&sort=nearest#mapSectionWrapper`;
        }

        function requestDeviceGPS() {
            const statusMsg = document.getElementById('gpsStatusMessage');
            const btn = document.getElementById('modalGPSBtn');
            
            if (!navigator.geolocation) {
                statusMsg.className = 'text-center text-xs font-bold text-rose-600 block py-1';
                statusMsg.innerText = 'براوزەرەکەت پاڵپشتی GPS ناکات، تکایە یەکێک لە گەڕەکەکانی خوارەوە هەڵبژێرە.';
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-base"></i> دۆزینەوەی شوێنەکەت لەسەر نەخشە...';
            statusMsg.className = 'text-center text-xs font-semibold text-blue-600 block py-1';
            statusMsg.innerText = 'تکایە پەسەندی بکە لە براوزەرەکەتدا...';

            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-location-arrow text-base"></i> دۆزینەوە لە ڕێگەی GPS ی ئامێرەکەم';
                    
                    let lat = pos.coords.latitude;
                    let lng = pos.coords.longitude;
                    let label = 'شوێنی تۆ (GPS)';

                    // Calculate distance to Sulaymaniyah center (35.5558, 45.4351)
                    const dLat = (lat - 35.5558) * 111;
                    const dLng = (lng - 45.4351) * 90.3;
                    const distKm = Math.sqrt(dLat * dLat + dLng * dLng);

                    if (distKm > 45) {
                        // User's ISP is outside Sulaimani (e.g. Erbil) -> default smoothly to Sulaimani Center so map stays focused!
                        lat = 35.5558;
                        lng = 45.4351;
                        label = 'سەنتەری سلێمانی';
                    }

                    closeLocationModal();
                    scrollToSulaimaniMap();

                    if (window.locateAndFilterHotelsMap) {
                        window.locateAndFilterHotelsMap(lat, lng, label);
                        return;
                    }

                    if (window.locateAndFilterMap) {
                        window.locateAndFilterMap(lat, lng, label);
                        return;
                    }

                    window.location.href = `{{ route('properties.index') }}?user_lat=${lat}&user_lng=${lng}&sort=nearest#mapSectionWrapper`;
                },
                function(err) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-location-arrow text-base"></i> دۆزینەوە لە ڕێگەی GPS ی ئامێرەکەم';
                    statusMsg.className = 'text-center text-xs font-bold text-rose-600 block py-1 bg-rose-50 rounded-xl p-2';
                    statusMsg.innerText = 'ڕێگەپێدانی GPS لە براوزەرەکەت ڕەتکرایەوە. دەتوانیت بە ئاسانی یەکێک لە گەڕەکەکانی خوارەوە هەڵبژێریت.';
                },
                { enableHighAccuracy: true, timeout: 8000, maximumAge: 60000 }
            );
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('locationPickerModal');
            if (e.target === modal) {
                closeLocationModal();
            }

            // Close Language Dropdown if clicked outside
            const menu = document.getElementById('langDropdownMenu');
            const wrapper = document.getElementById('langDropdownWrapper');
            if (menu && wrapper && !wrapper.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });

        // Language Switcher Dropdown Toggle
        function toggleLangDropdown(e) {
            if (e) e.stopPropagation();
            const menu = document.getElementById('langDropdownMenu');
            if (menu) menu.classList.toggle('hidden');
        }

        // ==========================================
        // Universal Favorites / Wishlist System
        // ==========================================
        const FAVORITES_KEY = 'estate_user_favorites';

        function getFavorites() {
            try {
                const stored = localStorage.getItem(FAVORITES_KEY);
                return stored ? JSON.parse(stored) : [];
            } catch (e) {
                return [];
            }
        }

        function isFavorite(id) {
            const favs = getFavorites();
            return favs.includes(Number(id));
        }

        function toggleFavorite(id, title = '') {
            id = Number(id);
            let favs = getFavorites();
            const index = favs.indexOf(id);
            let isAdded = false;

            if (index > -1) {
                favs.splice(index, 1);
                isAdded = false;
            } else {
                favs.push(id);
                isAdded = true;
            }

            localStorage.setItem(FAVORITES_KEY, JSON.stringify(favs));
            updateFavoriteNavbarCount();
            updateAllFavoriteButtons();

            // If on favorites page, smoothly remove card when unfavorited
            const card = document.getElementById(`favorite-card-${id}`);
            if (card && !isAdded) {
                card.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    card.remove();
                    const remainingCards = document.querySelectorAll('[id^="favorite-card-"]');
                    const countBadge = document.getElementById('favPageCount');
                    if (countBadge) countBadge.innerText = remainingCards.length;
                    if (remainingCards.length === 0) {
                        const emptyState = document.getElementById('favoritesEmptyState');
                        const clearBtn = document.getElementById('clearAllFavoritesBtn');
                        if (emptyState) emptyState.classList.remove('hidden');
                        if (clearBtn) clearBtn.classList.add('hidden');
                    }
                }, 250);
            }

            showFavoriteToast(isAdded, title);
            return isAdded;
        }

        function updateFavoriteNavbarCount() {
            const favs = getFavorites();
            const count = favs.length;
            const badges = document.querySelectorAll('.favorites-badge-count');
            badges.forEach(badge => {
                badge.innerText = count;
                if (count > 0) {
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            });
        }

        function updateAllFavoriteButtons() {
            const buttons = document.querySelectorAll('[data-favorite-id]');
            buttons.forEach(btn => {
                const id = Number(btn.getAttribute('data-favorite-id'));
                const active = isFavorite(id);
                const heartIcon = btn.querySelector('.fa-heart');
                const textSpan = btn.querySelector('.favorite-btn-text');

                if (active) {
                    btn.classList.add('is-favorited');
                    if (heartIcon) {
                        heartIcon.classList.remove('fa-regular');
                        heartIcon.classList.add('fa-solid', 'text-rose-500');
                    }
                    if (textSpan) {
                        textSpan.innerText = 'لە دڵخوازەکاندایە';
                    }
                } else {
                    btn.classList.remove('is-favorited');
                    if (heartIcon) {
                        heartIcon.classList.remove('fa-solid', 'text-rose-500');
                        heartIcon.classList.add('fa-regular');
                    }
                    if (textSpan) {
                        textSpan.innerText = 'پاشەکەوتکردن لە دڵخوازەکان';
                    }
                }
            });
        }

        let toastTimeout = null;
        function showFavoriteToast(isAdded, title = '') {
            const toast = document.getElementById('favoriteToast');
            const toastMsg = document.getElementById('favoriteToastMessage');
            const toastIcon = document.getElementById('favoriteToastIcon');
            if (!toast) return;

            if (isAdded) {
                toastIcon.innerHTML = '<i class="fa-solid fa-heart text-rose-500"></i>';
                toastMsg.innerText = title ? `«${title}» زیادکرا بۆ دڵخوازەکان ❤️` : 'خانووەکە زیادکرا بۆ لیستی دڵخوازەکانت ❤️';
            } else {
                toastIcon.innerHTML = '<i class="fa-regular fa-heart text-slate-400"></i>';
                toastMsg.innerText = title ? `«${title}» لە دڵخوازەکان لابرا` : 'خانووەکە لە لیستی دڵخوازەکان لابرا';
            }

            toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');

            if (toastTimeout) clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
            }, 3500);
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateFavoriteNavbarCount();
            updateAllFavoriteButtons();
        });
    </script>

    <!-- Favorite Toast Notification -->
    <div id="favoriteToast" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 pointer-events-none transition-all duration-300 max-w-sm w-full px-4 sm:px-0">
        <div class="bg-slate-900/95 text-white px-4 py-3 rounded-2xl shadow-2xl border border-slate-700/60 flex items-center gap-3 backdrop-blur-md">
            <div id="favoriteToastIcon" class="w-8 h-8 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-heart"></i>
            </div>
            <div class="flex-1 text-xs font-bold" id="favoriteToastMessage">
                خانووەکە زیادکرا بۆ لیستی دڵخوازەکانت ❤️
            </div>
            <a href="{{ route('favorites.index') }}" class="text-[11px] font-black text-rose-400 hover:text-rose-300 underline underline-offset-2 shrink-0">
                بینینی لیست
            </a>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
