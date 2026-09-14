@extends('admin.layouts.app')

@section('title', $user->name)
@section('subtitle', 'Participant Profile & Generated Anime Gallery')

@section('content')
<div class="space-y-6">
    <!-- User Info Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-500 to-brand-800 text-white font-black flex items-center justify-center text-2xl shadow-lg shadow-brand-500/20">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="text-xl font-black text-slate-900">{{ $user->name }}</h3>
                <div class="flex items-center gap-3 text-xs text-slate-500 font-semibold mt-1">
                    <span>📱 {{ $user->phone }}</span>
                    <span>•</span>
                    <span>🌐 IP: {{ $user->ip_address ?? 'N/A' }}</span>
                    <span>•</span>
                    <span>Registered {{ $user->created_at->format('M d, Y h:i A') }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-bold transition-all">
                ← Back to List
            </a>
            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Delete this user and all generations?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-bold transition-all cursor-pointer">
                    Delete User
                </button>
            </form>
        </div>
    </div>

    <!-- Gallery of Generations for this user -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
        <h4 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
            <span>🎨</span>
            <span>Generated Anime Portraits ({{ $user->generations->count() }})</span>
        </h4>

        @if($user->generations->isEmpty())
            <div class="py-12 text-center text-slate-400">
                <span class="text-4xl">🍦</span>
                <p class="text-xs font-bold mt-2">No generations recorded for this participant.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($user->generations as $gen)
                    <div class="bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition-shadow">
                        <div class="relative aspect-square bg-slate-200">
                            <img src="{{ $gen->generated_image_url }}" alt="{{ $gen->treat_name }}" class="w-full h-full object-cover">
                            <span class="absolute top-2 left-2 bg-black/60 backdrop-blur-md text-white px-2.5 py-1 rounded-full text-[10px] font-bold">
                                {{ $gen->treat_name }}
                            </span>
                        </div>
                        <div class="p-4 space-y-3">
                            <div class="text-[11px] text-slate-500 font-semibold">
                                <p>Created {{ $gen->created_at->format('M d, Y h:i A') }}</p>
                                @if($gen->custom_prompt)
                                    <p class="text-slate-400 mt-0.5 truncate">Prompt: "{{ $gen->custom_prompt }}"</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.generations.download', $gen->id) }}" class="flex-1 text-center py-2 px-3 rounded-xl bg-brand-700 hover:bg-brand-800 text-white font-bold text-xs shadow-xs transition-colors">
                                    Download PNG
                                </a>
                                <form method="POST" action="{{ route('admin.generations.destroy', $gen->id) }}" onsubmit="return confirm('Delete this generation?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 transition-colors cursor-pointer" title="Delete">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
