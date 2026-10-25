@extends('layouts.admin')

@section('title', 'ڕێکخستنەکانی سەرپەرشتیار')
@section('page_title', 'ڕێکخستن و ئاسایشی سیستەم')

@section('content')
<div class="space-y-6 max-w-4xl">

    <!-- Admin Profile & Security Card -->
    <div class="bg-slate-900/90 rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-400 flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-emerald-500/20">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div>
                <h3 class="text-base font-black text-white">{{ $admin->name }}</h3>
                <p class="text-xs text-slate-400 mt-0.5">{{ $admin->email }} • <span class="text-emerald-400 font-bold">سەرپەرشتیاری گشتی</span></p>
            </div>
        </div>

        <div class="border-t border-slate-800 pt-6">
            <h4 class="text-sm font-black text-white flex items-center gap-2 mb-4">
                <i class="fa-solid fa-key text-emerald-400"></i>
                <span>گۆڕینی وشەی نهێنی سەرپەرشتیار</span>
            </h4>

            <form action="{{ route('admin.settings.password') }}" method="POST" class="space-y-4 max-w-lg">
                @csrf
                @method('PUT')

                <!-- Current Password -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-300">وشەی نهێنی ئێستا</label>
                    <input type="password" name="current_password" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:border-emerald-500" dir="ltr">
                    @error('current_password')
                        <p class="text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-300">وشەی نهێنی نوێ</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:border-emerald-500" dir="ltr">
                    @error('password')
                        <p class="text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-300">دووبارەکردنەوەی وشەی نهێنی نوێ</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:border-emerald-500" dir="ltr">
                </div>

                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-600/30 transition-all">
                    نوێکردنەوەی وشەی نهێنی
                </button>
            </form>
        </div>
    </div>

    <!-- Credit System & Pricing Settings Card -->
    <div class="bg-slate-900/90 rounded-3xl p-6 sm:p-8 border border-amber-500/20 shadow-xl space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/15 text-amber-400 flex items-center justify-center text-xl font-bold border border-amber-500/25">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-white">ڕێکخستنی سیستەمی کریدت و پارەدان</h3>
                    <p class="text-xs text-slate-400 mt-0.5">دیاریکردنی خانووی بێبەرامبەر، نرخی کریدت، و شێوازەکانی حەواڵە لە کوردستان</p>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full text-[10px] font-black bg-amber-500/15 text-amber-300 border border-amber-500/30">
                سیستەمی قازانج و داهات
            </span>
        </div>

        <form action="{{ route('admin.settings.system') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                
                <!-- Initial Free Credits -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-gift text-purple-400"></i>
                        <span>کریدتی بێبەرامبەر بۆ نووسینگەی نوێ (دیاری سەرەتا)</span>
                    </label>
                    <input type="number" min="0" max="100" name="initial_free_credits" value="{{ old('initial_free_credits', $settings['initial_free_credits']) }}" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs font-mono font-bold focus:outline-none focus:border-emerald-500">
                    <p class="text-[11px] text-slate-500">نووسینگە کە تۆمار دەبێت ئەم بڕە کریدتەی بە دیاری پێ دەدرێت (بۆ نموونە: ٢ کریدت).</p>
                </div>

                <!-- Credits Per Property -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-house-chimney text-emerald-400"></i>
                        <span>تێچووی کریدت بۆ دانانی هەر خانوو</span>
                    </label>
                    <input type="number" min="1" max="50" name="credits_per_property" value="{{ old('credits_per_property', $settings['credits_per_property']) }}" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs font-mono font-bold focus:outline-none focus:border-emerald-500">
                    <p class="text-[11px] text-slate-500">بۆ دانانی هەر خانوویەک ئەم بڕە کریدتە لە نووسینگە دەبڕدرێت (بۆ نموونە: ١ کریدت).</p>
                </div>

                <!-- FastPay Number -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-mobile-screen text-rose-400"></i>
                        <span>ژمارەی حیسابی فاستپەی (FastPay)</span>
                    </label>
                    <input type="text" name="fastpay_number" value="{{ old('fastpay_number', $settings['fastpay_number']) }}"
                        placeholder="0770XXXXXXX"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs font-mono focus:outline-none focus:border-emerald-500" dir="ltr">
                </div>

                <!-- FIB Account -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-building-columns text-blue-400"></i>
                        <span>ژمارەی حیسابی بانکی یەکەمی عێراقی (FIB)</span>
                    </label>
                    <input type="text" name="fib_account" value="{{ old('fib_account', $settings['fib_account']) }}"
                        placeholder="IQ00FIB0000000000000000"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs font-mono focus:outline-none focus:border-emerald-500" dir="ltr">
                </div>

                <!-- WhatsApp Number -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-300 flex items-center gap-1.5">
                        <i class="fa-brands fa-whatsapp text-emerald-400"></i>
                        <span>ژمارەی واتسئاپ بۆ کڕینی کریدت و پەیوەندی</span>
                    </label>
                    <input type="text" name="admin_whatsapp" value="{{ old('admin_whatsapp', $settings['admin_whatsapp']) }}"
                        placeholder="0770XXXXXXX"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs font-mono focus:outline-none focus:border-emerald-500" dir="ltr">
                </div>

                <!-- Phone Number -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-phone text-teal-400"></i>
                        <span>ژمارەی تەلەفۆنی پەیوەندی ڕاستەوخۆ</span>
                    </label>
                    <input type="text" name="admin_phone" value="{{ old('admin_phone', $settings['admin_phone']) }}"
                        placeholder="0770XXXXXXX"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs font-mono focus:outline-none focus:border-emerald-500" dir="ltr">
                </div>

                <!-- Instructions -->
                <div class="sm:col-span-2 space-y-1.5">
                    <label class="text-xs font-bold text-slate-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-amber-400"></i>
                        <span>ڕێنمایی و دەقی ڕوونکردنەوەی پارەدان بۆ نووسینگەکان</span>
                    </label>
                    <textarea name="payment_instructions" rows="3"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:border-emerald-500 leading-relaxed">{{ old('payment_instructions', $settings['payment_instructions']) }}</textarea>
                </div>

            </div>

            <div class="pt-4 border-t border-slate-800 flex justify-end">
                <button type="submit" class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>پاشەکەوتکردنی ڕێکخستنەکانی کریدت</span>
                </button>
            </div>
        </form>
    </div>

    <!-- System Info Card -->
    <div class="bg-slate-900/90 rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-4">
        <h4 class="text-sm font-black text-white flex items-center gap-2">
            <i class="fa-solid fa-server text-blue-400"></i>
            <span>زانیاری ژینگە و سێرڤەر</span>
        </h4>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2">
            <div class="bg-slate-950/70 p-3.5 rounded-2xl border border-slate-800 space-y-1">
                <div class="text-[10px] text-slate-500 font-bold uppercase">PHP Version</div>
                <div class="text-xs font-mono font-bold text-emerald-400">{{ $systemInfo['php_version'] }}</div>
            </div>

            <div class="bg-slate-950/70 p-3.5 rounded-2xl border border-slate-800 space-y-1">
                <div class="text-[10px] text-slate-500 font-bold uppercase">Laravel Version</div>
                <div class="text-xs font-mono font-bold text-rose-400">{{ $systemInfo['laravel_version'] }}</div>
            </div>

            <div class="bg-slate-950/70 p-3.5 rounded-2xl border border-slate-800 space-y-1">
                <div class="text-[10px] text-slate-500 font-bold uppercase">Database Driver</div>
                <div class="text-xs font-mono font-bold text-blue-400 uppercase">{{ $systemInfo['database'] }}</div>
            </div>

            <div class="bg-slate-950/70 p-3.5 rounded-2xl border border-slate-800 space-y-1">
                <div class="text-[10px] text-slate-500 font-bold uppercase">Environment</div>
                <div class="text-xs font-mono font-bold text-amber-400 uppercase">{{ $systemInfo['app_env'] }}</div>
            </div>

            <div class="bg-slate-950/70 p-3.5 rounded-2xl border border-slate-800 space-y-1">
                <div class="text-[10px] text-slate-500 font-bold uppercase">Server OS</div>
                <div class="text-xs font-mono font-bold text-purple-400">{{ $systemInfo['server_os'] }}</div>
            </div>

            <div class="bg-slate-950/70 p-3.5 rounded-2xl border border-slate-800 space-y-1">
                <div class="text-[10px] text-slate-500 font-bold uppercase">Base URL</div>
                <div class="text-xs font-mono font-bold text-slate-300 truncate">{{ $systemInfo['app_url'] }}</div>
            </div>
        </div>
    </div>

</div>
@endsection
