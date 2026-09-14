<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We're Chilling! | Elephant House Wonder Hero</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            500: '#ff2975',
                            700: '#a3227d',
                            800: '#8c1d6b',
                            900: '#581044',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full flex items-center justify-center p-4 sm:p-6 antialiased bg-gradient-to-b from-[#1a0524] via-[#0e0214] to-[#08010b] text-white relative overflow-x-hidden select-none">

    <!-- Ambient glowing light orbs -->
    <div class="absolute top-1/6 left-1/2 -translate-x-1/2 w-96 h-96 bg-[#ff2975]/20 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-1/6 left-1/4 w-80 h-80 bg-sky-400/15 rounded-full blur-[100px] pointer-events-none"></div>

    <!-- Floating ice crystals -->
    <span class="absolute top-12 left-10 text-xl opacity-30 select-none animate-pulse">❄️</span>
    <span class="absolute top-24 right-12 text-lg opacity-40 select-none animate-bounce" style="animation-duration: 4s;">✨</span>
    <span class="absolute bottom-20 left-16 text-lg opacity-25 select-none animate-pulse" style="animation-duration: 3s;">⚡</span>
    <span class="absolute bottom-28 right-14 text-xl opacity-35 select-none animate-bounce" style="animation-duration: 5s;">❄️</span>

    <div class="relative z-10 w-full max-w-md bg-white/[0.08] backdrop-blur-3xl border border-white/20 rounded-[2.5rem] p-7 sm:p-8 shadow-[0_25px_70px_-15px_rgba(0,0,0,0.7)] text-center animate-fade-in flex flex-col items-center overflow-hidden">
        <!-- Top subtle rim highlight -->
        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-[#ff2975]/60 to-transparent"></div>

        <!-- Brand Header -->
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-pink-300 font-extrabold text-xs uppercase tracking-wider mb-5">
            <span>🍦</span>
            <span>Elephant House Wonder Hero</span>
        </div>

        <!-- Status Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-500/15 border border-sky-400/30 text-sky-300 font-extrabold text-[11px] uppercase tracking-widest mb-5 shadow-sm">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-400"></span>
            </span>
            <span>System Cool-Down & Tuning</span>
        </div>

        <!-- Visual Hero with Frost Halo -->
        <div class="relative w-32 h-32 mb-4 flex items-center justify-center">
            <div class="absolute inset-0 bg-gradient-to-tr from-[#ff2975]/35 via-sky-400/25 to-amber-300/25 rounded-full blur-2xl animate-pulse"></div>
            <div class="absolute inset-2 bg-white/5 border border-white/20 rounded-full backdrop-blur-md"></div>
            <span class="text-6xl relative select-none animate-bounce" style="animation-duration: 3.5s;">🍨</span>
            <div class="absolute -top-1 -right-1 bg-sky-500/30 border border-sky-300/40 rounded-full w-7 h-7 flex items-center justify-center text-xs shadow-md">
                ❄️
            </div>
            <div class="absolute -bottom-1 -left-1 bg-pink-500/30 border border-pink-300/40 rounded-full w-7 h-7 flex items-center justify-center text-xs shadow-md">
                ✨
            </div>
        </div>

        <!-- Main Headline -->
        <h1 class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-pink-100 to-[#ff75aa] tracking-tight">
            We're Chilling!
        </h1>

        <h2 class="text-xs sm:text-sm font-extrabold text-amber-300 uppercase tracking-wider mt-1.5 flex items-center gap-1.5">
            <span>⚡</span>
            <span>Upgrading Wonder Hero AI</span>
            <span>⚡</span>
        </h2>

        <p class="text-xs sm:text-sm text-slate-300 font-medium max-w-xs mx-auto mt-3 leading-relaxed">
            {{ (isset($exception) && method_exists($exception, 'getMessage') && $exception->getMessage()) ? $exception->getMessage() : 'Our servers are taking a frosty breather to serve up faster, sharper, and even cooler anime transformations. We will be back online in just a few moments!' }}
        </p>

        <!-- Live System Shimmer -->
        <div class="w-full bg-white/[0.06] border border-white/10 rounded-2xl p-3 sm:p-3.5 my-5 text-left shadow-inner">
            <div class="flex items-center justify-between text-[11px] font-bold text-slate-200 mb-2">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>AI Engine Optimization</span>
                </span>
                <span class="text-amber-300 font-black tracking-wider">IN PROGRESS</span>
            </div>
            <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden p-0.5">
                <div class="h-full bg-gradient-to-r from-sky-400 via-[#ff2975] to-amber-300 rounded-full animate-pulse" style="width: 92%;"></div>
            </div>
            <div class="flex items-center justify-between text-[10px] text-slate-400 font-medium mt-2">
                <span>Server Health: Optimal</span>
                <span>All Data Preserved</span>
            </div>
        </div>

        <!-- Action Button - Single, prominent (Admin Sign In removed) -->
        <div class="w-full space-y-3">
            <button onclick="window.location.reload()"
                    class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-[#ff2975] via-[#a3227d] to-[#78165b] hover:brightness-110 active:scale-[0.98] text-white font-black text-xs uppercase tracking-widest shadow-[0_10px_25px_-5px_rgba(255,41,117,0.45)] transition-all flex items-center justify-center gap-2 cursor-pointer">
                <span>🔄 Refresh & Try Again</span>
            </button>

            <p class="text-[11px] text-pink-200/60 font-medium flex items-center justify-center gap-1">
                <span>✨</span>
                <span>Automatically reconnecting when servers are ready</span>
            </p>
        </div>

        <!-- Footer -->
        <p class="text-[10px] text-slate-400 mt-6 tracking-wide">
            Elephant House © {{ date('Y') }} • Ceylon Cold Stores PLC
        </p>
    </div>
</body>
</html>
