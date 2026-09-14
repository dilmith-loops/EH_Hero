@extends('admin.layouts.app')

@section('title', 'Dashboard Overview')
@section('subtitle', 'Real-time metrics on Elephant House Wonder Hero participants and AI generated portraits')

@section('content')
<div class="space-y-6">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <!-- Total Users -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Participants</span>
                <span class="w-10 h-10 rounded-2xl bg-brand-50 text-brand-700 flex items-center justify-center text-lg font-black">
                    👥
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ number_format($totalUsers) }}</span>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">+{{ $todayUsers }} today</span>
            </div>
            <p class="text-xs text-slate-400 font-medium mt-2">Registered via mobile number</p>
        </div>

        <!-- Total Generations -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Anime Portraits Created</span>
                <span class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-black">
                    🎨
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ number_format($totalGenerations) }}</span>
                <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">+{{ $todayGenerations }} today</span>
            </div>
            <p class="text-xs text-slate-400 font-medium mt-2">Gemini AI transformations</p>
        </div>

        <!-- Generations Today -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Generations Today</span>
                <span class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center text-lg font-black">
                    ⚡
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ number_format($todayGenerations) }}</span>
            </div>
            <p class="text-xs text-slate-400 font-medium mt-2">Last 24 hours activity</p>
        </div>

        <!-- Top Treat -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Most Popular Wonder Treat</span>
                <span class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg font-black">
                    🍦
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-xl font-black text-brand-800 truncate">{{ $treatStats->first()->treat_name ?? 'N/A' }}</span>
            </div>
            <p class="text-xs text-slate-400 font-medium mt-2">{{ $treatStats->first()->count ?? 0 }} total generations</p>
        </div>
    </div>

    <!-- Middle Section: Treat Popularity Breakdown + Recent Users -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Treat Popularity Breakdown -->
        <div class="lg:col-span-1 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <span>📊</span>
                <span>Treat Popularity</span>
            </h3>

            @if($treatStats->isEmpty())
                <p class="text-xs text-slate-400 py-6 text-center">No treat generations recorded yet.</p>
            @else
                <div class="space-y-4">
                    @foreach($treatStats as $stat)
                        @php
                            $percentage = $totalGenerations > 0 ? round(($stat->count / $totalGenerations) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1.5">
                                <span class="text-slate-800">{{ $stat->treat_name }}</span>
                                <span class="text-brand-700 font-black">{{ $stat->count }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-gradient-to-r from-brand-500 to-brand-800 h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recent Registered Users -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <span>👥</span>
                    <span>Recent Participants</span>
                </h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-brand-700 hover:text-brand-900">View All →</a>
            </div>

            @if($recentUsers->isEmpty())
                <p class="text-xs text-slate-400 py-8 text-center">No participants registered yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                                <th class="pb-3">Name</th>
                                <th class="pb-3">Phone</th>
                                <th class="pb-3 text-center">Generations</th>
                                <th class="pb-3 text-right">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentUsers as $user)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3 font-bold text-slate-900">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="hover:text-brand-700">
                                            {{ $user->name }}
                                        </a>
                                    </td>
                                    <td class="py-3 font-semibold text-slate-600">{{ $user->phone }}</td>
                                    <td class="py-3 text-center">
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-black {{ $user->generations_count > 0 ? 'bg-brand-50 text-brand-800' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $user->generations_count }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right text-slate-400 font-medium">
                                        {{ $user->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Generations Gallery Stream -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span>✨</span>
                <span>Latest AI Generations</span>
            </h3>
            <a href="{{ route('admin.generations.index') }}" class="text-xs font-bold text-brand-700 hover:text-brand-900">Browse Full Gallery →</a>
        </div>

        @if($recentGenerations->isEmpty())
            <div class="py-12 text-center text-slate-400">
                <span class="text-4xl">🍦</span>
                <p class="text-xs font-bold mt-2">No generated images saved yet.</p>
                <p class="text-[11px]">Generate a portrait from the app to see it here!</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4">
                @foreach($recentGenerations as $gen)
                    <div class="group relative rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 aspect-square shadow-xs hover:shadow-lg transition-all">
                        <img src="{{ $gen->generated_image_url }}" alt="{{ $gen->treat_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-2 flex flex-col justify-end text-white">
                            <p class="text-[11px] font-black truncate">{{ $gen->user->name ?? 'User' }}</p>
                            <p class="text-[9px] text-brand-300 font-bold truncate">{{ $gen->treat_name }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
