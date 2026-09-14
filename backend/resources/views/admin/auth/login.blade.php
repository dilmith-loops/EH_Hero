<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Elephant House Wonder Hero</title>
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
            <div class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-brand-500 to-brand-800 mx-auto flex items-center justify-center text-3xl shadow-xl shadow-brand-500/20 mb-4 ring-4 ring-white/10">
                🍦
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">Elephant House Wonder</h1>
            <p class="text-sm font-semibold text-brand-500 mt-1 uppercase tracking-wider">Hero Admin Management Portal</p>
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
                    <input type="email" name="email" value="{{ old('email', 'admin@elephanthouse.lk') }}" required autofocus
                           class="w-full px-4 py-3 rounded-xl bg-slate-900/60 border border-white/10 text-white placeholder-slate-500 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Password</label>
                    <input type="password" name="password" value="password123" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-900/60 border border-white/10 text-white placeholder-slate-500 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
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

            <div class="mt-6 pt-5 border-t border-white/10 text-center">
                <p class="text-[11px] text-slate-400">
                    Default Credentials: <code class="bg-black/30 px-1.5 py-0.5 rounded text-amber-300">admin@elephanthouse.lk</code> / <code class="bg-black/30 px-1.5 py-0.5 rounded text-amber-300">password123</code>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
