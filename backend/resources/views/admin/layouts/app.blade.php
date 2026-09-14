<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | Elephant House Wonder Hero</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
                            50: '#fdf2f8',
                            100: '#fce7f3',
                            500: '#ff2975',
                            600: '#e11d48',
                            700: '#a3227d',
                            800: '#8c1d6b',
                            900: '#581044',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50">
    <div class="min-h-screen lg:h-screen lg:overflow-hidden flex flex-col lg:flex-row">
        <!-- Sidebar: Fixed / Sticky on desktop so it never scrolls away -->
        <aside class="w-full lg:w-72 lg:h-screen lg:sticky lg:top-0 bg-slate-900 text-white flex flex-col justify-between shrink-0 shadow-xl border-r border-slate-800 z-40 overflow-y-auto">
            <div>
                <!-- Brand Header -->
                <div class="p-6 border-b border-slate-800/80 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-800 flex items-center justify-center text-xl font-black shadow-lg shadow-brand-500/30">
                            🍦
                        </div>
                        <div>
                            <h1 class="text-base font-extrabold tracking-tight text-white leading-tight">EH Wonder Hero</h1>
                            <p class="text-xs font-semibold text-brand-500 uppercase tracking-wider">Admin Portal</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1.5">
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-brand-800 to-brand-700 text-white shadow-md shadow-brand-900/40' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-brand-800 to-brand-700 text-white shadow-md shadow-brand-900/40' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Participants</span>
                    </a>

                    <a href="{{ route('admin.generations.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all {{ request()->routeIs('admin.generations.*') ? 'bg-gradient-to-r from-brand-800 to-brand-700 text-white shadow-md shadow-brand-900/40' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Generated Images</span>
                    </a>

                    <a href="{{ route('admin.settings.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-gradient-to-r from-brand-800 to-brand-700 text-white shadow-md shadow-brand-900/40' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Settings & Maintenance</span>
                        @if(\App\Models\Setting::isMaintenanceEnabled())
                            <span class="ml-auto px-2 py-0.5 text-[9px] font-black rounded-full bg-amber-500 text-slate-950 uppercase animate-pulse">Pause</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.admins.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all {{ request()->routeIs('admin.admins.*') ? 'bg-gradient-to-r from-brand-800 to-brand-700 text-white shadow-md shadow-brand-900/40' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Admin Accounts</span>
                    </a>
                </nav>
            </div>

            <!-- Footer / Admin User Profile & Logout -->
            <div class="p-4 border-t border-slate-800/80">
                <div class="bg-slate-800/70 rounded-2xl p-3.5 mb-3 flex items-center justify-between">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-9 h-9 rounded-full bg-brand-700 text-white font-black flex items-center justify-center text-xs shrink-0">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email ?? '' }}</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-rose-300 hover:text-white hover:bg-rose-950/50 border border-rose-900/30 transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0 lg:h-screen lg:overflow-y-auto">
            <!-- Top Bar -->
            <header class="bg-white border-b border-slate-200/80 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">@yield('title')</h2>
                    <p class="text-xs text-slate-500 font-medium">@yield('subtitle', 'Manage Elephant House Wonder Hero activity and generated portraits')</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="/EH-Hero/" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-brand-50 text-brand-800 hover:bg-brand-100 transition-colors border border-brand-200">
                        <span>Launch App</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </header>

            <!-- Alerts -->
            @if(session('success'))
                <div class="mx-6 mt-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between animate-fade-in">
                    <div class="flex items-center gap-2">
                        <span>✅</span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="mx-6 mt-6 p-4 rounded-2xl bg-sky-50 border border-sky-200 text-sky-800 text-xs font-bold flex items-center justify-between animate-fade-in">
                    <div class="flex items-center gap-2">
                        <span>ℹ️</span>
                        <span>{{ session('info') }}</span>
                    </div>
                </div>
            @endif

            <!-- Body -->
            <div class="p-6 flex-1">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Customized Modal Dialog Box -->
    <div id="customConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-200" aria-modal="true" role="dialog">
        <div id="customConfirmCard" class="relative w-full max-w-md bg-white rounded-3xl p-6 sm:p-7 shadow-2xl border border-slate-100 transform scale-95 transition-all duration-200 text-center sm:text-left animate-fade-in">
            <!-- Icon and Header -->
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
                <div id="customConfirmIconWrapper" class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-2xl shrink-0 shadow-xs text-rose-600">
                    <span id="customConfirmIcon">⚠️</span>
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <h3 id="customConfirmTitle" class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Confirm Action</h3>
                    <p id="customConfirmMessage" class="text-xs sm:text-sm text-slate-500 mt-1.5 font-medium leading-relaxed">
                        Are you sure you want to proceed?
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex flex-col-reverse sm:flex-row items-center gap-3">
                <button type="button" id="customConfirmCancelBtn"
                        class="w-full sm:w-1/2 py-3 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 active:scale-[0.98] text-slate-700 font-bold text-xs transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="button" id="customConfirmOkBtn"
                        class="w-full sm:w-1/2 py-3 px-4 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 active:scale-[0.98] text-white font-extrabold text-xs shadow-md shadow-rose-600/25 transition-all cursor-pointer">
                    Yes, Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Customized Alert Modal Dialog Box -->
    <div id="customAlertModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-200" aria-modal="true" role="dialog">
        <div id="customAlertCard" class="relative w-full max-w-sm bg-white rounded-3xl p-6 shadow-2xl border border-slate-100 transform scale-95 transition-all duration-200 text-center animate-fade-in">
            <div id="customAlertIconWrapper" class="w-12 h-12 rounded-2xl bg-brand-50 border border-brand-100 flex items-center justify-center text-2xl mx-auto shadow-xs text-brand-700 mb-3">
                <span id="customAlertIcon">ℹ️</span>
            </div>
            <h3 id="customAlertTitle" class="text-base font-black text-slate-900 tracking-tight">Notice</h3>
            <p id="customAlertMessage" class="text-xs sm:text-sm text-slate-500 mt-1.5 font-medium leading-relaxed">
                Message details
            </p>
            <div class="mt-5">
                <button type="button" id="customAlertOkBtn"
                        class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-brand-700 to-brand-800 hover:brightness-110 active:scale-[0.98] text-white font-extrabold text-xs shadow-md shadow-brand-800/25 transition-all cursor-pointer">
                    OK
                </button>
            </div>
        </div>
    </div>

    <script>
        (function() {
            // Confirm Dialog
            let activeConfirmCallback = null;
            const confirmModal = document.getElementById('customConfirmModal');
            const confirmCard = document.getElementById('customConfirmCard');
            const confirmTitleEl = document.getElementById('customConfirmTitle');
            const confirmMessageEl = document.getElementById('customConfirmMessage');
            const confirmOkBtn = document.getElementById('customConfirmOkBtn');
            const confirmCancelBtn = document.getElementById('customConfirmCancelBtn');
            const confirmIconEl = document.getElementById('customConfirmIcon');
            const confirmIconWrapper = document.getElementById('customConfirmIconWrapper');

            window.showConfirmDialog = function(options) {
                return new Promise((resolve) => {
                    const title = options.title || 'Confirm Action';
                    const message = options.message || 'Are you sure you want to proceed?';
                    const confirmText = options.confirmText || 'Yes, Delete';
                    const cancelText = options.cancelText || 'Cancel';
                    const isDanger = options.isDanger !== false;
                    const icon = options.icon || (isDanger ? '⚠️' : '❓');

                    confirmTitleEl.textContent = title;
                    confirmMessageEl.textContent = message;
                    confirmOkBtn.textContent = confirmText;
                    confirmCancelBtn.textContent = cancelText;
                    confirmIconEl.textContent = icon;

                    if (isDanger) {
                        confirmIconWrapper.className = 'w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-2xl shrink-0 shadow-xs text-rose-600';
                        confirmOkBtn.className = 'w-full sm:w-1/2 py-3 px-4 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 active:scale-[0.98] text-white font-extrabold text-xs shadow-md shadow-rose-600/25 transition-all cursor-pointer';
                    } else {
                        confirmIconWrapper.className = 'w-12 h-12 rounded-2xl bg-brand-50 border border-brand-100 flex items-center justify-center text-2xl shrink-0 shadow-xs text-brand-700';
                        confirmOkBtn.className = 'w-full sm:w-1/2 py-3 px-4 rounded-xl bg-gradient-to-r from-brand-700 to-brand-800 hover:brightness-110 active:scale-[0.98] text-white font-extrabold text-xs shadow-md shadow-brand-800/25 transition-all cursor-pointer';
                    }

                    activeConfirmCallback = resolve;
                    confirmModal.classList.remove('opacity-0', 'pointer-events-none');
                    confirmModal.classList.add('opacity-100', 'pointer-events-auto');
                    confirmCard.classList.remove('scale-95');
                    confirmCard.classList.add('scale-100');
                    confirmOkBtn.focus();
                });
            };

            window.closeConfirmDialog = function(result) {
                confirmModal.classList.remove('opacity-100', 'pointer-events-auto');
                confirmModal.classList.add('opacity-0', 'pointer-events-none');
                confirmCard.classList.remove('scale-100');
                confirmCard.classList.add('scale-95');
                if (activeConfirmCallback) {
                    activeConfirmCallback(result);
                    activeConfirmCallback = null;
                }
            };

            confirmOkBtn.addEventListener('click', () => window.closeConfirmDialog(true));
            confirmCancelBtn.addEventListener('click', () => window.closeConfirmDialog(false));
            confirmModal.addEventListener('click', (e) => {
                if (e.target === confirmModal) window.closeConfirmDialog(false);
            });

            // Alert Dialog
            let activeAlertCallback = null;
            const alertModal = document.getElementById('customAlertModal');
            const alertCard = document.getElementById('customAlertCard');
            const alertTitleEl = document.getElementById('customAlertTitle');
            const alertMessageEl = document.getElementById('customAlertMessage');
            const alertOkBtn = document.getElementById('customAlertOkBtn');
            const alertIconEl = document.getElementById('customAlertIcon');

            window.showAlertDialog = function(options) {
                return new Promise((resolve) => {
                    const title = options.title || 'Notice';
                    const message = options.message || '';
                    const icon = options.icon || 'ℹ️';

                    alertTitleEl.textContent = title;
                    alertMessageEl.textContent = message;
                    alertIconEl.textContent = icon;

                    activeAlertCallback = resolve;
                    alertModal.classList.remove('opacity-0', 'pointer-events-none');
                    alertModal.classList.add('opacity-100', 'pointer-events-auto');
                    alertCard.classList.remove('scale-95');
                    alertCard.classList.add('scale-100');
                    alertOkBtn.focus();
                });
            };

            window.closeAlertDialog = function() {
                alertModal.classList.remove('opacity-100', 'pointer-events-auto');
                alertModal.classList.add('opacity-0', 'pointer-events-none');
                alertCard.classList.remove('scale-100');
                alertCard.classList.add('scale-95');
                if (activeAlertCallback) {
                    activeAlertCallback();
                    activeAlertCallback = null;
                }
            };

            alertOkBtn.addEventListener('click', window.closeAlertDialog);
            alertModal.addEventListener('click', (e) => {
                if (e.target === alertModal) window.closeAlertDialog();
            });

            // Keyboard Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    if (!confirmModal.classList.contains('pointer-events-none')) {
                        window.closeConfirmDialog(false);
                    }
                    if (!alertModal.classList.contains('pointer-events-none')) {
                        window.closeAlertDialog();
                    }
                }
            });

            // Universal form submit interceptor for [data-confirm]
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form.dataset.confirmed === 'true') return;

                const confirmMsg = form.getAttribute('data-confirm');
                if (confirmMsg) {
                    e.preventDefault();
                    const title = form.getAttribute('data-confirm-title') || 'Confirm Deletion';
                    const btnText = form.getAttribute('data-confirm-btn') || 'Yes, Delete';
                    const isDanger = form.getAttribute('data-confirm-danger') !== 'false';

                    window.showConfirmDialog({
                        title: title,
                        message: confirmMsg,
                        confirmText: btnText,
                        isDanger: isDanger
                    }).then((confirmed) => {
                        if (confirmed) {
                            form.dataset.confirmed = 'true';
                            form.submit();
                        }
                    });
                }
            });
        })();
    </script>

    @yield('scripts')
</body>
</html>
