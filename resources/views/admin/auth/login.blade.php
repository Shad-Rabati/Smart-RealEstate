<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>چوونەژوورەوەی سەرپەرشتیار | خانوبەرەی سلێمانی</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Vazirmatn -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { font-family: 'Vazirmatn', system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Glowing Background Aura -->
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-teal-600/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10 space-y-6">

        <!-- Logo & Header -->
        <div class="text-center space-y-3">
            <div class="inline-flex w-16 h-16 rounded-3xl bg-gradient-to-tr from-emerald-600 via-emerald-500 to-teal-400 items-center justify-center text-white shadow-2xl shadow-emerald-500/30 ring-1 ring-white/20">
                <i class="fa-solid fa-shield-halved text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-white tracking-tight">
                    بەشی بەڕێوەبەری سیستەم
                </h1>
                <p class="text-xs text-slate-400 font-medium mt-1">
                    تکایە زانیارییەکانی هەژماری سەرپەرشتیار (Admin) داخڵ بکە
                </p>
            </div>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900/90 backdrop-blur-2xl rounded-3xl p-8 border border-slate-800 shadow-2xl shadow-black/60 space-y-6">

            <!-- Flash Error -->
            @if(session('error'))
                <div class="p-3.5 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs font-bold flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-400"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="p-3.5 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs font-bold flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-3.5 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs font-bold space-y-1">
                    @foreach($errors->all() as $err)
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation text-rose-400 text-[10px]"></i>
                            <span>{{ $err }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-envelope text-slate-500 text-xs"></i>
                        <span>ئیمەیڵی سەرپەرشتیار</span>
                    </label>
                    <input type="email" id="adminEmailInput" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 rounded-2xl bg-slate-950/80 border border-slate-800 text-white text-xs font-medium focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all placeholder:text-slate-600"
                        placeholder="admin@estate.test" dir="ltr" autocomplete="off">
                </div>

                <!-- Password Input -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-lock text-slate-500 text-xs"></i>
                        <span>وشەی نهێنی (Password)</span>
                    </label>
                    <input type="hidden" name="password" id="adminHiddenPassword">
                    <input type="password" id="adminPasswordInput" required
                        class="w-full px-4 py-3 rounded-2xl bg-slate-950/80 border border-slate-800 text-white text-xs font-medium focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all placeholder:text-slate-600"
                        placeholder="••••••••" dir="ltr" autocomplete="current-password">
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 text-slate-400 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded-lg bg-slate-950 border-slate-800 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-slate-900">
                        <span>لەبیرم مەبە</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="adminSubmitBtn" class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-black text-sm shadow-xl shadow-emerald-600/30 hover:shadow-emerald-600/50 transition-all hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                    <span>چوونەژوورەوە بۆ پانێڵ</span>
                </button>

            </form>

            <script>
                // Instant password wipe upon submit
                const adminForm = document.querySelector('form[action="{{ route('admin.login.post') }}"]');
                const adminSubmitBtn = document.getElementById('adminSubmitBtn');

                if (adminForm) {
                    adminForm.addEventListener('submit', function() {
                        const pw = document.getElementById('adminPasswordInput');
                        const hiddenPw = document.getElementById('adminHiddenPassword');
                        if (pw && hiddenPw) {
                            hiddenPw.value = pw.value;
                            pw.value = ''; // Wipes instantly on click
                        }
                        if (adminSubmitBtn) {
                            adminSubmitBtn.disabled = true;
                            adminSubmitBtn.classList.add('opacity-80', 'cursor-not-allowed');
                            adminSubmitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i><span>تکایە چاوەڕێبە...</span>';
                        }
                    });
                }

                window.addEventListener('pageshow', function() {
                    const pw = document.getElementById('adminPasswordInput');
                    if (pw) pw.value = '';
                });

                // Keep session alive if tab stays open
                setInterval(() => {
                    fetch('/up', { method: 'GET', cache: 'no-cache' }).catch(() => {});
                }, 5 * 60 * 1000);
            </script>

        </div>

        <!-- Back to Website -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="text-xs font-bold text-slate-400 hover:text-emerald-400 transition-colors inline-flex items-center gap-2">
                <span>← گەڕانەوە بۆ ماڵپەڕی سەرەکی</span>
            </a>
        </div>

    </div>

</body>
</html>
