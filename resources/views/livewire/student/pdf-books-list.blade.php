<div class="space-y-8 animate__animated animate__fadeIn">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-display font-bold text-white mb-2">My Learning Resources</h2>
            <p class="text-sm text-gray-400">Download PDF books available for your assigned group and round</p>
        </div>
    </div>

    <!-- Books Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($books as $book)
            <div class="glass-card rounded-3xl p-6 flex flex-col relative group animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.05 }}s">
                <div class="absolute inset-0 bg-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity rounded-3xl"></div>
                
                <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center mb-6">
                    <i data-lucide="book-open" class="w-8 h-8 text-indigo-400"></i>
                </div>

                <h4 class="text-xl font-medium text-white mb-2 leading-tight">
                    {{ $book->title }}
                </h4>
                
                <div class="flex flex-wrap gap-2 mb-6">
                    <span class="px-3 py-1 bg-white/5 border border-white/5 text-gray-400 text-xs font-bold uppercase tracking-wider rounded-full">
                        {{ $book->round->name }}
                    </span>
                    <span class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-wider rounded-full">
                        {{ $book->group->name }}
                    </span>
                </div>

                <div class="mt-auto pt-6 border-t border-white/5" x-data="{ loading: false }" @clear-manual-loaders.window="loading = false">
                    <a href="{{ asset($book->file_path) }}" target="_blank" download @click="loading = true; setTimeout(() => loading = false, 5000)"
                       class="w-full flex justify-center items-center gap-2 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/30 disabled:opacity-50">
                        <i data-lucide="download-cloud" class="w-5 h-5" x-show="!loading"></i>
                        <div x-show="loading" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                        <span x-text="loading ? 'Starting...' : 'Download PDF'"></span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full glass-card rounded-3xl p-20 text-center animate__animated animate__fadeIn">
                <div class="w-20 h-20 bg-white/5 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="folder-open" class="w-10 h-10 text-gray-600"></i>
                </div>
                <h3 class="text-xl font-display font-bold text-white mb-2">No Resources Available</h3>
                <p class="text-gray-500 max-w-xs mx-auto">There are no PDF books published for your current group and round yet.</p>
            </div>
        @endforelse
    </div>
</div>
