<div class="space-y-8 animate__animated animate__fadeIn">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-display font-bold text-white mb-2">PDF Books Collection</h2>
            <p class="text-sm text-gray-400">Manage learning materials assigned by group and round</p>
        </div>
        <div class="flex items-center gap-4">
            <button wire:click="openForm"
                class="flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/30">
                <i data-lucide="upload-cloud" class="w-5 h-5"></i> Upload PDF Book
            </button>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="glass rounded-3xl p-2 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-2">
            <div class="md:col-span-4 relative group">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 group-focus-within:text-indigo-400 transition-colors"></i>
                <input type="text" wire:model.live.debounce.300ms="searchTitle" placeholder="Search by book title..."
                    class="w-full bg-white/5 border-none rounded-2xl pl-12 pr-4 py-3.5 text-white text-sm focus:ring-2 focus:ring-indigo-500/50">
            </div>
            <div class="md:col-span-3">
                <select wire:model.live="filterRound"
                    class="w-full bg-white/5 border-none rounded-2xl px-4 py-3.5 text-white text-sm focus:ring-2 focus:ring-indigo-500/50">
                    <option value="">All Rounds</option>
                    @foreach($rounds as $round)
                        <option value="{{ $round->id }}">{{ $round->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4">
                <select wire:model.live="filterGroup"
                    class="w-full bg-white/5 border-none rounded-2xl px-4 py-3.5 text-white text-sm focus:ring-2 focus:ring-indigo-500/50">
                    <option value="">All Groups</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-1 flex items-center justify-center">
                <button wire:click="$refresh" class="w-full h-full glass rounded-2xl flex items-center justify-center text-gray-400 hover:text-white hover:bg-indigo-600 transition-all">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Books Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($books as $book)
            <div class="glass-card rounded-3xl p-6 flex flex-col relative group animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.05 }}s">
                <div class="absolute inset-0 bg-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity rounded-3xl"></div>
                
                <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center mb-6">
                    <i data-lucide="book" class="w-8 h-8 text-indigo-400"></i>
                </div>

                <h4 class="text-xl font-medium text-white mb-2 leading-tight">
                    {{ $book->title }}
                </h4>
                
                <div class="flex flex-wrap gap-2 mb-6">
                    <span class="px-3 py-1 bg-white border border-gray-200 text-gray-900 text-xs font-bold uppercase tracking-wider rounded-full shadow-sm">
                        {{ $book->round->name }}
                    </span>
                    <span class="px-3 py-1 bg-white/5 border border-white/5 text-gray-400 text-xs font-bold uppercase tracking-wider rounded-full">
                        {{ $book->group->name }}
                    </span>
                </div>

                <div class="mt-auto flex gap-3 pt-6 border-t border-white/5">
                    <a href="{{ asset($book->file_path) }}" target="_blank"
                       class="flex-1 py-2.5 bg-white/5 hover:bg-white/10 text-white rounded-xl text-sm font-medium transition-all text-center">
                        <i data-lucide="eye" class="w-4 h-4 inline-block mr-1"></i> View
                    </a>
                    <button wire:click="openForm({{ $book->id }})" class="w-10 h-10 bg-white/5 hover:bg-white/10 text-indigo-400 rounded-xl flex items-center justify-center transition-all">
                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                    </button>
                    <button wire:click="delete({{ $book->id }})" wire:confirm="Are you sure you want to delete this book?" class="w-10 h-10 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white rounded-xl flex items-center justify-center transition-all">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full glass-card rounded-3xl p-20 text-center animate__animated animate__fadeIn">
                <div class="w-20 h-20 bg-white/5 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="book-x" class="w-10 h-10 text-gray-600"></i>
                </div>
                <h3 class="text-xl font-display font-bold text-white mb-2">No PDF Books Found</h3>
                <p class="text-gray-500 max-w-xs mx-auto">Click 'Upload PDF Book' to add materials, or adjust filters if applied.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8 flex justify-center custom-pagination">
        {{ $books->links() }}
    </div>

    <!-- Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4" x-data x-init="setTimeout(() => window.lucide && lucide.createIcons(), 50)">
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md" wire:click="closeForm"></div>
            <div class="glass relative z-10 w-full max-w-xl max-h-[90vh] overflow-y-auto rounded-[2.5rem] p-8 lg:p-12 shadow-2xl border-white/10 animate__animated animate__zoomIn animate__faster no-scrollbar">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-3xl font-display font-bold text-gray-900">{{ $editingId ? 'Edit Book Info' : 'Upload PDF Book' }}</h3>
                    </div>
                    <button wire:click="closeForm" class="w-12 h-12 bg-white border border-gray-200 rounded-2xl flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-all">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Book Title / Caption</label>
                        <input type="text" wire:model="title" class="w-full bg-white/5 border-white/10 rounded-2xl px-4 py-4 focus:ring-2 focus:ring-indigo-500/50" placeholder="e.g. Science Part 1">
                        @error('title') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Round Limit</label>
                            <select wire:model="round_id" class="w-full bg-white/5 border-white/10 rounded-2xl px-4 py-4 focus:ring-2 focus:ring-indigo-500/50">
                                <option value="">Select Round</option>
                                @foreach($rounds as $round)
                                    <option value="{{ $round->id }}">{{ $round->name }}</option>
                                @endforeach
                            </select>
                            @error('round_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Group Policy</label>
                            <select wire:model="group_id" class="w-full bg-white/5 border-white/10 rounded-2xl px-4 py-4 focus:ring-2 focus:ring-indigo-500/50">
                                <option value="">Select Group</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                            @error('group_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">PDF File</label>
                        <div class="relative group">
                            <input type="file" wire:model="file_path" accept="application/pdf" class="hidden" id="file_upload">
                            <label for="file_upload" class="flex flex-col items-center justify-center w-full h-32 glass border-dashed border-white/20 rounded-3xl cursor-pointer hover:bg-white/5 transition-all">
                                <i data-lucide="upload-cloud" class="w-8 h-8 text-indigo-400 mb-2"></i>
                                <span class="text-sm font-semibold text-gray-600">Click to {{ $editingId ? 'replace file' : 'upload PDF' }}</span>
                                <span class="text-xs text-gray-400 mt-1">Max size: 20MB</span>
                            </label>
                        </div>
                        <div wire:loading wire:target="file_path" class="text-indigo-400 text-xs font-medium italic mt-2">Uploading file... please wait</div>
                        @error('file_path') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4 pt-6">
                        <button type="button" wire:click="closeForm" class="flex-1 py-4 bg-gray-100/50 rounded-2xl text-sm font-bold text-gray-500 hover:text-gray-900 transition-all border border-gray-200">Cancel</button>
                        <button type="submit" class="flex-[2] py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-sm font-bold transition-all shadow-xl shadow-indigo-600/30" wire:loading.attr="disabled" wire:target="file_path">
                            <span wire:loading.remove wire:target="file_path">{{ $editingId ? 'Save Changes' : 'Upload Book' }}</span>
                            <span wire:loading wire:target="file_path">Wait for upload...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            succeed(({ snapshot, effect }) => {
                setTimeout(() => {
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                }, 50);
            });
        });
    });
</script>
