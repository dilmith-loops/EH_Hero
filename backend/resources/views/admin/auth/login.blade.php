<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Elephant House Wonder Hero</title>
    <!-- Elephant House Favicon (Data URI guaranteed without 404 or rewrite issues) -->
    <link rel="icon" type="image/png" href="{{ \App\Models\Setting::getLogoDataUrl() }}">
    <link rel="shortcut icon" type="image/png" href="{{ \App\Models\Setting::getLogoDataUrl() }}">
    <link rel="apple-touch-icon" href="{{ \App\Models\Setting::getLogoDataUrl() }}">
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
<body class="h-full flex items-center justify-center p-4 antialiased bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-brand-950 to-slate-950">
    <div class="w-full max-w-md">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 rounded-3xl bg-white/95 p-2 mx-auto flex items-center justify-center shadow-2xl shadow-brand-500/20 mb-4 ring-4 ring-white/10 border border-white/20">
                <img src="{{ \App\Models\Setting::getLogoDataUrl() }}" alt="Elephant House" class="w-full h-full object-contain">
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">Elephant House</h1>
            <p class="text-xs font-extrabold text-brand-500 mt-1 uppercase tracking-widest">IT Admin Management Portal</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl text-white">
            <h2 class="text-lg font-black text-white mb-1">Welcome Back</h2>
            <p class="text-xs text-slate-300 mb-6">Sign in with your administrator credentials.</p>

            @if($errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-500/20 border border-rose-500/40 text-rose-200 text-xs font-bold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@elephanthouse.lk" required autofocus
                           class="w-full px-4 py-3 rounded-xl bg-slate-900/60 border border-white/10 text-white placeholder-slate-500 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="••••••••••••" required
                               class="w-full pl-4 pr-11 py-3 rounded-xl bg-slate-900/60 border border-white/10 text-white placeholder-slate-500 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
                        <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white p-1 transition-colors cursor-pointer" aria-label="Toggle password visibility">
                            <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs py-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-300 font-semibold">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-white/20 bg-slate-900/60">
                        <span>Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-brand-500 via-brand-700 to-brand-800 hover:brightness-110 active:scale-[0.99] text-white font-black text-sm uppercase tracking-wider shadow-lg shadow-brand-500/30 transition-all cursor-pointer mt-2">
                    Sign In to Portal
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const eyeOpen = button.querySelector('.eye-open');
            const eyeClosed = button.querySelector('.eye-closed');
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
