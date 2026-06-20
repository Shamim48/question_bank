<div class="space-y-8 animate__animated animate__fadeIn">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900 mb-2">My Learning Resources</h2>
            <p class="text-sm text-gray-500">Download PDF books available for your assigned group and round.</p>
        </div>
    </div>

    <!-- Books Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($books as $book)
            <div class="glass-card rounded-3xl p-6 flex flex-col relative group border border-gray-100 hover:border-indigo-200 hover:shadow-md transition-all"
                style="animation-delay: {{ $loop->index * 0.05 }}s">

                <div class="w-16 h-16 rounded-2xl bg-indigo-100 border border-indigo-200 flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:border-indigo-600 transition-all duration-300">
                    <i data-lucide="book-open" class="w-8 h-8 text-indigo-600 group-hover:text-white transition-colors duration-300"></i>
                </div>

                <h4 class="text-lg font-semibold text-gray-900 mb-3 leading-tight">
                    {{ $book->title }}
                </h4>

                <div class="flex flex-wrap gap-2 mb-6">
                    <span class="px-3 py-1 bg-gray-100 border border-gray-200 text-gray-600 text-xs font-bold uppercase tracking-wider rounded-full">
                        {{ $book->round->name }}
                    </span>
                    <span class="px-3 py-1 bg-emerald-100 border border-emerald-200 text-emerald-700 text-xs font-bold uppercase tracking-wider rounded-full">
                        {{ $book->group->name }}
                    </span>
                </div>

                <div class="mt-auto pt-6 border-t border-gray-100" x-data="{ loading: false }" @clear-manual-loaders.window="loading = false">
                    <a href="{{ asset($book->file_path) }}" target="_blank" download
                        @click="loading = true; setTimeout(() => loading = false, 5000)"
                        class="w-full flex justify-center items-center gap-2 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/20">
                        <i data-lucide="download-cloud" class="w-5 h-5" x-show="!loading"></i>
                        <div x-show="loading" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                        <span x-text="loading ? 'Starting...' : 'Download PDF'"></span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full glass-card rounded-3xl p-20 text-center border border-dashed border-gray-200">
                <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="folder-open" class="w-10 h-10 text-gray-300"></i>
                </div>
                <h3 class="text-xl font-display font-bold text-gray-700 mb-2">No Resources Available</h3>
                <p class="text-gray-400 max-w-xs mx-auto">There are no PDF books published for your current group and round yet.</p>
            </div>
        @endforelse
    </div>
</div>
