@extends('layouts.app')

@section('title', 'باڵانسی کریدت و کڕینی پاکێج | ' . $agency->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Top Breadcrumb & Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('agency.dashboard') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-slate-600 hover:text-emerald-600 transition-colors">
            <i class="fa-solid fa-arrow-right"></i>
            <span>گەڕانەوە بۆ پانێڵی نووسینگە</span>
        </a>

        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black bg-amber-500/10 text-amber-800 border border-amber-500/25">
            <i class="fa-solid fa-coins text-amber-500"></i>
            <span>سیستەمی فەرمی کریدت</span>
        </div>
    </div>

    <!-- Hero Card: Current Credit Balance -->
    <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-amber-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-amber-500/20">
        <div class="absolute -left-12 -top-12 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400/20 text-amber-300 text-xs font-black border border-amber-400/30">
                    <i class="fa-solid fa-crown text-amber-400"></i>
                    <span>نووسینگەی فەرمی {{ $agency->name }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">باڵانسی کریدت و کڕینی پاکێج</h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-xl leading-relaxed">
                    هەر خانوویەک کە بڵاوی دەکەیتەوە تەنها <strong class="text-amber-400 font-black">{{ $settings['credits_per_property'] }} کریدت</strong> لە باڵانسەکەت بەکاردەهێنێت. بە کڕینی کریدت دەتوانیت بەردەوام خانووەکانت لەسەر نەخشەی سلێمانی بڵاو بکەیتەوە.
                </p>
            </div>

            <!-- Big Balance Badge -->
            <div class="bg-slate-950/60 backdrop-blur-md rounded-3xl p-6 border border-amber-500/30 text-center min-w-[220px] shadow-2xl flex-shrink-0">
                <span class="text-[11px] font-bold text-amber-300/80 uppercase tracking-wider block mb-1">کریدتی بەردەست لە هەژمارەکەتدا</span>
                <div class="flex items-center justify-center gap-2">
                    <i class="fa-solid fa-coins text-amber-400 text-3xl animate-pulse"></i>
                    <span class="text-4xl sm:text-5xl font-black text-amber-400 font-mono tracking-tight">{{ $agency->credits }}</span>
                </div>
                <div class="mt-2 text-xs font-bold text-slate-300 pt-2 border-t border-slate-800">
                    <span>توانای دانانی </span>
                    <span class="text-emerald-400 font-black">{{ floor($agency->credits / max(1, $settings['credits_per_property'])) }} خانووت</span>
                    <span> هەیە</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Packages Section -->
    <div class="space-y-4">
        <div>
            <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-sparkles text-amber-500"></i>
                <span>پاکێجە بەردەستەکانی کڕینی کریدت</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">پاکێجێک هەڵبژێرە و لە ڕێگەی واتسئاپەوە داوای بکە تا بە خێرایی باڵانسەکەت بۆ نوێ بکرێتەوە</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Package 1: Bronze (Starter) -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:border-amber-400 hover:shadow-md transition-all flex flex-col justify-between space-y-6 group">
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center text-xl font-bold">
                        <i class="fa-solid fa-cube"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">پاکێجی دەستپێک (Starter)</h3>
                        <p class="text-xs text-slate-500 mt-1">گونجاوە بۆ نووسینگەی بچووک</p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-baseline gap-2">
                        <span class="text-3xl font-black text-slate-900 font-mono">$10</span>
                        <span class="text-xs text-slate-500">/ ٥ کریدت</span>
                    </div>

                    <ul class="space-y-2.5 text-xs text-slate-600">
                        <li class="flex items-center gap-2 text-slate-800 font-bold">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                            <span>دانانی <strong>٥ خانووی تەواو</strong> بە وێنە و نەخشە</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                            <span>دەستبەجێ چالاکبوون لەسەر نەخشەی سلێمانی</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                            <span>پەیوەندی ڕاستەوخۆ بە کڕیاران</span>
                        </li>
                    </ul>
                </div>

                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['admin_whatsapp'] ?? '9647700000000') }}?text={{ urlencode('سڵاو بەڕێزم، دەمەوێت پاکێجی دەستپێک (٥ کریدت بە $١٠) بکڕم بۆ نووسینگەی: ' . $agency->name) }}" target="_blank" class="w-full py-3 px-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs text-center transition-all flex items-center justify-center gap-2 group-hover:scale-[1.02]">
                    <i class="fa-brands fa-whatsapp text-emerald-400 text-base"></i>
                    <span>کڕین لە ڕێگەی واتسئاپ</span>
                </a>
            </div>

            <!-- Package 2: Silver (Most Popular) -->
            <div class="bg-gradient-to-b from-amber-500/10 via-white to-white rounded-3xl p-6 border-2 border-amber-500 shadow-lg relative flex flex-col justify-between space-y-6 group">
                <div class="absolute -top-3.5 right-6 px-3 py-1 rounded-full bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-black text-[10px] tracking-wider uppercase shadow-md">
                    ★ باوترین و پێشنیارکراو
                </div>

                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500 text-slate-950 flex items-center justify-center text-xl font-bold shadow-md shadow-amber-500/20">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">پاکێجی زیوی (Silver Pro)</h3>
                        <p class="text-xs text-slate-500 mt-1">تایبەت بە نووسینگە چالاکەکان</p>
                    </div>

                    <div class="pt-3 border-t border-amber-200/60 flex items-baseline gap-2">
                        <span class="text-3xl font-black text-slate-900 font-mono">$25</span>
                        <span class="text-xs text-slate-500">/ ١٥ کریدت</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">+٢ کریدت دیاری</span>
                    </div>

                    <ul class="space-y-2.5 text-xs text-slate-600">
                        <li class="flex items-center gap-2 text-slate-800 font-bold">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                            <span>دانانی <strong>١٥ خانووی تەواو</strong></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                            <span>کریدتەکان هەرگیز بەسەرناچن (No Expiration)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                            <span>پاڵپشتی خێرای سەرپەرشتیار</span>
                        </li>
                    </ul>
                </div>

                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['admin_whatsapp'] ?? '9647700000000') }}?text={{ urlencode('سڵاو بەڕێزم، دەمەوێت پاکێجی زیوی (١٥ کریدت بە $٢٥) بکڕم بۆ نووسینگەی: ' . $agency->name) }}" target="_blank" class="w-full py-3 px-4 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs text-center transition-all flex items-center justify-center gap-2 shadow-md shadow-amber-500/25 group-hover:scale-[1.02]">
                    <i class="fa-brands fa-whatsapp text-slate-950 text-base"></i>
                    <span>کڕین لە ڕێگەی واتسئاپ</span>
                </a>
            </div>

            <!-- Package 3: Gold VIP -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:border-amber-400 hover:shadow-md transition-all flex flex-col justify-between space-y-6 group">
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 border border-amber-300 flex items-center justify-center text-xl font-bold">
                        <i class="fa-solid fa-crown text-amber-600"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">پاکێجی زێڕین (VIP Business)</h3>
                        <p class="text-xs text-slate-500 mt-1">بۆ نووسینگەی گەورە و کۆمپانیاکان</p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-baseline gap-2">
                        <span class="text-3xl font-black text-slate-900 font-mono">$50</span>
                        <span class="text-xs text-slate-500">/ ٤٠ کریدت</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800">هەرزانترین نرخ</span>
                    </div>

                    <ul class="space-y-2.5 text-xs text-slate-600">
                        <li class="flex items-center gap-2 text-slate-800 font-bold">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                            <span>دانانی <strong>٤٠ خانووی تەواو</strong></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                            <span>کەمترین تێچوو بۆ هەر خانوو</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                            <span>پێشینە لە خزمەتگوزاری و پاڵپشتی</span>
                        </li>
                    </ul>
                </div>

                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['admin_whatsapp'] ?? '9647700000000') }}?text={{ urlencode('سڵاو بەڕێزم، دەمەوێت پاکێجی زێڕین (٤٠ کریدت بە $٥٠) بکڕم بۆ نووسینگەی: ' . $agency->name) }}" target="_blank" class="w-full py-3 px-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs text-center transition-all flex items-center justify-center gap-2 group-hover:scale-[1.02]">
                    <i class="fa-brands fa-whatsapp text-emerald-400 text-base"></i>
                    <span>کڕین لە ڕێگەی واتسئاپ</span>
                </a>
            </div>

        </div>
    </div>

    <!-- Payment Options & Instructions -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div>
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-wallet text-emerald-600"></i>
                <span>شێوازەکانی پارەدان و حەواڵەکردن</span>
            </h3>
            <p class="text-xs text-slate-500 mt-1">تکایە بڕی پارەکە لە ڕێگەی یەکێک لەم شێوازانەی خوارەوە بنێرە، پاشان وەسڵەکە بنێرە بۆ واتسئاپ تا دەستبەجێ باڵانسەکەت زیاد بکرێت.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- FastPay -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> فاستپەی (FastPay)
                    </span>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $settings['fastpay_number'] }}'); alert('ژمارەی FastPay کۆپیکرا!');" class="text-[11px] text-emerald-600 hover:underline font-bold">کۆپیکردن</button>
                </div>
                <div class="text-base font-black text-slate-900 font-mono" dir="ltr">{{ $settings['fastpay_number'] }}</div>
                <span class="text-[10px] text-slate-500 block">بە ناوی سەرپەرشتیاری خانوبەرەی سلێمانی</span>
            </div>

            <!-- FIB Account -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span> بانکی یەکەمی عێراقی (FIB)
                    </span>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $settings['fib_account'] }}'); alert('ژمارەی FIB کۆپیکرا!');" class="text-[11px] text-emerald-600 hover:underline font-bold">کۆپیکردن</button>
                </div>
                <div class="text-xs font-black text-slate-900 font-mono truncate" dir="ltr">{{ $settings['fib_account'] }}</div>
                <span class="text-[10px] text-slate-500 block">حەواڵەی ڕاستەوخۆ لە ڕێگەی ئەپڵیکەیشن</span>
            </div>

            <!-- Cash or Direct Phone -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> پەیوەندی و کاش
                    </span>
                    <a href="tel:{{ $settings['admin_phone'] }}" class="text-[11px] text-emerald-600 hover:underline font-bold">پەیوەندی</a>
                </div>
                <div class="text-base font-black text-slate-900 font-mono" dir="ltr">{{ $settings['admin_phone'] }}</div>
                <span class="text-[10px] text-slate-500 block">پەیوەندی لە کاتژمێر ٩ی بەیانی تا ٩ی شەو</span>
            </div>

        </div>

        @if(!empty($settings['payment_instructions']))
            <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-xs text-amber-900 leading-relaxed">
                <strong class="font-black">ڕێنمایی سەرپەرشتیار:</strong>
                {{ $settings['payment_instructions'] }}
            </div>
        @endif
    </div>

    <!-- Credit Transaction History Log -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
        <div>
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-slate-600"></i>
                <span>مێژووی کردارەکانی باڵانسی کریدت</span>
            </h3>
            <p class="text-xs text-slate-500 mt-1">سەرجەم زیادکردن و کەمکردنەکانی کریدتی نووسینگەکەت بە بەڵگە و کاتەوە</p>
        </div>

        @if($transactions->isEmpty())
            <div class="py-8 text-center text-slate-400 text-xs">
                تا ئێستا هیچ مامەڵەیەکی کریدت تۆمار نەکراوە.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                        <tr>
                            <th class="py-3 px-4">کات و ڕێکەوت</th>
                            <th class="py-3 px-4">جۆری کردار</th>
                            <th class="py-3 px-4">تێبینی و ڕوونکردنەوە</th>
                            <th class="py-3 px-4 text-center">بڕی کریدت</th>
                            <th class="py-3 px-4 text-left">باڵانسی ماوە</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($transactions as $tx)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 whitespace-nowrap font-mono text-[11px] text-slate-500" dir="ltr">
                                    {{ $tx->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($tx->type === 'initial_gift')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700">
                                            <i class="fa-solid fa-gift"></i> دیاری دەستپێک
                                        </span>
                                    @elseif($tx->type === 'property_publish')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                            <i class="fa-solid fa-house-chimney"></i> دانانی خانوو
                                        </span>
                                    @elseif($tx->type === 'admin_topup')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            <i class="fa-solid fa-circle-plus"></i> کڕین / پڕکردنەوە
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                            {{ $tx->type }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-800 font-medium">
                                    {{ $tx->description ?: '-' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-center font-mono font-black text-sm">
                                    @if($tx->amount > 0)
                                        <span class="text-emerald-600">+{{ $tx->amount }}</span>
                                    @else
                                        <span class="text-rose-600">{{ $tx->amount }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-left font-mono font-black text-slate-900">
                                    {{ $tx->balance_after }} کریدت
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
