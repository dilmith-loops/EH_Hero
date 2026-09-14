@extends('admin.layouts.app')

@section('title', 'Admin Accounts')
@section('subtitle', 'Manage authorized system administrators, change passwords, and grant portal access')

@section('content')
<div class="space-y-6">
    <!-- Success / Error Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-bold flex items-center gap-2 shadow-xs">
            <span>✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center gap-2 shadow-xs">
            <span>⚠️</span>
            <span>{{ session('error') ?? $errors->first() }}</span>
        </div>
    @endif

    <!-- Action Bar: Search & Create -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.admins.index') }}" class="w-full sm:w-96 flex items-center gap-2">
            <div class="relative w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search admin name or email..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-700 transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            @if(request('search'))
                <a href="{{ route('admin.admins.index') }}" class="px-3 py-2.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200">
                    Reset
                </a>
            @endif
        </form>

        <!-- Create New Admin Button -->
        <a href="{{ route('admin.admins.create') }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-brand-800 to-brand-700 hover:brightness-105 text-white font-bold text-xs uppercase tracking-wider shadow-sm transition-all cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Create Admin User</span>
        </a>
    </div>

    <!-- Admins Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        @if($admins->isEmpty())
            <div class="py-16 text-center text-slate-400">
                <span class="text-4xl">🔐</span>
                <p class="text-xs font-bold mt-2">No admin users found.</p>
                @if(request('search'))
                    <p class="text-[11px] mt-1">Try searching with a different keyword.</p>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/70 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Administrator</th>
                            <th class="px-6 py-4">Email Address</th>
                            <th class="px-6 py-4 text-center">Role / Status</th>
                            <th class="px-6 py-4">Account Created</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($admins as $admin)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-700 to-brand-900 text-white font-black flex items-center justify-center text-xs shadow-xs shrink-0">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-extrabold text-slate-900 leading-snug">{{ $admin->name }}</p>
                                            @if($admin->id === Auth::id())
                                                <span class="inline-flex items-center gap-1 text-[10px] font-black text-brand-600 uppercase tracking-wider">
                                                    ● Current Session
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-600">
                                    {{ $admin->email }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-purple-50 border border-purple-200 text-purple-700">
                                        Administrator
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-medium">
                                    {{ $admin->created_at->format('M d, Y • h:i A') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.admins.edit', $admin->id) }}"
                                           class="p-2 rounded-xl text-slate-500 hover:text-brand-700 hover:bg-slate-100 transition-colors"
                                           title="Edit Admin User & Password">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <!-- Delete Button -->
                                        @if($admin->id !== Auth::id())
                                            <form method="POST" action="{{ route('admin.admins.destroy', $admin->id) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete administrator \'{{ addslashes($admin->name) }}\'? This action cannot be undone.');"
                                                  class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                                                        title="Delete Admin">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="p-2 text-slate-300 cursor-not-allowed" title="You cannot delete your own account">
                                                <svg class="w-4 h-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($admins->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $admins->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
