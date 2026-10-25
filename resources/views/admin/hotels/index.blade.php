@extends('layouts.admin')

@section('title', 'بەڕێوەبردنی ئوتێلەکان | پانێڵی سەرپەرشتیار')
@section('page_title', 'بەڕێوەبردنی ئوتێلەکان')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-900">لیستی ئوتێلەکانی سلێمانی</h1>
            <p class="text-xs text-slate-500">لێرەوە دەتوانیت ئوتێلی نوێ زیادبکەیت، دەستکاری بکەیت یان دۆخەکەی چالاک/ناچالاک بکەیت.</p>
        </div>

        <a href="{{ route('admin.hotels.create') }}" class="px-5 py-2.5 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-md transition-all flex items-center gap-2 self-start">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>زیادکردنی ئوتێلی نوێ</span>
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <div>
                <span class="text-[11px] text-slate-500 block font-medium">کۆی ئوتێلەکان</span>
                <span class="text-lg font-black text-slate-900">{{ $stats['total'] }}</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <span class="text-[11px] text-slate-500 block font-medium">ئوتێلی چالاک</span>
                <span class="text-lg font-black text-emerald-600">{{ $stats['active'] }}</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-star"></i>
            </div>
            <div>
                <span class="text-[11px] text-slate-500 block font-medium">٥ ئەستێرە</span>
                <span class="text-lg font-black text-slate-900">{{ $stats['five_star'] }}</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-award"></i>
            </div>
            <div>
                <span class="text-[11px] text-slate-500 block font-medium">تایبەتکراو (Featured)</span>
                <span class="text-lg font-black text-purple-600">{{ $stats['featured'] }}</span>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <form action="{{ route('admin.hotels.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="گەڕان بەپێی ناوی ئوتێل، گەڕەک یان تەلەفۆن..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs outline-none">
            </div>

            <div class="w-40">
                <select name="stars" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs outline-none bg-white">
                    <option value="all">هەموو ئەستێرەکان</option>
                    <option value="5" {{ request('stars') == '5' ? 'selected' : '' }}>٥ ئەستێرە</option>
                    <option value="4" {{ request('stars') == '4' ? 'selected' : '' }}>٤ ئەستێرە</option>
                    <option value="3" {{ request('stars') == '3' ? 'selected' : '' }}>٣ ئەستێرە</option>
                </select>
            </div>

            <div class="w-36">
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs outline-none bg-white">
                    <option value="all">هەموو دۆخەکان</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>چالاک</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>ناچالاک</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800">
                فلتەر
            </button>
            <a href="{{ route('admin.hotels.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs hover:bg-slate-200">
                سڕینەوە
            </a>
        </form>
    </div>

    <!-- Hotels Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">ئوتێل</th>
                        <th class="py-3.5 px-4">ئەستێرە</th>
                        <th class="py-3.5 px-4">نرخی شەوانە</th>
                        <th class="py-3.5 px-4">گەڕەک / شوێن</th>
                        <th class="py-3.5 px-4">پەیوەندی</th>
                        <th class="py-3.5 px-4 text-center">دۆخ</th>
                        <th class="py-3.5 px-4 text-center">کردارەکان</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($hotels as $hotel)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $hotel->primary_image_url }}" alt="{{ $hotel->name }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 shrink-0">
                                    <div>
                                        <div class="font-black text-slate-900 text-xs">
                                            {{ $hotel->name }}
                                            @if($hotel->is_featured)
                                                <span class="inline-block mr-1 text-[10px] font-bold bg-amber-100 text-amber-800 px-1.5 py-0.2 rounded">تایبەت</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('hotels.show', $hotel->slug) }}" target="_blank" class="text-[11px] text-blue-600 hover:underline">
                                            پیشاندان لە ماڵپەڕ ↗
                                        </a>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3 px-4">
                                <span class="text-amber-500 font-bold">{{ str_repeat('★', $hotel->stars) }}</span>
                                <span class="text-[11px] text-slate-500 block">{{ $hotel->stars }} ئەستێرە</span>
                            </td>

                            <td class="py-3 px-4">
                                <span class="font-black text-emerald-600 text-sm">${{ number_format($hotel->price_per_night) }}</span>
                                <span class="text-[10px] text-slate-400 block">/ شەو</span>
                            </td>

                            <td class="py-3 px-4 font-semibold text-slate-700">
                                {{ $hotel->neighborhood }}
                            </td>

                            <td class="py-3 px-4 text-slate-600 font-mono text-[11px]">
                                {{ $hotel->phone }}
                            </td>

                            <td class="py-3 px-4 text-center">
                                <form action="{{ route('admin.hotels.toggle-status', $hotel->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold transition-all {{ $hotel->status === 'active' ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                        {{ $hotel->status === 'active' ? '● چالاک' : '○ ناچالاک' }}
                                    </button>
                                </form>
                            </td>

                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.hotels.edit', $hotel->id) }}" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-amber-100 text-slate-700 hover:text-amber-700 flex items-center justify-center transition-colors" title="دەستکاری">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>

                                    <form action="{{ route('admin.hotels.destroy', $hotel->id) }}" method="POST" onsubmit="return confirm('دڵنیایت لە سڕینەوەی ئەم ئوتێلە؟');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-100 text-slate-700 hover:text-rose-700 flex items-center justify-center transition-colors" title="سڕینەوە">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">
                                هیچ ئوتێلێک نەدۆزرایەوە.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($hotels->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $hotels->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
