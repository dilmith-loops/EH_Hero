@extends('admin.layouts.app')

@section('title', 'IP Limitation & Security Settings')
@section('subtitle', 'Configure generation caps per IP address, toggle limits on/off, and manage abuse controls')

@section('content')
<div class="space-y-6 max-w-5xl">

    <!-- Top Status Banner -->
    <div class="rounded-3xl p-6 border {{ $limitEnabled ? 'bg-gradient-to-r from-emerald-500/10 via-brand-50 to-emerald-50 border-emerald-300' : 'bg-slate-100 border-slate-200' }} flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-xs">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-sm {{ $limitEnabled ? 'bg-emerald-500 text-white' : 'bg-slate-300 text-slate-600' }}">
                {{ $limitEnabled ? '🛡️' : '⚠️' }}
            </div>
            <div>
                <h3 class="text-base font-black text-slate-900">
                    IP Generation Limit is {{ $limitEnabled ? 'ACTIVE & ENFORCED' : 'CURRENTLY DISABLED' }}
                </h3>
                <p class="text-xs text-slate-500 font-semibold mt-0.5">
                    @if($limitEnabled)
                        Each IP is restricted to a maximum of <span class="font-extrabold text-brand-700">{{ $limitMax }} generation{{ $limitMax > 1 ? 's' : '' }}</span> ({{ ucfirst($limitPeriod) }}).
                    @else
                        Participants can generate unlimited anime portraits without restriction.
                    @endif
                </p>
            </div>
        </div>

        <div>
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider {{ $limitEnabled ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-200 text-slate-700' }}">
                <span class="w-2 h-2 rounded-full {{ $limitEnabled ? 'bg-emerald-600 animate-pulse' : 'bg-slate-400' }}"></span>
                {{ $limitEnabled ? 'ON' : 'OFF' }}
            </span>
        </div>
    </div>

    <!-- Main Settings Form & Info Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Settings Form -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider mb-6 flex items-center gap-2">
                <span>⚙️</span>
                <span>Generation Limit Configuration</span>
            </h3>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf

                <!-- Toggle Switch: Enable / Disable -->
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

                <div class="pt-2">
                    <button type="submit"
                            class="w-full sm:w-auto px-8 py-3 rounded-2xl bg-gradient-to-r from-brand-800 to-brand-700 hover:brightness-105 active:scale-[0.98] text-white font-extrabold text-xs uppercase tracking-wider shadow-md transition-all cursor-pointer">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Side: IP Snapshot & Top Generating IPs -->
        <div class="space-y-6">
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
        </div>
    </div>
</div>
@endsection
