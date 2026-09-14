@extends('admin.layouts.app')

@section('title', 'Create Admin User')
@section('subtitle', 'Add a new authorized administrator to the Wonder Hero management portal')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Back Link -->
    <div>
        <a href="{{ route('admin.admins.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-brand-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Back to Admin Accounts</span>
        </a>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3 pb-5 border-b border-slate-100 mb-6">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-brand-700 to-brand-900 text-white flex items-center justify-center text-lg font-black shadow-xs">
                👤
            </div>
            <div>
                <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">New Administrator Details</h3>
                <p class="text-xs text-slate-400">All fields are required. Minimum password length is 8 characters.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold space-y-1">
                @foreach($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.admins.store') }}" class="space-y-5">
            @csrf

            <!-- Full Name -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Ruwan Silva" required autofocus
                       class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-700 transition-all">
            </div>

            <!-- Email Address -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g. ruwan@elephanthouse.lk" required
                       class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-700 transition-all">
            </div>

            <!-- Password with Eye Toggle -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Password</label>
                <div class="relative">
                    <input type="password" id="admin_password" name="password" placeholder="At least 8 characters" required
                           class="w-full pl-4 pr-11 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-700 transition-all">
                    <button type="button" onclick="togglePassword('admin_password', this)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 p-1 transition-colors cursor-pointer"
                            aria-label="Toggle password visibility">
                        <svg class="w-4 h-4 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg class="w-4 h-4 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Confirm Password with Eye Toggle -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Confirm Password</label>
                <div class="relative">
                    <input type="password" id="admin_password_confirmation" name="password_confirmation" placeholder="Re-enter password" required
                           class="w-full pl-4 pr-11 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-700 transition-all">
                    <button type="button" onclick="togglePassword('admin_password_confirmation', this)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 p-1 transition-colors cursor-pointer"
                            aria-label="Toggle password visibility">
                        <svg class="w-4 h-4 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg class="w-4 h-4 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Submit & Cancel Actions -->
            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('admin.admins.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-brand-800 to-brand-700 hover:brightness-105 active:scale-[0.98] text-white font-bold text-xs uppercase tracking-wider shadow-md transition-all cursor-pointer">
                    Save Admin User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword(inputId, button) {
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
@endsection
