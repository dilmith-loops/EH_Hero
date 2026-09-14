<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode | Elephant House Wonder Hero</title>
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
<body class="h-full flex items-center justify-center p-4 antialiased bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-brand-950 to-slate-950 text-white">
    <div class="w-full max-w-lg text-center">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-300 font-extrabold text-xs uppercase tracking-wider mb-6">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
            <span>Scheduled Maintenance</span>
        </div>

        <!-- Maintenance Card -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/15 rounded-3xl p-8 sm:p-12 shadow-2xl">
            <div class="relative w-28 h-28 mx-auto mb-6 flex items-center justify-center">
                <div class="absolute inset-0 bg-brand-500/30 rounded-full blur-2xl animate-pulse"></div>
                <span class="text-7xl relative select-none animate-bounce">🍨</span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-tight">
                We're Chilling!
            </h1>

            <p class="text-sm font-bold text-brand-400 mt-1 uppercase tracking-wider">
                Upgrading Elephant House Wonder Hero
            </p>

            <p class="text-xs sm:text-sm text-slate-300 font-medium max-w-md mx-auto mt-4 leading-relaxed">
                {{ (isset($exception) && method_exists($exception, 'getMessage') && $exception->getMessage()) ? $exception->getMessage() : 'Our team is fine-tuning the Wonder Anime AI servers to serve up even cooler transformations. We will be back online shortly!' }}
            </p>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                <button onclick="window.location.reload()"
                        class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-gradient-to-r from-brand-500 via-brand-700 to-brand-800 hover:brightness-110 active:scale-[0.98] text-white font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-brand-500/25 transition-all cursor-pointer">
                    🔄 Refresh Page
                </button>
                <a href="{{ route('admin.login') }}"
                   class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 active:scale-[0.98] text-white font-extrabold text-xs uppercase tracking-wider transition-all">
                    Admin Sign In
                </a>
            </div>
        </div>

        <p class="text-xs text-slate-500 mt-6">
            Elephant House © {{ date('Y') }} • Ceylon Cold Stores PLC
        </p>
    </div>
</body>
</html>
