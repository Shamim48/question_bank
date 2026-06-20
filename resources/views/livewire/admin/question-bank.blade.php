<div class="space-y-8 animate__animated animate__fadeIn">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-display font-bold text-white mb-2">Question Bank</h2>
            <p class="text-sm text-gray-400">Manage and organize your MCQ ecosystem</p>
        </div>
        <div class="flex items-center gap-4">
            <button wire:click="openForm" wire:loading.attr="disabled"
                class="flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/30 disabled:opacity-50">
                <i data-lucide="plus-circle" class="w-5 h-5" wire:loading.remove wire:target="openForm"></i>
                <div wire:loading wire:target="openForm" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                New Question
            </button>
        </div>
    </div>

    <!-- Stats Snapshot -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach(['Total' => $questions->total(), 'Text' => $questions->where('type', 'text')->count(), 'Image' => $questions->where('type', 'image')->count(), 'Media' => $questions->whereIn('type', ['audio', 'video'])->count()] as $label => $val)
            <div class="glass flex items-center justify-between p-4 rounded-2xl border-white/5">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ $label }}</span>
                <span class="text-xl font-display font-bold text-indigo-400">{{ $val }}</span>
            </div>
        @endforeach
    </div>

    <!-- Advanced Filters -->
    <div class="glass rounded-3xl p-2 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-2">
            <div class="md:col-span-3 relative group">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 group-focus-within:text-indigo-400 transition-colors"></i>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by content..."
                    class="w-full bg-white/5 border-none rounded-2xl pl-12 pr-4 py-3.5 text-white text-sm focus:ring-2 focus:ring-indigo-500/50">
            </div>
            <div class="md:col-span-2">
                <select wire:model.live="filterRound"
                    class="w-full bg-white/5 border-none rounded-2xl px-4 py-3.5 text-white text-sm focus:ring-2 focus:ring-indigo-500/50">
                    <option value="">All Rounds</option>
                    @foreach($rounds as $round)
                        <option value="{{ $round->id }}">{{ $round->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <select wire:model.live="filterSubject"
                    class="w-full bg-white/5 border-none rounded-2xl px-4 py-3.5 text-white text-sm focus:ring-2 focus:ring-indigo-500/50">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->round->name }})</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <select wire:model.live="filterGroup"
                    class="w-full bg-white/5 border-none rounded-2xl px-4 py-3.5 text-white text-sm focus:ring-2 focus:ring-indigo-500/50">
                    <option value="">All Groups</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <select wire:model.live="filterType"
                    class="w-full bg-white/5 border-none rounded-2xl px-4 py-3.5 text-white text-sm focus:ring-2 focus:ring-indigo-500/50">
                    <option value="">All Types</option>
                    <option value="text">Text</option>
                    <option value="image">Image</option>
                    <option value="audio">Audio</option>
                    <option value="video">Video</option>
                </select>
            </div>
            <div class="md:col-span-1 flex items-center justify-center">
                <button wire:click="$refresh" class="w-full h-full glass rounded-2xl flex items-center justify-center text-gray-400 hover:text-white hover:bg-indigo-600 transition-all">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Questions Grid -->
    <div class="space-y-4">
        @forelse($questions as $question)
            <div class="glass-card rounded-3xl p-6 lg:p-8 animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.05 }}s">
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Badge & Content Section -->
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-6">
                            @php
                                $typeColors = [
                                    'text' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                                    'image' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                    'audio' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                    'video' => 'bg-pink-500/10 text-pink-400 border-pink-500/20',
                                ];
                            @endphp
                            <span class="flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $typeColors[$question->type] }}">
                                <i data-lucide="{{ $question->type === 'text' ? 'file-text' : ($question->type === 'image' ? 'image' : ($question->type === 'audio' ? 'mic' : 'video')) }}" class="w-3 h-3"></i>
                                {{ $question->type }}
                            </span>
                            <span class="px-3 py-1 bg-white/5 border border-white/5 text-gray-400 text-[10px] font-bold uppercase tracking-wider rounded-full">
                                {{ $question->subject->round->name }} • {{ $question->subject->name }}
                            </span>
                            @if($question->group)
                            <span class="flex items-center gap-1 px-3 py-1 bg-white/5 border border-white/5 text-gray-400 text-[10px] font-bold uppercase tracking-wider rounded-full">
                                <i data-lucide="users" class="w-3 h-3"></i> {{ $question->group->name }}
                            </span>
                            @endif
                            <span class="flex items-center gap-1 px-3 py-1 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 text-[10px] font-bold uppercase tracking-wider rounded-full">
                                <i data-lucide="clock" class="w-3 h-3"></i> {{ $question->time_limit }}s
                            </span>
                            <span class="flex items-center gap-1 px-3 py-1 bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-bold uppercase tracking-wider rounded-full">
                                <i data-lucide="plus-circle" class="w-3 h-3"></i> {{ $question->points }} Points
                            </span>
                        </div>

                        <h4 class="text-xl font-medium text-black mb-6 leading-relaxed">{{ $question->content }}</h4>

                        <!-- Options Visualization -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($question->options as $opt)
                                <div class="flex items-center gap-4 p-4 rounded-2xl {{ $opt->is_correct ? 'bg-emerald-500/5 border border-emerald-500/20' : 'bg-white/5 border border-transparent' }}">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0 {{ $opt->is_correct ? 'bg-emerald-500 text-white' : 'bg-gray-800 text-gray-500' }}">
                                        {{ chr(64 + $opt->option_number) }}
                                    </div>
                                    <span class="text-sm {{ $opt->is_correct ? 'text-emerald-400 font-semibold' : 'text-gray-400' }}">
                                        {{ $opt->option_text }}
                                    </span>
                                    @if($opt->is_correct)
                                        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500 ml-auto"></i>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Actions Panel -->
                    <div class="lg:w-12 flex lg:flex-col gap-3 justify-end lg:justify-start border-t lg:border-t-0 lg:border-l border-white/5 pt-6 lg:pt-0 lg:pl-6">
                        <button wire:click="openForm({{ $question->id }})" class="w-10 h-10 glass rounded-xl flex items-center justify-center text-indigo-400 hover:bg-indigo-600 hover:text-white transition-all" title="Edit">
                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                        </button>
                        <button wire:click="delete({{ $question->id }})" wire:confirm="Delete this question?" 
                                wire:loading.attr="disabled" wire:target="delete({{ $question->id }})"
                                class="w-10 h-10 glass rounded-xl flex items-center justify-center text-red-400 hover:bg-red-500 hover:text-white transition-all disabled:opacity-50" title="Delete">
                            <i data-lucide="trash-2" class="w-5 h-5" wire:loading.remove wire:target="delete({{ $question->id }})"></i>
                            <div wire:loading wire:target="delete({{ $question->id }})" class="w-5 h-5 border-2 border-red-500/30 border-t-red-500 rounded-full animate-spin"></div>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="glass-card rounded-3xl p-20 text-center animate__animated animate__fadeIn">
                <div class="w-20 h-20 bg-white/5 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="search-x" class="w-10 h-10 text-gray-600"></i>
                </div>
                <h3 class="text-xl font-display font-bold text-white mb-2">No results found</h3>
                <p class="text-gray-500 max-w-xs mx-auto">Try adjusting your filters or search term to find what you're looking for.</p>
                <button wire:click="$set('search', '')" class="mt-8 text-indigo-400 font-bold uppercase tracking-widest text-[10px] hover:text-white transition-colors">Clear All Filters</button>
            </div>
        @endforelse
    </div>

    <div class="mt-12 flex justify-center custom-pagination">
        {{ $questions->links() }}
    </div>

    <!-- Premium Modal Form -->
    @if($showForm)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4" x-data x-init="setTimeout(() => window.lucide && lucide.createIcons(), 50)">
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md" wire:click="closeForm"></div>
            <div class="glass relative z-10 w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-[2.5rem] p-8 lg:p-12 shadow-2xl border-white/10 animate__animated animate__zoomIn animate__faster no-scrollbar">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-3xl font-display font-bold text-gray-900">{{ $editingId ? 'Edit Challenge' : 'New Challenge' }}</h3>
                        <p class="text-gray-500 text-sm mt-1">Configure your question and options below.</p>
                    </div>
                    <button wire:click="closeForm" class="w-12 h-12 bg-white border border-gray-200 rounded-2xl flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-all">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-8">
                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-2">
                        <div class="space-y-2 col-span-1 md:col-span-2">
                            <label class="text-xs font-bold {{ $errors->has('selectedRounds') ? 'text-red-500' : 'text-gray-400' }} uppercase tracking-widest ml-1">Included Rounds</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($rounds as $r)
                                    <label class="flex items-center gap-2 px-4 py-2 {{ $errors->has('selectedRounds') ? 'border-red-500/50 bg-red-500/5' : 'bg-white/5 border-white/10' }} border rounded-xl cursor-pointer hover:bg-white/10 transition-colors">
                                        <input type="checkbox" wire:model="selectedRounds" value="{{ $r->id }}" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-900 font-medium">{{ $r->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('selectedRounds') <span class="text-red-400 text-[10px] font-bold uppercase tracking-wider ml-1 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-2">
                        <div class="space-y-2">
                            <label class="text-xs font-bold {{ $errors->has('subject_id') ? 'text-red-500' : 'text-gray-400' }} uppercase tracking-widest ml-1">Subject Context</label>
                            <select wire:model="subject_id" class="w-full bg-white/5 {{ $errors->has('subject_id') ? 'border-red-500' : 'border-white/10' }} rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50">
                                <option value="">Select Domain</option>
                                @foreach($subjects as $s)<option value="{{ $s->id }}">{{ $s->name }} ({{ $s->round->name }})</option>@endforeach
                            </select>
                            @error('subject_id') <span class="text-red-400 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold {{ $errors->has('group_id') ? 'text-red-500' : 'text-gray-400' }} uppercase tracking-widest ml-1">Group Access</label>
                            <select wire:model="group_id" class="w-full bg-white/5 {{ $errors->has('group_id') ? 'border-red-500' : 'border-white/10' }} rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50">
                                <option value="">Select Group</option>
                                @foreach($groups as $g)<option value="{{ $g->id }}">{{ $g->name }}</option>@endforeach
                            </select>
                            @error('group_id') <span class="text-red-400 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Media Format</label>
                            <select wire:model="type" class="w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50">
                                <option value="text">Pure Text</option>
                                <option value="image">Image Attachment</option>
                                <option value="audio">Audio Stream</option>
                                <option value="video">Video Feed</option>
                            </select>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold {{ $errors->has('content') ? 'text-red-500' : 'text-gray-400' }} uppercase tracking-widest ml-1">Question Blueprint</label>
                        <textarea wire:model="content" rows="4" class="w-full bg-white/5 {{ $errors->has('content') ? 'border-red-500' : 'border-white/10' }} rounded-2xl px-6 py-4 focus:ring-2 focus:ring-indigo-500/50 text-white placeholder-gray-600" placeholder="Type your intellectual challenge here..."></textarea>
                        @error('content') <span class="text-red-400 text-[10px] font-bold ml-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Media Uploads -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 glass-card rounded-3xl {{ ($errors->has('media_url') || $errors->has('media_file')) ? 'border-red-500/30' : '' }}">
                        <div class="space-y-2">
                            <label class="text-xs font-bold {{ $errors->has('media_url') ? 'text-red-500' : 'text-gray-400' }} uppercase tracking-widest ml-1">External Link</label>
                            <div class="relative">
                                <i data-lucide="link" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-600"></i>
                                <input type="text" wire:model="media_url" class="w-full bg-white/5 {{ $errors->has('media_url') ? 'border-red-500' : 'border-white/10' }} rounded-2xl pl-12 pr-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50" placeholder="https://youtube.com/...">
                            </div>
                            @error('media_url') <span class="text-red-400 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold {{ $errors->has('media_file') ? 'text-red-500' : 'text-gray-400' }} uppercase tracking-widest ml-1">Direct Upload</label>
                            <div class="relative group">
                                <input type="file" wire:model="media_file" class="hidden" id="file_upload">
                                <label for="file_upload" class="flex items-center justify-center gap-3 w-full h-12 glass {{ $errors->has('media_file') ? 'border-red-500 border-solid bg-red-500/5' : 'border-dashed border-white/20' }} rounded-2xl cursor-pointer hover:bg-white/5 transition-all">
                                    <i data-lucide="cloud-upload" class="w-5 h-5 text-indigo-400 mt-0.5"></i>
                                    <span class="text-xs font-semibold">Select Assets</span>
                                </label>
                            </div>
                            <div wire:loading wire:target="media_file" class="text-indigo-400 text-[10px] font-medium italic mt-1">Uploading...</div>
                            @error('media_file') <span class="text-red-400 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Scoring -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Time Limit (s)</label>
                            <input type="number" wire:model="time_limit" class="w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50" min="5">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Mark Value</label>
                            <input type="number" wire:model="points" class="w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50" min="1">
                        </div>
                    </div>

                    <!-- Options Grid -->
                    <div class="space-y-4">
                        <label class="text-xs font-bold {{ $errors->has('options.*.text') || $errors->has('correct_option') ? 'text-red-500' : 'text-gray-400' }} uppercase tracking-widest ml-1">MCQ Options Configuration</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($options as $index => $option)
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-4 flex items-center">
                                        <input type="radio" wire:model="correct_option" value="{{ $index + 1 }}" class="w-5 h-5 bg-white/5 border-white/20 text-indigo-600 focus:ring-0">
                                    </div>
                                    <input type="text" wire:model="options.{{ $index }}.text" class="w-full bg-white/5 {{ $errors->has('options.'.$index.'.text') ? 'border-red-500 bg-red-500/5' : 'border-white/10' }} rounded-2xl pl-14 pr-4 py-4 focus:ring-2 focus:ring-emerald-500/50 transition-all focus:bg-emerald-500/5" placeholder="Option {{ chr(65 + $index) }}">
                                    @error('options.'.$index.'.text') <span class="absolute -bottom-5 left-1 text-red-400 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                                </div>
                            @endforeach
                        </div>
                        @error('correct_option') <span class="text-red-400 text-xs mt-2 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4 pt-8">
                        <button type="button" wire:click="closeForm" class="flex-1 py-4 bg-gray-100/50 rounded-2xl text-sm font-bold text-gray-500 hover:text-gray-900 transition-all border border-gray-200">Discard Changes</button>
                        <button type="submit" class="flex-[2] py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-sm font-bold transition-all shadow-xl shadow-indigo-600/30 flex items-center justify-center gap-2 disabled:opacity-70" wire:loading.attr="disabled">
                            <i data-lucide="send" class="w-5 h-5" wire:loading.remove wire:target="save"></i>
                            <div wire:loading wire:target="save" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            <span>{{ $editingId ? 'Update' : 'Submit' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
