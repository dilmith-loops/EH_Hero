<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Flavor Not Found | Elephant House Wonder Hero</title>
    <!-- Elephant House Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('eh-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('eh-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('eh-logo.png') }}">
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
        <!-- Brand Logo / Badge -->
        <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-brand-300 font-extrabold text-xs uppercase tracking-wider mb-6">
            <img src="{{ asset('eh-logo.png') }}" alt="Logo" class="w-5 h-5 object-contain">
            <span>Elephant House Wonder Hero</span>
        </div>

        <!-- 404 Visual Card -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/15 rounded-3xl p-8 sm:p-12 shadow-2xl">
            <div class="relative w-28 h-28 mx-auto mb-6 flex items-center justify-center">
                <div class="absolute inset-0 bg-brand-500/30 rounded-full blur-2xl animate-pulse"></div>
                <span class="text-7xl relative select-none">🍦</span>
            </div>

            <h1 class="text-6xl sm:text-7xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-brand-100 to-brand-500 tracking-tight leading-none">
                404
            </h1>

            <h2 class="text-xl sm:text-2xl font-black text-white mt-3">
                Oops! This Flavor Doesn't Exist
            </h2>

            <p class="text-sm text-slate-300 font-medium max-w-sm mx-auto mt-3 leading-relaxed">
                The page or resource you were looking for seems to have melted away or wandered off to another aisle.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ url('/') }}"
                   class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-gradient-to-r from-brand-500 via-brand-700 to-brand-800 hover:brightness-110 active:scale-[0.98] text-white font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-brand-500/25 transition-all">
                    Return to Portal
                </a>
                <a href="/EH-Hero/"
                   class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 active:scale-[0.98] text-white font-extrabold text-xs uppercase tracking-wider transition-all">
                    Launch Mobile App
                </a>
            </div>
        </div>

        <p class="text-xs text-slate-500 mt-6">
            Elephant House © {{ date('Y') }} • Ceylon Cold Stores PLC
        </p>
    </div>
</body>
</html>
