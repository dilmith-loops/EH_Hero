@extends('admin.layouts.app')

@section('title', 'Participants')
@section('subtitle', 'Manage all registered app users and view their generated anime portraits')

@section('content')
<div class="space-y-6">
    <!-- Action Bar: Search & Export -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="w-full sm:w-96 flex items-center gap-2">
            <div class="relative w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or IP..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-700 transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            @if(request('search'))
                <a href="{{ route('admin.users.index') }}" class="px-3 py-2.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200">
                    Reset
                </a>
            @endif
        </form>

        <!-- Export CSV Button -->
        <a href="{{ route('admin.users.export') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-brand-800 to-brand-700 hover:brightness-105 text-white font-bold text-xs uppercase tracking-wider shadow-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            <span>Export to CSV</span>
        </a>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        @if($users->isEmpty())
            <div class="py-16 text-center text-slate-400">
                <span class="text-4xl">👥</span>
                <p class="text-xs font-bold mt-2">No participants found.</p>
                @if(request('search'))
                    <p class="text-[11px] mt-1">Try searching with a different name or phone number.</p>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/70 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Participant Name</th>
                            <th class="px-6 py-4">IP Address</th>
                            <th class="px-6 py-4 text-center">Generations</th>
                            <th class="px-6 py-4">Registered Date</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-black text-slate-400">#{{ $user->id }}</td>
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-brand-50 text-brand-800 font-black flex items-center justify-center text-xs">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="hover:text-brand-700 hover:underline">
                                            {{ $user->name }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200/60">
                                        {{ $user->ip_address ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-black {{ $user->generations_count > 0 ? 'bg-brand-50 text-brand-800' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $user->generations_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-medium">
                                    {{ $user->created_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold text-xs transition-colors">
                                            View
                                        </a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete this user and all their generations?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 font-bold text-xs transition-colors cursor-pointer">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $users->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
