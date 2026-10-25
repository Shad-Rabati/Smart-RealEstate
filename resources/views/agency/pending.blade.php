@extends('layouts.app')

@section('title', 'چاوەڕوانی پەسەندکردنی نووسینگە | خانوبەرەی سلێمانی')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-lg border border-slate-200/80 text-center space-y-6">
        
        <!-- Glowing Amber Icon -->
        <div class="relative w-20 h-20 mx-auto">
            <div class="absolute inset-0 bg-amber-400/20 rounded-3xl animate-ping opacity-75"></div>
            <div class="relative w-20 h-20 rounded-3xl bg-gradient-to-tr from-amber-500 to-amber-400 text-white flex items-center justify-center text-3xl shadow-lg shadow-amber-500/30">
                <i class="fa-solid fa-hourglass-half animate-pulse"></i>
            </div>
        </div>

        <!-- Main Heading & Notification Message -->
        <div class="space-y-2">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold border border-amber-200/80">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>داواکاری لە ژێر پێداچوونەوەدایە</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                تکایە چاوەڕوان بە
            </h1>
            <p class="text-sm sm:text-base font-bold text-amber-800 max-w-lg mx-auto leading-relaxed">
                لە ماوەیەکی کەمدا لەلایەن سەرپەرشتیارەکانەوە نووسینگەکەت تۆمار و پەسەند دەکرێت.
            </p>
        </div>

        <!-- Explanatory Box -->
        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 text-right space-y-3">
            <div class="flex items-center gap-2 text-xs font-black text-slate-800">
                <i class="fa-solid fa-shield-halved text-emerald-600 text-sm"></i>
                <span>بۆچی نووسینگەکەم پێویستی بە سەلماندنە؟</span>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed font-medium">
                بۆ پاراستنی متمانەی کڕیاران و کرێچییەکانی شاری سلێمانی و دڵنیابوونەوە لە ڕاستی نووسینگەکان، پێویستە سەرپەرشتیاری سیستەم پێداچوونەوە بە زانیارییەکانتدا بکات. تاوەکو سەرپەرشتیار سەلماندنی فەرمیت بۆ نەکات، ئەکاونتەکەت ناتوانێت هیچ خانووبەرەیەک دابنێت یان بڵاوبکاتەوە.
            </p>
            @if($agency)
                <div class="pt-3 border-t border-slate-200/70 grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-slate-600">ناوی نووسینگە:</span>
                        <span class="font-bold text-slate-900">{{ $agency->name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-600">خاوەنکار:</span>
                        <span class="font-bold text-slate-900">{{ $agency->owner_name ?? Auth::user()->name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-600">ژمارەی پەیوەندی:</span>
                        <span class="font-bold text-slate-900" dir="ltr">{{ $agency->phone }}</span>
                    </div>
                    <div>
                        <span class="text-slate-600">دۆخی هەژمار:</span>
                        <span class="font-black text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full text-[11px]">چاوەڕوانی پەسەندکردن</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
            <!-- Refresh Check -->
            <button type="button" onclick="window.location.reload()" class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-rotate-right"></i>
                <span>پشکنینی دۆخی هەژمارەکەم</span>
            </button>

            <!-- WhatsApp Direct Contact -->
            <a href="https://wa.me/9647701234567?text={{ urlencode('سڵاو، نووسینگەی (' . ($agency->name ?? 'خانوبەرە') . ')م تۆمارکردووە لە پلاتفۆرمی خانوبەرەی سلێمانی، تکایە بۆم پەسەند بکەن.') }}" target="_blank"
                class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-[#25D366] hover:bg-[#20ba5a] text-white font-bold text-xs shadow-md transition-all flex items-center justify-center gap-2">
                <i class="fa-brands fa-whatsapp text-sm"></i>
                <span>پەیوەندی بە سەرپەرشتیار (WhatsApp)</span>
            </a>

            <!-- Logout -->
            <form action="{{ route('agency.logout') }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>دەرچوون</span>
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
