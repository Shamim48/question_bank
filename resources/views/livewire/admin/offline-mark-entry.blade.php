<div class="space-y-8 animate__animated animate__fadeIn">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-display font-bold text-white mb-2">Offline Marks Entry</h2>
            <p class="text-sm text-gray-400">Record marks given by multiple judges in offline events</p>
        </div>
        <div class="flex items-center gap-4">
            <button wire:click="openForm"
                class="flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/30">
                <i data-lucide="plus-circle" class="w-5 h-5"></i> Add Mark
            </button>
        </div>
    </div>

    <!-- Stats Snapshot -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="glass flex items-center justify-between p-4 rounded-2xl border-white/5">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Records</span>
            <span class="text-xl font-display font-bold text-indigo-400">{{ $marksList->total() }}</span>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="glass rounded-3xl p-2 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-2">
            <div class="md:col-span-3 relative group">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 group-focus-within:text-indigo-400 transition-colors"></i>
                <input type="text" wire:model.live.debounce.300ms="searchStudent" placeholder="Search ID, name, email..."
                    class="w-full bg-white/5 border-none rounded-2xl pl-12 pr-4 py-3.5 text-white text-sm focus:ring-2 focus:ring-indigo-500/50">
            </div>
            <div class="md:col-span-2">
                <select wire:model.live="filterGroup"
                    class="w-full bg-white/5 border-none rounded-2xl px-4 py-3.5 text-white text-sm focus:ring-2 focus:ring-indigo-500/50">
                    <option value="">All Groups</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->name }}">{{ $group->name }}</option>
                    @endforeach
                </select>
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
                <select wire:model.live="filterSubject"
                    class="w-full bg-white/5 border-none rounded-2xl px-4 py-3.5 text-white text-sm focus:ring-2 focus:ring-indigo-500/50">
                    <option value="">All Subjects</option>
                    @foreach($filterSubjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->round->name }})</option>
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

    <!-- Marks Table Grid -->
    <div class="space-y-4">
        @forelse($marksList as $mark)
            <div class="glass-card rounded-3xl p-6 lg:p-8 animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.05 }}s">
                <div class="flex flex-col lg:flex-row gap-8 items-center">
                    <!-- Student Info -->
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-2">
                            <span class="px-3 py-1 bg-white/5 border border-white/5 text-gray-400 text-[10px] font-bold uppercase tracking-wider rounded-full">
                                {{ $mark->round->name }} • {{ $mark->subject->name }}
                            </span>
                        </div>
                        <h4 class="text-xl font-medium text-white">{{ $mark->student->name }}</h4>
                        <p class="text-sm text-gray-400 mt-1">{{ $mark->student->email }}</p>
                    </div>

                    <!-- Marks Info -->
                    <div class="flex-[0.5] flex flex-col items-center">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Judge</span>
                        <span class="text-[15px] font-medium text-gray-300">{{ $mark->judge_name }}</span>
                    </div>
                    
                    <div class="flex-[0.5] flex flex-col items-center">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Marks</span>
                        <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                            <span class="text-2xl font-display font-bold text-indigo-400">{{ $mark->marks }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="lg:w-16 flex lg:flex-col gap-3 justify-end lg:justify-start border-t lg:border-t-0 lg:border-l border-white/5 pt-6 lg:pt-0 lg:pl-6">
                        <button wire:click="openForm({{ $mark->id }})" class="w-10 h-10 glass rounded-xl flex items-center justify-center text-indigo-400 hover:bg-indigo-600 hover:text-white transition-all" title="Edit">
                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                        </button>
                        <button wire:click="delete({{ $mark->id }})" wire:confirm="Delete this mark?" class="w-10 h-10 glass rounded-xl flex items-center justify-center text-red-400 hover:bg-red-500 hover:text-white transition-all" title="Delete">
                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="glass-card rounded-3xl p-20 text-center animate__animated animate__fadeIn">
                <div class="w-20 h-20 bg-white/5 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="clipboard-x" class="w-10 h-10 text-gray-600"></i>
                </div>
                <h3 class="text-xl font-display font-bold text-white mb-2">No records found</h3>
                <p class="text-gray-500 max-w-xs mx-auto">No offline marks have been recorded yet or your filters do not match any records.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-12 flex justify-center custom-pagination">
        {{ $marksList->links() }}
    </div>

    <!-- Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4" x-data x-init="setTimeout(() => window.lucide && lucide.createIcons(), 50)">
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md" wire:click="closeForm"></div>
            <div class="glass relative z-10 w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-[2.5rem] p-8 lg:p-12 shadow-2xl border-white/10 animate__animated animate__zoomIn animate__faster no-scrollbar">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-3xl font-display font-bold text-gray-900">{{ $editingId ? 'Edit Marks' : 'Record Offline Marks' }}</h3>
                        <p class="text-gray-500 text-sm mt-1">Enter marks given by a judge for a student.</p>
                    </div>
                    <button wire:click="closeForm" class="w-12 h-12 bg-white border border-gray-200 rounded-2xl flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-all">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-8">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Group Filter (Optional)</label>
                                <select wire:model.live="group_id" class="w-full bg-white/5 border-white/10 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50">
                                    <option value="">Any Group</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2 relative" x-data="{ open: false, search: @entangle('searchStudentQuery').live }" @click.away="open = false">
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Student</label>
                                <div @click="open = !open" class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3.5 text-white text-sm cursor-pointer flex justify-between items-center transition-colors hover:bg-white/10">
                                    <span x-text="$wire.selectedStudentName || 'Select Student'" :class="!$wire.selectedStudentName ? 'text-gray-400' : ''"></span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                </div>
                                
                                <div x-show="open" x-cloak x-transition class="absolute z-50 w-full mt-2 bg-slate-900 border border-white/10 rounded-2xl shadow-2xl overflow-hidden backdrop-blur-xl pb-2">
                                    <div class="p-3 border-b border-white/10 bg-black/40">
                                        <input type="text" x-model="search" placeholder="Search ID or Name..." class="w-full bg-white/5 border-none rounded-xl px-4 py-3 text-white text-sm focus:ring-2 focus:ring-indigo-500/50 placeholder-gray-500" @click.stop>
                                    </div>
                                    <div class="max-h-56 overflow-y-auto px-2 pt-2 no-scrollbar">
                                        @forelse($students as $student)
                                            <div @click="$wire.setStudent({{ $student->id }}, '{{ addslashes($student->name) }}'); open = false; search = ''" 
                                                 class="px-4 py-3 text-gray-300 hover:bg-indigo-600 hover:text-white rounded-xl cursor-pointer transition-colors flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-xs font-bold">{{ $student->id }}</div>
                                                <span>{{ $student->name }}</span>
                                            </div>
                                        @empty
                                            <div class="px-4 py-4 text-center text-gray-500 text-sm">No candidates found</div>
                                        @endforelse
                                    </div>
                                </div>
                                @error('student_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Round</label>
                                <select wire:model.live="round_id" class="w-full bg-white/5 border-white/10 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50">
                                    <option value="">Select Round</option>
                                    @foreach($rounds as $round)
                                        <option value="{{ $round->id }}">{{ $round->name }}</option>
                                    @endforeach
                                </select>
                                @error('round_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Subject</label>
                                <select wire:model="subject_id" class="w-full bg-white/5 border-white/10 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50">
                                    <option value="">Select Subject</option>
                                    @foreach($formSubjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                @error('subject_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Judge Name</label>
                                <input type="text" wire:model="judge_name" class="w-full bg-white/5 border-white/10 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50" placeholder="e.g. John Doe">
                                @error('judge_name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Marks Awarded</label>
                                <input type="number" step="0.01" wire:model="marks" class="w-full bg-white/5 border-white/10 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50" placeholder="0">
                                @error('marks') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4 pt-4">
                        <button type="button" wire:click="closeForm" class="flex-1 py-4 bg-gray-100/50 rounded-2xl text-sm font-bold text-gray-500 hover:text-gray-900 transition-all border border-gray-200">Cancel</button>
                        <button type="submit" class="flex-[2] py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-sm font-bold transition-all shadow-xl shadow-indigo-600/30">
                            {{ $editingId ? 'Update Marks' : 'Save Marks' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
