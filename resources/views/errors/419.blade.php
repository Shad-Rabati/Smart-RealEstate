<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>کاتەکەت بەسەرچوو (419) | خانوبەرەی سلێمانی</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Vazirmatn', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-3xl p-8 border border-slate-200/90 shadow-xl text-center space-y-6">
        <div class="w-20 h-20 mx-auto rounded-3xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600 text-3xl shadow-inner">
            <i class="fa-solid fa-clock-rotate-left animate-pulse"></i>
        </div>

        <div class="space-y-2">
            <span class="inline-block text-[11px] font-black uppercase tracking-wider bg-amber-100 text-amber-800 px-3 py-1 rounded-full">
                کۆدی هەڵە: 419 (Page Expired)
            </span>
            <h1 class="text-2xl font-black text-slate-900">
                کاتەکەت لە پەڕەکەدا بەسەرچوو
            </h1>
            <p class="text-sm text-slate-600 leading-relaxed">
                بەهۆی مانەوە بۆ ماوەیەکی درێژ لەسەر پەڕەکە، دانیشتنەکەت کۆتایی هات. تکایە پەڕەکە نوێبکەرەوە و دووبارە هەوڵبدەرەوە.
            </p>
        </div>

        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
            <button onclick="window.history.back();" class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-rotate-right text-xs"></i>
                <span>گەڕانەوە و هەوڵدانەوە</span>
            </button>
            <a href="{{ url('/') }}" class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-house text-xs"></i>
                <span>پەڕەی سەرەکی</span>
            </a>
        </div>

        <div class="text-[11px] text-slate-600 pt-2 border-t border-slate-100">
            خۆکارانە دەگەڕێیتەوە دوای <span id="countdownTimer" class="font-bold text-emerald-600">٤</span> چرکە...
        </div>
    </div>

    <script>
        let seconds = 4;
        const el = document.getElementById('countdownTimer');
        const interval = setInterval(() => {
            seconds--;
            if (el) el.innerText = seconds;
            if (seconds <= 0) {
                clearInterval(interval);
                if (window.history.length > 1) {
                    window.history.back();
                } else {
                    window.location.href = "{{ route('agency.login') }}";
                }
            }
        }, 1000);
    </script>
</body>
</html>
