@extends('admin.layouts.app')

@section('title', 'Platform Settings & Maintenance')
@section('subtitle', 'Control platform maintenance mode, configure IP generation limits, and preview system pages')

@section('content')
<div class="space-y-6 max-w-5xl">

    <!-- Top Maintenance Status Banner with Quick-Toggle -->
    <div class="rounded-3xl p-6 border {{ $maintenanceMode ? 'bg-gradient-to-r from-amber-500/15 via-rose-500/10 to-amber-50 border-amber-400/80 shadow-md' : 'bg-gradient-to-r from-emerald-500/10 via-brand-50 to-emerald-50 border-emerald-300 shadow-xs' }} flex flex-col md:flex-row items-start md:items-center justify-between gap-5 transition-all">
        <div class="flex items-start sm:items-center gap-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shrink-0 shadow-sm {{ $maintenanceMode ? 'bg-amber-500 text-white animate-pulse' : 'bg-emerald-600 text-white' }}">
                {{ $maintenanceMode ? '🛠️' : '🚀' }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-base sm:text-lg font-black text-slate-900">
                        Platform Maintenance is {{ $maintenanceMode ? 'ACTIVE & PAUSED' : 'OFF (PLATFORM LIVE)' }}
                    </h3>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $maintenanceMode ? 'bg-amber-500 text-slate-950 animate-pulse' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ $maintenanceMode ? 'CHILL MODE' : 'LIVE' }}
                    </span>
                </div>
                <p class="text-xs text-slate-600 font-semibold mt-1 max-w-xl leading-relaxed">
                    @if($maintenanceMode)
                        Public visitors see the custom <span class="font-bold text-amber-900">503 Maintenance page</span>. AI generation and participant registration are paused. Admins remain fully authorized.
                    @else
                        The platform is accepting public traffic, anime generations, and selfie uploads normally.
                    @endif
                </p>
            </div>
        </div>

        <!-- Quick 1-Click Toggle Form -->
        <div class="shrink-0 w-full md:w-auto">
            <form method="POST" action="{{ route('admin.settings.toggle-maintenance') }}">
                @csrf
                @if($maintenanceMode)
                    <button type="submit"
                            class="w-full md:w-auto px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-500 active:scale-[0.98] text-white font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <span>🟢</span>
                        <span>Turn Maintenance OFF (Go Live)</span>
                    </button>
                @else
                    <button type="submit"
                            class="w-full md:w-auto px-6 py-3 rounded-2xl bg-gradient-to-r from-amber-600 to-orange-600 hover:brightness-110 active:scale-[0.98] text-white font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-amber-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <span>🛠️</span>
                        <span>Turn Maintenance ON (Pause Portal)</span>
                    </button>
                @endif
            </form>
        </div>
    </div>

    <!-- Main Settings Form & Info Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Settings Form Column -->
        <div class="lg:col-span-2 space-y-6">
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-bold flex items-center gap-2">
                    <span>✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf

                <!-- SECTION 1: MAINTENANCE MODE -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <span>🛠️</span>
                            <span>Platform Maintenance Mode</span>
                        </h3>
                        <span class="text-[11px] font-bold px-3 py-1 rounded-full {{ $maintenanceMode ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600' }}">
                            {{ $maintenanceMode ? 'Currently Active' : 'Currently Disabled' }}
                        </span>
                    </div>

                    <!-- Maintenance Mode Toggle Switch -->
                    <div class="flex items-center justify-between p-4 rounded-2xl {{ $maintenanceMode ? 'bg-amber-50/60 border border-amber-200' : 'bg-slate-50 border border-slate-200/80' }}">
                        <div>
                            <label for="maintenance_mode" class="text-xs font-black text-slate-900 uppercase tracking-wider block">
                                Enable Maintenance Mode
                            </label>
                            <p class="text-[11px] text-slate-500 font-medium mt-0.5">
                                When switched ON, all public requests receive the custom Wonder Hero 503 screen.
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1"
                                   class="sr-only peer" {{ $maintenanceMode ? 'checked' : '' }}>
                            <div class="w-12 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-600"></div>
                        </label>
                    </div>

                    <!-- Maintenance Custom Notice Message -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                            Custom Maintenance Notice (Displayed to Public)
                        </label>
                        <textarea name="maintenance_message" rows="3"
                                  class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all"
                                  placeholder="Message displayed on the public maintenance screen...">{{ old('maintenance_message', $maintenanceMessage) }}</textarea>
                        <p class="text-[11px] text-slate-400 mt-1">
                            This message will be rendered directly on the 503 page and returned to the mobile app.
                        </p>
                    </div>
                </div>

                <!-- SECTION 2: IP GENERATION LIMIT CONFIGURATION -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <span>🛡️</span>
                            <span>IP Generation Limit Configuration</span>
                        </h3>
                        <span class="text-[11px] font-bold px-3 py-1 rounded-full {{ $limitEnabled ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                            {{ $limitEnabled ? 'Enforced' : 'Disabled' }}
                        </span>
                    </div>

                    <!-- Toggle Switch: Enable / Disable IP Limit -->
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <div>
                            <label for="ip_limit_enabled" class="text-xs font-black text-slate-900 uppercase tracking-wider block">
                                Enforce IP Generation Limit
                            </label>
                            <p class="text-[11px] text-slate-500 font-medium mt-0.5">
                                Turn ON to restrict multiple generations from the same IP address.
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="ip_limit_enabled" id="ip_limit_enabled" value="1"
                                   class="sr-only peer" {{ $limitEnabled ? 'checked' : '' }}>
                            <div class="w-12 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-800"></div>
                        </label>
                    </div>

                    <!-- Numeric Input: Max Generations -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                            Maximum Generations Allowed per IP Address
                        </label>
                        <div class="relative">
                            <input type="number" name="ip_limit_max" min="1" max="1000" value="{{ old('ip_limit_max', $limitMax) }}" required
                                   class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-extrabold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-700 transition-all">
                            <span class="absolute right-4 top-3 text-xs font-bold text-slate-400 uppercase">
                                Photos / User
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1.5">
                            Recommended: <span class="font-bold text-slate-600">1, 2, or 3</span> generations per participant to control Gemini API costs.
                        </p>
                    </div>

                    <!-- Limitation Period: Lifetime vs Daily -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                            Limitation Reset Period
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition-all {{ $limitPeriod === 'lifetime' ? 'bg-brand-50 border-brand-700 text-brand-900 ring-1 ring-brand-700' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                                <input type="radio" name="ip_limit_period" value="lifetime" {{ $limitPeriod === 'lifetime' ? 'checked' : '' }}
                                       class="text-brand-800 focus:ring-brand-700">
                                <div>
                                    <span class="text-xs font-bold block">Lifetime (All-Time)</span>
                                    <span class="text-[10px] text-slate-500">Counts total generations ever created from that IP</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition-all {{ $limitPeriod === 'daily' ? 'bg-brand-50 border-brand-700 text-brand-900 ring-1 ring-brand-700' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                                <input type="radio" name="ip_limit_period" value="daily" {{ $limitPeriod === 'daily' ? 'checked' : '' }}
                                       class="text-brand-800 focus:ring-brand-700">
                                <div>
                                    <span class="text-xs font-bold block">Daily (Per 24 Hours)</span>
                                    <span class="text-[10px] text-slate-500">Resets every midnight Sri Lankan Time (Asia/Colombo)</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Custom Limit Message -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                            Message Shown When Limit is Reached
                        </label>
                        <textarea name="ip_limit_message" rows="2"
                                  class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-700 transition-all"
                                  placeholder="Message shown to participants when they exceed their allowed generation count...">{{ old('ip_limit_message', $limitMessage) }}</textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-1">
                    <button type="submit"
                            class="w-full sm:w-auto px-9 py-3.5 rounded-2xl bg-gradient-to-r from-brand-800 via-brand-700 to-brand-900 hover:brightness-110 active:scale-[0.98] text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-brand-900/30 transition-all cursor-pointer">
                        Save All Platform Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Side: Analytics & Previews Column -->
        <div class="space-y-6">
            <!-- System Status Quick Summary -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
                <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span>📊</span>
                    <span>System Status Summary</span>
                </h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-xs pb-2 border-b border-slate-100">
                        <span class="text-slate-500 font-semibold">Portal Status</span>
                        <span class="font-extrabold {{ $maintenanceMode ? 'text-amber-600' : 'text-emerald-600' }}">
                            {{ $maintenanceMode ? '🛠️ Maintenance' : '🟢 Live' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-xs pb-2 border-b border-slate-100">
                        <span class="text-slate-500 font-semibold">IP Limit Cap</span>
                        <span class="font-extrabold text-slate-800">
                            {{ $limitEnabled ? "{$limitMax} / IP ({$limitPeriod})" : 'Disabled' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-semibold">Server Timezone</span>
                        <span class="font-extrabold text-slate-800">
                            Asia/Colombo (SLT)
                        </span>
                    </div>
                </div>
            </div>

            <!-- Unique IPs Stat Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tracked Client IPs</span>
                <div class="text-3xl font-black text-slate-900 mt-1">
                    {{ number_format($totalDistinctIps) }}
                </div>
                <p class="text-xs text-slate-500 font-medium mt-1">Distinct network addresses monitored</p>
            </div>

            <!-- Top Active IPs -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
                <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                    <span>📡</span>
                    <span>Top Generating IPs</span>
                </h4>

                @if($topIps->isEmpty())
                    <p class="text-xs text-slate-400 py-4 text-center">No IP generation history yet.</p>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($topIps as $ip)
                            <div class="py-2.5 flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-mono font-bold text-slate-800">{{ $ip->ip_address }}</span>
                                    <p class="text-[10px] text-slate-400 font-medium">
                                        {{ \Carbon\Carbon::parse($ip->latest_activity)->timezone('Asia/Colombo')->diffForHumans() }}
                                    </p>
                                </div>
                                <span class="font-black text-brand-800 bg-brand-50 px-2.5 py-0.5 rounded-full">
                                    {{ $ip->count }} gen{{ $ip->count > 1 ? 's' : '' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Error & Maintenance Page Previews -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
                <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                    <span>🎨</span>
                    <span>System Pages & Previews</span>
                </h4>
                <div class="space-y-2">
                    <a href="{{ route('admin.preview.503') }}" target="_blank"
                       class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 hover:bg-amber-50/50 border border-slate-200/80 hover:border-amber-200 transition-all text-xs font-bold text-slate-700 hover:text-amber-800">
                        <span class="flex items-center gap-2">
                            <span>🛠️</span>
                            <span>503 Maintenance Page</span>
                        </span>
                        <span class="text-[10px] text-slate-400">Preview ↗</span>
                    </a>
                    <a href="{{ route('admin.preview.404') }}" target="_blank"
                       class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 hover:bg-brand-50/50 border border-slate-200/80 hover:border-brand-200 transition-all text-xs font-bold text-slate-700 hover:text-brand-800">
                        <span class="flex items-center gap-2">
                            <span>🔍</span>
                            <span>404 Not Found Page</span>
                        </span>
                        <span class="text-[10px] text-slate-400">Preview ↗</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
