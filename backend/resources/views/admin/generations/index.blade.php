@extends('admin.layouts.app')

@section('title', 'Generated Anime Gallery')
@section('subtitle', 'Browse, filter, inspect before/after, and download all AI-generated anime hero portraits')

@section('content')
<div class="space-y-6">
    <!-- Filter Bar -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.generations.index') }}" class="w-full flex flex-col sm:flex-row items-center gap-3">
            <!-- Search by user name / phone -->
            <div class="relative w-full sm:w-72">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search participant..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-700 transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <!-- Treat Filter -->
            <select name="treat_id" onchange="this.form.submit()"
                    class="w-full sm:w-56 px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-700 transition-all">
                <option value="">All Wonder Treats</option>
                @foreach($treats as $t)
                    <option value="{{ $t->treat_id }}" {{ request('treat_id') === $t->treat_id ? 'selected' : '' }}>
                        {{ $t->treat_name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-brand-800 hover:bg-brand-900 text-white font-bold text-xs shadow-xs transition-colors">
                Apply Filter
            </button>

            @if(request('treat_id') || request('search'))
                <a href="{{ route('admin.generations.index') }}" class="w-full sm:w-auto text-center px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 font-bold text-xs transition-colors">
                    Reset
                </a>
            @endif
        </form>

        <div class="text-xs text-slate-400 font-bold shrink-0">
            Total: {{ $generations->total() }} Portraits
        </div>
    </div>

    <!-- Gallery Grid -->
    @if($generations->isEmpty())
        <div class="bg-white rounded-3xl p-16 border border-slate-200/80 shadow-xs text-center text-slate-400">
            <span class="text-5xl">🎨</span>
            <p class="text-sm font-bold mt-3 text-slate-700">No generated images match your criteria.</p>
            <p class="text-xs mt-1">Try resetting the filters or generate portraits from the mobile application.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($generations as $gen)
                <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <!-- Image Container with Badges -->
                        <div class="relative aspect-square bg-slate-100 overflow-hidden cursor-pointer"
                             onclick="openModal({{ json_encode([
                                 'id' => $gen->id,
                                 'userName' => $gen->user->name ?? 'Participant',
                                 'treatName' => $gen->treat_name,
                                 'style' => $gen->style_id,
                                 'customPrompt' => $gen->custom_prompt,
                                 'originalUrl' => $gen->original_image_url,
                                 'generatedUrl' => $gen->generated_image_url,
                                 'ipAddress' => $gen->ip_address ?? $gen->user->ip_address ?? 'N/A',
                                 'date' => $gen->created_at->format('M d, Y h:i A'),
                                 'downloadUrl' => route('admin.generations.download', $gen->id),
                             ]) }})">
                            <img src="{{ $gen->generated_image_url }}" alt="{{ $gen->treat_name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                            <!-- Top Treat Badge -->
                            <span class="absolute top-3 left-3 bg-black/70 backdrop-blur-md text-white font-extrabold text-[10px] px-3 py-1 rounded-full shadow-md">
                                🍦 {{ $gen->treat_name }}
                            </span>

                            <!-- Hover Overlay for Compare -->
                            <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="bg-white text-slate-900 font-extrabold text-xs px-3.5 py-2 rounded-xl shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-transform">
                                    🔍 Compare Before / After
                                </span>
                            </div>
                        </div>

                        <!-- Card Info -->
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('admin.users.show', $gen->app_user_id) }}" class="font-extrabold text-xs text-slate-900 hover:text-brand-700 truncate">
                                    {{ $gen->user->name ?? 'Participant' }}
                                </a>
                                <span class="text-[10px] font-semibold text-slate-400">
                                    {{ $gen->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-[10px] font-mono text-slate-400 mt-0.5">
                                IP: {{ $gen->ip_address ?? $gen->user->ip_address ?? 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="px-4 pb-4 pt-1 flex items-center gap-2 border-t border-slate-100 mt-2">
                        <a href="{{ route('admin.generations.download', $gen->id) }}"
                           class="flex-1 text-center py-2 rounded-xl bg-slate-100 hover:bg-brand-700 hover:text-white font-bold text-xs text-slate-700 transition-colors shadow-2xs">
                            Download
                        </a>
                        <form method="POST" action="{{ route('admin.generations.destroy', $gen->id) }}"
                              onsubmit="return confirm('Delete this generation permanently?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors cursor-pointer" title="Delete">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($generations->hasPages())
            <div class="p-4 bg-white rounded-2xl border border-slate-200/80">
                {{ $generations->links() }}
            </div>
        @endif
    @endif
</div>

<!-- Before & After Comparison Lightbox Modal -->
<div id="compareModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden shadow-2xl animate-fade-in" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 id="modalUserName" class="text-base font-black text-slate-900">Participant</h3>
                <p id="modalSub" class="text-xs text-slate-500 font-semibold mt-0.5">Wonder Anime Hero</p>
            </div>
            <button onclick="closeModal()" class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 font-bold text-slate-600 flex items-center justify-center text-sm transition-colors cursor-pointer">
                ✕
            </button>
        </div>

        <!-- Modal Images Comparison Body -->
        <div class="p-6 overflow-y-auto flex-1 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Original Photo -->
                <div class="bg-slate-50 rounded-2xl p-3 border border-slate-200">
                    <span class="block text-center text-xs font-black uppercase tracking-wider text-slate-500 mb-2">Original User Photo</span>
                    <div class="aspect-square bg-slate-200 rounded-xl overflow-hidden flex items-center justify-center">
                        <img id="modalOriginalImg" src="" alt="Original" class="w-full h-full object-cover">
                        <div id="modalNoOriginal" class="hidden text-slate-400 text-xs font-bold text-center p-4">
                            Original image not captured
                        </div>
                    </div>
                </div>

                <!-- Generated Anime Portrait -->
                <div class="bg-brand-50/50 rounded-2xl p-3 border border-brand-200">
                    <span class="block text-center text-xs font-black uppercase tracking-wider text-brand-800 mb-2">AI Anime Hero Portrait</span>
                    <div class="aspect-square bg-slate-900 rounded-xl overflow-hidden flex items-center justify-center">
                        <img id="modalGeneratedImg" src="" alt="Generated" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div id="modalPromptContainer" class="hidden bg-slate-50 rounded-2xl p-4 text-xs">
                <span class="font-bold text-slate-700">Custom Prompt:</span>
                <span id="modalPromptText" class="text-slate-600 italic"></span>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-5 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between">
            <span id="modalDate" class="text-xs text-slate-400 font-semibold"></span>
            <div class="flex items-center gap-3">
                <a id="modalDownloadBtn" href="" class="px-5 py-2.5 rounded-xl bg-brand-800 hover:bg-brand-900 text-white font-bold text-xs shadow-md transition-colors">
                    Download High-Res PNG
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal(data) {
        document.getElementById('modalUserName').innerText = data.userName;
        document.getElementById('modalSub').innerText = data.treatName + ' • IP: ' + data.ipAddress;
        document.getElementById('modalDate').innerText = 'Generated on ' + data.date;
        document.getElementById('modalDownloadBtn').href = data.downloadUrl;

        const origImg = document.getElementById('modalOriginalImg');
        const noOrig = document.getElementById('modalNoOriginal');
        if (data.originalUrl) {
            origImg.src = data.originalUrl;
            origImg.classList.remove('hidden');
            noOrig.classList.add('hidden');
        } else {
            origImg.classList.add('hidden');
            noOrig.classList.remove('hidden');
        }

        document.getElementById('modalGeneratedImg').src = data.generatedUrl;

        const promptBox = document.getElementById('modalPromptContainer');
        if (data.customPrompt) {
            document.getElementById('modalPromptText').innerText = data.customPrompt;
            promptBox.classList.remove('hidden');
        } else {
            promptBox.classList.add('hidden');
        }

        const modal = document.getElementById('compareModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('compareModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('compareModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
</script>
@endsection
