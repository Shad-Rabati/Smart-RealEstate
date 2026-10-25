@extends('layouts.app')

@section('title', 'ڕێکخستنی پرۆفایلی نووسینگە | خانوبەرەی سلێمانی')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900">ڕێکخستنی زانیارییەکانی نووسینگە</h1>
            <p class="text-xs text-slate-700">ئەم زانیارییانە بۆ کڕیاران و کرێچییەکان دەردەکەوێت</p>
        </div>

        <a href="{{ route('agency.dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
            ← گەڕانەوە بۆ پانێڵ
        </a>
    </div>

    <form action="{{ route('agency.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 space-y-5">
        @csrf
        @method('PUT')

        <div class="flex items-center gap-5 pb-5 border-b border-slate-100">
            <img src="{{ $agency->logo_url }}" alt="{{ $agency->name }}" class="w-20 h-20 rounded-2xl object-cover border border-slate-200">
            <div>
                <h3 class="font-bold text-slate-900 text-sm">{{ $agency->name }}</h3>
                <span class="text-xs text-slate-600">لۆگۆی ئێستای نووسینگە</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">ناوی نووسینگە *</label>
                <input type="text" name="name" value="{{ old('name', $agency->name) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">ناوی خاوەنکار / نوێنەر</label>
                <input type="text" name="owner_name" value="{{ old('owner_name', $agency->owner_name) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">ژمارەی پەیوەندی (مۆبایل) *</label>
                <input type="text" name="phone" value="{{ old('phone', $agency->phone) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">ژمارەی واتسئاپ (WhatsApp)</label>
                <input type="text" name="whatsapp" value="{{ old('whatsapp', $agency->whatsapp) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm outline-none">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1">ناونیشانی نووسینگە لە سلێمانی *</label>
                <input type="text" name="address" value="{{ old('address', $agency->address) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">بارکردنی لۆگۆی نوێ</label>
                <input type="file" name="logo" accept="image/*" class="w-full text-xs text-slate-600 file:mr-0 file:ml-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">یان بەستەری لۆگۆ (Image URL)</label>
                <input type="url" name="logo_url" value="{{ old('logo_url') }}" placeholder="https://..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs outline-none">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1">دەربارەی نووسینگە (Bio)</label>
                <textarea name="description" rows="4" class="w-full p-4 rounded-xl border border-slate-300 text-sm outline-none">{{ old('description', $agency->description) }}</textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
            <button type="submit" class="px-8 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md transition-colors">
                پاشەکەوتکردنی گۆڕانکارییەکان
            </button>
        </div>
    </form>

</div>
@endsection
