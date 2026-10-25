@extends('layouts.app')

@section('title', 'چوونەژوورەوە | خانوبەرەی سلێمانی')

@section('content')
<div class="min-h-[75vh] flex flex-col justify-center items-center px-4 py-10 sm:py-16">
    
    <!-- Top Back Link -->
    <div class="w-full max-w-md mb-4 flex items-center justify-start text-xs">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-emerald-600 font-bold transition-colors">
            <i class="fa-solid fa-arrow-right text-[11px]"></i>
            <span>گەڕانەوە بۆ لاپەڕەی سەرەکی</span>
        </a>
    </div>

    <!-- Main Card -->
    <div class="w-full max-w-md bg-white rounded-3xl p-7 sm:p-9 shadow-xl shadow-slate-200/60 border border-slate-100 space-y-6">
        
        <!-- Header -->
        <div class="text-center space-y-2.5">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white flex items-center justify-center text-2xl mx-auto shadow-md shadow-emerald-500/20">
                <i class="fa-solid fa-user-lock"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">چوونەژوورەوە</h1>
            <p class="text-xs text-slate-600 font-medium">تکایە زانیاری هەژمارەکەت بنووسە بۆ چوونەژوورەوە</p>
        </div>

        <!-- Global Alerts / Errors -->
        @if(session('success'))
            <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-base shrink-0 text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs font-bold flex items-center gap-2.5">
                <i class="fa-solid fa-circle-exclamation text-base shrink-0 text-red-500"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('status'))
            <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-base shrink-0 text-emerald-600"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if($errors->has('email') && !session('error'))
            <div class="p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs font-bold flex items-center gap-2.5">
                <i class="fa-solid fa-circle-exclamation text-base shrink-0 text-red-500"></i>
                <span>{{ $errors->first('email') }}</span>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('agency.login.post') }}" method="POST" id="loginForm" autocomplete="off" class="space-y-4">
            @csrf

            <!-- Email Input -->
            <div>
                <label for="emailInput" class="block text-xs font-black text-slate-700 mb-1.5">
                    ئیمەیڵ (Email)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-regular fa-envelope text-sm"></i>
                    </div>
                    <input 
                        type="email" 
                        name="email" 
                        id="emailInput" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email"
                        dir="ltr"
                        @if(!old('email')) autofocus @endif
                        class="w-full pr-10 pl-4 py-3 rounded-xl border @error('email') border-red-300 bg-red-50/20 @else border-slate-200 bg-slate-50/40 @enderror focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-sm font-semibold text-slate-900 outline-none transition-all placeholder:text-slate-400 text-right" 
                        placeholder="name@example.com"
                    >
                </div>
            </div>

            <!-- Password Input -->
            <div>
                <label for="passwordInput" class="block text-xs font-black text-slate-700 mb-1.5">
                    وشەی نهێنی (Password)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </div>
                    <!-- Hidden field to submit password securely while visible box is cleared instantly -->
                    <input type="hidden" name="password" id="hiddenPassword">
                    <input 
                        type="password" 
                        id="passwordInput" 
                        required 
                        autocomplete="new-password"
                        dir="ltr"
                        @if(old('email')) autofocus @endif
                        class="w-full pr-10 pl-11 py-3 rounded-xl border border-slate-200 bg-slate-50/40 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-sm font-semibold text-slate-900 outline-none transition-all placeholder:text-slate-400 text-right tracking-widest" 
                        placeholder="••••••••"
                    >
                    <button 
                        type="button" 
                        onclick="togglePasswordVisibility()" 
                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
                        title="دەرخستن / شاردنەوەی وشەی نهێنی"
                    >
                        <i id="passwordEyeIcon" class="fa-regular fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between pt-1">
                <label class="inline-flex items-center gap-2 cursor-pointer select-none text-xs font-bold text-slate-600 hover:text-slate-800 transition-colors">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                    <span>لەبیرم مەبە لەم ئامێرەدا</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                id="submitBtn"
                class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-l from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-black text-sm shadow-lg shadow-emerald-600/25 active:scale-[0.99] transition-all flex items-center justify-center gap-2 cursor-pointer"
            >
                <i class="fa-solid fa-arrow-right-to-bracket text-base"></i>
                <span>چوونەژوورەوە بۆ پانێڵ</span>
            </button>
        </form>

        <!-- New Agency Registration Card -->
        <div class="pt-4 border-t border-slate-100">
            <div class="p-4 rounded-2xl bg-gradient-to-r from-emerald-50/90 via-teal-50/60 to-emerald-50/90 border border-emerald-200/80 flex items-center justify-between gap-3 text-right">
                <div>
                    <span class="font-black text-slate-900 block text-xs">خاوەنی نووسینگەی نوێیت؟</span>
                    <span class="text-[11px] text-slate-600 font-medium">تۆماربە و بەئاسانی موڵکەکانت بڵاوبکەرەوە</span>
                </div>
                <a href="{{ route('agency.register') }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs shadow-sm transition-all shrink-0 flex items-center gap-1.5">
                    <i class="fa-solid fa-user-plus text-[11px]"></i>
                    <span>تۆمارکردن</span>
                </a>
            </div>
        </div>

    </div>

    <!-- Security & Support Footer Note -->
    <div class="mt-6 text-center text-[11px] text-slate-600 space-y-1">
        <p>پێویستت بە هاوکاری هەیە؟ پەیوەندی بکە بە پشگیری تەکنیکی</p>
    </div>

</div>

@push('scripts')
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('passwordInput');
        const eyeIcon = document.getElementById('passwordEyeIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordInput.classList.remove('tracking-widest');
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordInput.classList.add('tracking-widest');
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }

    function clearPasswordBox() {
        const visiblePass = document.getElementById('passwordInput');
        const hiddenPass = document.getElementById('hiddenPassword');
        if (visiblePass) visiblePass.value = '';
        if (hiddenPass) hiddenPass.value = '';
    }

    // Instantly wipe visible password upon clicking submit so it never stays in the box
    const loginForm = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');

    if (loginForm) {
        loginForm.addEventListener('submit', function() {
            const visiblePass = document.getElementById('passwordInput');
            const hiddenPass = document.getElementById('hiddenPassword');

            if (visiblePass && hiddenPass) {
                hiddenPass.value = visiblePass.value;
                visiblePass.value = ''; // Wipes instantly on click
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
                submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-base"></i><span>تکایە چاوەڕێبە...</span>';
            }
        });
    }

    // Ensure password box is empty when page is loaded, shown, or returned to after logout
    window.addEventListener('pageshow', clearPasswordBox);
    window.addEventListener('load', clearPasswordBox);
    document.addEventListener('DOMContentLoaded', clearPasswordBox);
    setTimeout(clearPasswordBox, 50);
    setTimeout(clearPasswordBox, 200);
    setTimeout(clearPasswordBox, 500);

    // Keep session alive if tab stays open
    setInterval(() => {
        fetch('/up', { method: 'GET', cache: 'no-cache' }).catch(() => {});
    }, 5 * 60 * 1000);
</script>
@endpush
@endsection
