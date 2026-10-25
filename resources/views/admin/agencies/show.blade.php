@extends('layouts.admin')

@section('title', 'وردەکاری نووسینگە: ' . $agency->name)
@section('page_title', 'وردەکاری نووسینگە')

@section('content')
<div class="space-y-6">

    <!-- Agency Header Card -->
    <div class="bg-slate-900/90 rounded-3xl p-6 sm:p-8 border border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <img src="{{ $agency->logo_url }}" alt="{{ $agency->name }}" class="w-20 h-20 rounded-2xl object-cover border border-slate-700 shadow-xl">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h2 class="text-xl font-black text-white">{{ $agency->name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $agency->verified ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-slate-800 text-slate-400' }}">
                        {{ $agency->verified ? '✓ نووسینگەی باوەڕپێکراو' : 'ئاسایی' }}
                    </span>
                </div>
                <div class="text-xs text-slate-400">
                    خاوەنکار: <b class="text-slate-200">{{ $agency->owner_name ?? 'دیارینەکراو' }}</b> • شار: <b class="text-slate-200">{{ $agency->city }}</b>
                </div>
                <div class="text-xs text-slate-500 flex items-center gap-4 pt-1">
                    <span><i class="fa-solid fa-phone text-slate-500 ml-1"></i> {{ $agency->phone }}</span>
                    <span><i class="fa-solid fa-location-dot text-slate-500 ml-1"></i> {{ $agency->address }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2.5 flex-shrink-0">
            <!-- Toggle Verify -->
            <form action="{{ route('admin.agencies.toggle-verify', $agency->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 shadow-md {{ $agency->verified ? 'bg-slate-800 hover:bg-amber-500/20 text-slate-300 hover:text-amber-400' : 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-emerald-600/30' }}">
                    <i class="fa-solid {{ $agency->verified ? 'fa-ban' : 'fa-check' }}"></i>
                    <span>{{ $agency->verified ? 'هەڵپەساردنی پەسەندکردن' : 'پەسەندکردنی نووسینگە' }}</span>
                </button>
            </form>

            <!-- Public Page Link -->
            <a href="{{ route('agencies.show', $agency->slug) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition-colors">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs ml-1"></i> ماڵپەڕ
            </a>

            <!-- Delete Agency -->
            <form action="{{ route('admin.agencies.destroy', $agency->id) }}" method="POST" onsubmit="return confirm('ئایا دڵنیایت لە سڕینەوەی ئەم نووسینگەیە و سەرجەم خانووەکانی؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3.5 py-2 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 text-xs font-bold transition-colors">
                    سڕینەوە
                </button>
            </form>
        </div>
    </div>

    <!-- Credit Management Card (New) -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-900 to-amber-950/40 rounded-3xl p-6 sm:p-7 border border-amber-500/30 shadow-xl space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-800">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-2xl font-black border border-amber-500/30">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-white flex items-center gap-2">
                        <span>بەڕێوەبردنی کریدتی ئەم نووسینگەیە</span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">زیادکردن یان گۆڕینی باڵانسی کریدت دوای وەرگرتنی پارەی پاکێج</p>
                </div>
            </div>

            <!-- Big Current Balance Display -->
            <div class="flex items-center gap-3 px-5 py-2.5 rounded-2xl bg-slate-950/80 border border-amber-500/30">
                <span class="text-xs font-bold text-slate-400">باڵانسی ئێستا:</span>
                <span class="text-2xl font-black text-amber-400 font-mono">{{ $agency->credits }}</span>
                <span class="text-xs text-amber-300 font-bold">کریدت</span>
            </div>
        </div>

        <!-- Add / Set Credits Form -->
        <form action="{{ route('admin.agencies.credits', $agency->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-end">
            @csrf
            
            <div class="sm:col-span-3 space-y-1.5">
                <label class="text-xs font-bold text-slate-300">کردار</label>
                <select name="mode" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:border-amber-500">
                    <option value="add">+ زیادکردنی کریدت (Add)</option>
                    <option value="set">= دانانی باڵانسی دیاریکراو (Set to)</option>
                </select>
            </div>

            <div class="sm:col-span-3 space-y-1.5">
                <label class="text-xs font-bold text-slate-300">ژمارەی کریدت</label>
                <input type="number" min="0" max="10000" name="amount" value="5" required
                    class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs font-mono font-bold focus:outline-none focus:border-amber-500">
            </div>

            <div class="sm:col-span-4 space-y-1.5">
                <label class="text-xs font-bold text-slate-300">تێبینی / هۆکار (ئارەزوومەندانە)</label>
                <input type="text" name="note" placeholder="بۆ نموونە: پاکێجی ١٥ کریدت بە FastPay درا"
                    class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:border-amber-500">
            </div>

            <div class="sm:col-span-2">
                <button type="submit" class="w-full py-2 px-4 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs shadow-md shadow-amber-500/25 transition-all flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>جێبەجێکردن</span>
                </button>
            </div>
        </form>

        <!-- Recent Transactions Log for this agency -->
        @if($agency->creditTransactions->isNotEmpty())
            <div class="pt-4 border-t border-slate-800 space-y-2">
                <h4 class="text-xs font-black text-slate-300 flex items-center gap-1.5">
                    <i class="fa-solid fa-clock-rotate-left text-amber-400"></i> دوایین مامەڵەکانی کریدت:
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    @foreach($agency->creditTransactions->take(4) as $tx)
                        <div class="p-2.5 rounded-xl bg-slate-950/60 border border-slate-800/80 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-slate-200">{{ $tx->description }}</span>
                                <span class="text-[10px] text-slate-500 block font-mono" dir="ltr">{{ $tx->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                            <span class="font-mono font-black text-sm {{ $tx->amount > 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $tx->amount > 0 ? '+' . $tx->amount : $tx->amount }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Agency Properties Listing -->
    <div class="bg-slate-900/90 rounded-3xl p-6 border border-slate-800 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-black text-white flex items-center gap-2">
                <i class="fa-solid fa-house-chimney text-emerald-400"></i>
                <span>خانووەکانی ئەم نووسینگەیە ({{ $agency->properties->count() }})</span>
            </h3>
        </div>

        <div class="divide-y divide-slate-800">
            @forelse($agency->properties as $prop)
                <div class="py-3.5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <img src="{{ $prop->primary_image_url }}" alt="{{ $prop->title }}" class="w-14 h-14 rounded-xl object-cover border border-slate-800 flex-shrink-0">
                        <div class="min-w-0">
                            <a href="{{ route('properties.show', $prop->slug) }}" target="_blank" class="text-xs font-bold text-white hover:text-emerald-400 block truncate">
                                {{ $prop->title }}
                            </a>
                            <div class="text-[11px] text-slate-400 flex items-center gap-3 mt-1">
                                <span>{{ $prop->neighborhood }}</span>
                                <span>{{ $prop->type_text }}</span>
                                <span class="font-extrabold text-emerald-400">{{ $prop->formatted_price }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 flex-shrink-0">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $prop->status === 'available' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-slate-800 text-slate-400' }}">
                            {{ $prop->status_text }}
                        </span>

                        <form action="{{ route('admin.properties.destroy', $prop->id) }}" method="POST" onsubmit="return confirm('ئایا دڵنیایت لە سڕینەوەی ئەم خانووە؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-xl bg-slate-800 hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 text-xs transition-colors" title="سڕینەوەی خانوو">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center text-xs text-slate-500">هیچ خانوویەک لەم نووسینگەیە تۆمار نەکراوە.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
