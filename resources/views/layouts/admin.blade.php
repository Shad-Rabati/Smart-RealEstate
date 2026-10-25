<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'داشبۆردی سەرپەرشتیار') | خانوبەرەی سلێمانی</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Vazirmatn', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts: Vazirmatn -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Pro/Free Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Vazirmatn', system-ui, -apple-system, sans-serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #0f172a;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #10b981;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex antialiased selection:bg-emerald-500 selection:text-white">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity"></div>

    <!-- Admin Sidebar (Right in RTL) -->
    <aside id="adminSidebar" class="fixed inset-y-0 right-0 z-50 w-72 bg-slate-900/95 backdrop-blur-2xl border-l border-slate-800 flex flex-col transition-transform duration-300 transform translate-x-full lg:translate-x-0">
        
        <!-- Brand Header -->
        <div class="h-[76px] px-6 flex items-center justify-between border-b border-slate-800/80">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 via-emerald-500 to-teal-400 flex items-center justify-center text-white shadow-lg shadow-emerald-500/25 group-hover:scale-105 transition-all">
                    <i class="fa-solid fa-shield-halved text-base"></i>
                </div>
                <div>
                    <div class="text-base font-black text-white tracking-tight flex items-center gap-1.5 leading-none">
                        <span>خانوبەرەی</span>
                        <span class="text-emerald-400">سلێمانی</span>
                    </div>
                    <div class="text-[10px] text-emerald-400/90 font-bold tracking-wider uppercase mt-1 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>سەرپەرشتیاری گشتی (Admin)</span>
                    </div>
                </div>
            </a>
            
            <!-- Mobile Close Button -->
            <button type="button" onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-white text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-1 py-6 px-4 space-y-1.5 overflow-y-auto">
            
            <div class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                بەشەکانی سەرەکی
            </div>

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/60' }}">
                <i class="fa-solid fa-chart-pie text-sm {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-500' }}"></i>
                <span>داشبۆرد و ئامارەکان</span>
            </a>

            <!-- Analytics & Visitors -->
            <a href="{{ route('admin.analytics.index') }}" class="flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.analytics.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/60' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-chart-line text-sm {{ request()->routeIs('admin.analytics.*') ? 'text-white' : 'text-slate-500' }}"></i>
                    <span>ئاماری سەردانیکەران</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-800 text-emerald-400 border border-slate-700 font-mono">
                    {{ number_format(\App\Models\SiteVisit::count()) }}
                </span>
            </a>

            <!-- Agencies Management -->
            <a href="{{ route('admin.agencies.index') }}" class="flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.agencies.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/60' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-building text-sm {{ request()->routeIs('admin.agencies.*') ? 'text-white' : 'text-slate-500' }}"></i>
                    <span>نووسینگەکان</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-800 text-slate-300 border border-slate-700">
                    {{ \App\Models\Agency::count() }}
                </span>
            </a>

            <!-- Properties Management -->
            <a href="{{ route('admin.properties.index') }}" class="flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.properties.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/60' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-house-chimney text-sm {{ request()->routeIs('admin.properties.*') ? 'text-white' : 'text-slate-500' }}"></i>
                    <span>سەرجەم خانووەکان</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-800 text-slate-300 border border-slate-700">
                    {{ \App\Models\Property::count() }}
                </span>
            </a>

            <!-- VIP Properties Showcase Management -->
            <a href="{{ route('admin.vip.index') }}" class="flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.vip.*') ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-black shadow-lg shadow-amber-500/30' : 'text-slate-400 hover:text-amber-300 hover:bg-slate-800/60' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-crown text-sm {{ request()->routeIs('admin.vip.*') ? 'text-slate-950' : 'text-amber-400' }}"></i>
                    <span>خانووە VIPیەکان</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ request()->routeIs('admin.vip.*') ? 'bg-slate-950 text-amber-400' : 'bg-amber-500/15 text-amber-400 border border-amber-500/30' }}">
                    {{ \App\Models\Property::where('is_featured', true)->count() }}
                </span>
            </a>

            <!-- Hotels Management -->
            <a href="{{ route('admin.hotels.index') }}" class="flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.hotels.*') ? 'bg-amber-600 text-white shadow-lg shadow-amber-600/30' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/60' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-hotel text-sm {{ request()->routeIs('admin.hotels.*') ? 'text-white' : 'text-slate-500' }}"></i>
                    <span>ئوتێلەکان</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-800 text-amber-400 border border-slate-700">
                    {{ \App\Models\Hotel::count() }}
                </span>
            </a>

            <div class="pt-5 px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                سیستەم و پارێزراوی
            </div>

            <!-- Settings -->
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/60' }}">
                <i class="fa-solid fa-gear text-sm {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-slate-500' }}"></i>
                <span>ڕێکخستن و وشەی نهێنی</span>
            </a>

            <div class="pt-5 px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                دەرچوون و دۆخی ئاسایی
            </div>

            <!-- View Public Website -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-800/80 transition-all border border-slate-800/80">
                <i class="fa-solid fa-house text-emerald-400 text-sm"></i>
                <span>گەڕانەوە بۆ ماڵپەڕ (وەک بەکارهێنەر)</span>
            </a>

            <!-- Exit Admin Mode (Logout) -->
            <form action="{{ route('admin.logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-3 rounded-2xl text-xs font-bold text-rose-300 hover:text-white bg-rose-500/10 hover:bg-rose-600 border border-rose-500/20 transition-all text-right shadow-sm group">
                    <i class="fa-solid fa-arrow-right-from-bracket text-sm text-rose-400 group-hover:text-white transition-colors"></i>
                    <span>دەرچوون لە سەرپەرشتیاری (دۆخی ئاسایی)</span>
                </button>
            </form>

        </div>

        <!-- Current Admin User Card -->
        <div class="p-4 border-t border-slate-800/80 bg-slate-900/60">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center font-bold text-xs flex-shrink-0">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] text-slate-400 truncate">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <!-- Logout Form -->
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-8 h-8 rounded-xl bg-slate-800 hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 flex items-center justify-center text-xs transition-colors" title="دەرچوون لە سەرپەرشتیاری">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>

    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 lg:mr-72">
        
        <!-- Top Navbar Header -->
        <header class="h-[76px] px-4 sm:px-8 border-b border-slate-800/80 bg-slate-900/50 backdrop-blur-xl flex items-center justify-between sticky top-0 z-30">
            
            <div class="flex items-center gap-3">
                <!-- Mobile Menu Button -->
                <button type="button" onclick="toggleSidebar()" class="lg:hidden w-9 h-9 rounded-xl bg-slate-800 text-slate-300 hover:text-white flex items-center justify-center">
                    <i class="fa-solid fa-bars text-sm"></i>
                </button>

                <div class="flex items-center gap-2 text-xs">
                    <span class="text-slate-400 font-bold">بەشی بەڕێوەبردن</span>
                    <span class="text-slate-600">/</span>
                    <span class="text-emerald-400 font-black">@yield('page_title', 'داشبۆرد')</span>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <span class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>سیستەم چالاکە</span>
                </span>

                <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-300 hover:text-white transition-colors flex items-center gap-1.5 border border-slate-700" title="بینینی ماڵپەڕی سەرەکی">
                    <i class="fa-solid fa-house text-xs text-emerald-400"></i>
                    <span class="hidden sm:inline">ماڵپەڕ</span>
                </a>

                <!-- Direct Exit Button (Return to Normal Mode) -->
                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-rose-500/15 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/30 text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm group" title="دەرچوون لە سەرپەرشتیاری و گەڕانەوە بۆ دۆخی ئاسایی">
                        <i class="fa-solid fa-arrow-right-from-bracket text-xs text-rose-400 group-hover:text-white transition-colors"></i>
                        <span>گەڕانەوە بۆ دۆخی ئاسایی</span>
                    </button>
                </form>
            </div>

        </header>

        <!-- Main Body Content -->
        <main class="flex-1 p-4 sm:p-8 space-y-6">

            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs font-bold flex items-center justify-between gap-3 shadow-lg">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-400 text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs font-bold flex items-center justify-between gap-3 shadow-lg">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-exclamation text-rose-400 text-base"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-400 hover:text-white">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @yield('content')

        </main>

        <!-- Footer -->
        <footer class="p-6 border-t border-slate-800/60 text-center text-xs text-slate-500">
            <span>پلاتفۆرمی خانوبەرەی سلێمانی • بەشی بەڕێوەبەری سیستەم (Admin Panel) © {{ date('Y') }}</span>
        </footer>

    </div>

    <!-- Scripts -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (!sidebar || !backdrop) return;
            sidebar.classList.toggle('translate-x-full');
            backdrop.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
