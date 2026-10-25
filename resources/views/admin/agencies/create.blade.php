@extends('layouts.admin')

@section('title', 'زیادکردنی نووسینگەی نوێ')
@section('page_title', 'زیادکردنی نووسینگەی خانوبەرە')

@section('content')
<div class="max-w-4xl space-y-6">

    <!-- Header & Return button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-black text-white">تۆمارکردنی نووسینگەی نوێ بە دەستی</h2>
            <p class="text-xs text-slate-400 mt-1">وەک سەرپەرشتیار دەتوانیت ڕاستەوخۆ هەژمار و زانیاری بۆ هەر نووسینگەیەک دروست بکەیت</p>
        </div>
        <a href="{{ route('admin.agencies.index') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all flex items-center gap-2">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span>گەڕانەوە بۆ لیست</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-900/90 rounded-3xl p-6 sm:p-8 border border-slate-800/80 shadow-xl">
        <form action="{{ route('admin.agencies.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: Agency Info -->
            <div>
                <h3 class="text-sm font-black text-emerald-400 flex items-center gap-2 mb-4 pb-2 border-b border-slate-800">
                    <i class="fa-solid fa-building"></i>
                    <span>زانیارییە سەرەکییەکانی نووسینگە</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Agency Name -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">
                            ناوی نووسینگە <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            placeholder="نموونە: نووسینگەی بەختیاری"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder:text-slate-600 focus:outline-none focus:border-emerald-500 transition-colors">
                        @error('name')
                            <p class="text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Owner Name -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">
                            ناوی خاوەنکار یان بەڕێوەبەر <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="owner_name" value="{{ old('owner_name') }}" required
                            placeholder="نموونە: کاک کاروان ئەحمەد"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder:text-slate-600 focus:outline-none focus:border-emerald-500 transition-colors">
                        @error('owner_name')
                            <p class="text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">
                            ژمارەی مۆبایل / پەیوەندی <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required dir="ltr"
                            placeholder="07701234567"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder:text-slate-600 focus:outline-none focus:border-emerald-500 transition-colors text-right">
                        @error('phone')
                            <p class="text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- WhatsApp -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">
                            ژمارەی واتسئاپ (ئارەزوومەندانە)
                        </label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" dir="ltr"
                            placeholder="07701234567"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder:text-slate-600 focus:outline-none focus:border-emerald-500 transition-colors text-right">
                        @error('whatsapp')
                            <p class="text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="space-y-1.5 sm:col-span-2">
                        <label class="text-xs font-bold text-slate-300">
                            ناونیشانی دەقیقی نووسینگە لە سلێمانی <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="address" value="{{ old('address') }}" required
                            placeholder="نموونە: سلێمانی، بەختیاری، بەرامبەر باخی گشتی"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder:text-slate-600 focus:outline-none focus:border-emerald-500 transition-colors">
                        @error('address')
                            <p class="text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="space-y-1.5 sm:col-span-2">
                        <label class="text-xs font-bold text-slate-300">دەربارەی نووسینگە یان تایبەتمەندییەکان (ئارەزوومەندانە)</label>
                        <textarea name="description" rows="3"
                            placeholder="کورتەیەک دەربارەی ئەزموونی نووسینگەکە و ئەو گەڕەکانەی کاری تێدا دەکەن..."
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder:text-slate-600 focus:outline-none focus:border-emerald-500 transition-colors">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Login Credentials -->
            <div>
                <h3 class="text-sm font-black text-blue-400 flex items-center gap-2 mb-4 pb-2 border-b border-slate-800">
                    <i class="fa-solid fa-key"></i>
                    <span>زانیارییەکانی چوونەژوورەوەی ئەم نووسینگەیە</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">
                            ئیمەیڵی نووسینگە (Login Email) <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required dir="ltr"
                            placeholder="office@estate.test"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder:text-slate-600 focus:outline-none focus:border-emerald-500 transition-colors">
                        @error('email')
                            <p class="text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">
                            وشەی نهێنی چوونەژوورەوە (Password) <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password" required dir="ltr"
                            placeholder="لایەنی کەم ٦ پیت یان ژمارە"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder:text-slate-600 focus:outline-none focus:border-emerald-500 transition-colors">
                        @error('password')
                            <p class="text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Verification & Logo -->
            <div>
                <h3 class="text-sm font-black text-amber-400 flex items-center gap-2 mb-4 pb-2 border-b border-slate-800">
                    <i class="fa-solid fa-certificate"></i>
                    <span>دۆخی پەسەندکردن و لۆگۆ</span>
                </h3>

                <div class="space-y-4">
                    <!-- Verified Checkbox -->
                    <label class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800/80 cursor-pointer hover:border-emerald-500/50 transition-all">
                        <input type="checkbox" name="verified" value="1" checked class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 bg-slate-900 border-slate-700">
                        <div>
                            <span class="text-xs font-black text-white flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-check text-emerald-400"></i>
                                پەسەندکراوی فەرمی بێت (Verified Badge)
                            </span>
                            <span class="text-[11px] text-slate-400 block mt-0.5">بە چالاککردنی ئەمە، باجی باوەڕپێکراوی شین لەسەر پرۆفایل و خانووەکانی ئەم نووسینگەیە دادەنرێت.</span>
                        </div>
                    </label>

                    <!-- Logo Upload -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">لۆگۆی نووسینگە (ئارەزوومەندانە)</label>
                        <input type="file" name="logo" accept="image/*"
                            class="w-full text-xs text-slate-400 file:mr-0 file:ml-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-emerald-400 hover:file:bg-slate-700 cursor-pointer">
                        @error('logo')
                            <p class="text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('admin.agencies.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-colors">
                    پەشیمانبوونەوە
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span>تۆمارکردن و دروستکردنی نووسینگە</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
