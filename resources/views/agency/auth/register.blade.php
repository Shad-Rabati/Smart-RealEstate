@extends('layouts.app')

@section('title', 'تۆمارکردنی نووسینگەی نوێ | خانوبەرەی سلێمانی')

@section('content')
<div class="max-w-xl mx-auto px-4 py-12">
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 space-y-6">
        
        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-slate-900 text-white flex items-center justify-center text-2xl mx-auto">
                <i class="fa-solid fa-briefcase"></i>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900">تۆمارکردنی نووسینگەی خانوبەرە</h1>
            <p class="text-xs text-slate-700">بە خۆڕایی هەژماری نووسینگەکەت بکەرەوە و خانووەکانی کرێ لەسەر نەخشەی سلێمانی دابنێ</p>
        </div>

        <form action="{{ route('agency.register.post') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ناوی نووسینگەی خانوبەرە *</label>
                    <input type="text" name="agency_name" value="{{ old('agency_name') }}" required placeholder="بۆ نموونە: نووسینگەی ڕێبین" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ناوی خاوەنکار / نوێنەر *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="ناوی تەواو بنووسە" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">ئیمەیڵی فەرمی *</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="agency@example.com" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ژمارەی مۆبایل *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="0770XXXXXXX" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ژمارەی واتسئاپ (WhatsApp)</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="0770XXXXXXX" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">ناونیشانی نووسینگە لە سلێمانی *</label>
                <input type="text" name="address" value="{{ old('address') }}" required placeholder="بۆ نموونە: سلێمانی، سەرچنار، شەقامی سەرەکی" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">وشەی نهێنی (Password) *</label>
                    <input type="password" name="password" required placeholder="لانیکەم ٦ پیت" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">دووبارەکردنەوەی وشەی نهێنی *</label>
                    <input type="password" name="password_confirmation" required placeholder="دووبارەی بکەرەوە" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm outline-none">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md transition-colors">
                    دروستکردنی هەژمار و چوونەژوورەوە
                </button>
            </div>
        </form>

        <div class="text-center pt-4 border-t border-slate-100 text-xs text-slate-700">
            پێشتر هەژمارت دروستکردووە؟
            <a href="{{ route('agency.login') }}" class="font-bold text-emerald-700 hover:underline">
                چوونەژوورەوە
            </a>
        </div>

    </div>
</div>
@endsection
