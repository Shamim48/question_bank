<div class="space-y-8 animate__animated animate__fadeIn">
    @php
        $u = auth()->user();
        $canCreate = $u->isAdmin() || $u->hasPermission('offline-marks-create');
        $canEdit   = $u->isAdmin() || $u->hasPermission('offline-marks-edit');
        $canDelete = $u->isAdmin() || $u->hasPermission('offline-marks-delete');
    @endphp

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900 mb-2">Offline Marks Entry</h2>
            <p class="text-sm text-gray-400">Record marks given by multiple judges in offline events</p>
        </div>
        @if($canCreate)
        <button wire:click="openForm" wire:loading.attr="disabled"
            class="flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/30 disabled:opacity-50">
            <i data-lucide="plus-circle" class="w-5 h-5" wire:loading.remove wire:target="openForm"></i>
            <div wire:loading wire:target="openForm" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
            Add Mark
        </button>
        @endif
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="glass-card flex items-center justify-between p-4 rounded-2xl border border-gray-100">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Records</span>
            <span class="text-xl font-display font-bold text-indigo-600">{{ $marksList->total() }}</span>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-3xl p-3 border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-2">
            <div class="md:col-span-3 relative group">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-indigo-500 transition-colors pointer-events-none"></i>
                <input type="text" wire:model.live.debounce.300ms="searchStudent" placeholder="Search name or ID..."
                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-300 focus:outline-none placeholder-gray-400">
            </div>
            <div class="md:col-span-2">
                <select wire:model.live="filterGroup"
                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-300 focus:outline-none">
                    <option value="">All Groups</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->name }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3">
                <select wire:model.live="filterRound"
                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-300 focus:outline-none">
                    <option value="">All Rounds</option>
                    @foreach($rounds as $round)
                        <option value="{{ $round->id }}">{{ $round->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3">
                <select wire:model.live="filterSubject"
                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-300 focus:outline-none">
                    <option value="">All Subjects</option>
                    @foreach($filterSubjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->round->name }})</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-1 flex items-center justify-center">
                <button wire:click="$refresh"
                    class="w-full h-full min-h-[46px] glass-card border border-gray-200 rounded-2xl flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:border-indigo-300 transition-all">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Marks List -->
    <div class="space-y-4">
        @forelse($marksList as $mark)
            <div class="glass-card rounded-3xl p-6 lg:p-8 border border-gray-100 animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.05 }}s">
                <div class="flex flex-col lg:flex-row gap-8 items-center">
                    <!-- Student Info -->
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="px-3 py-1 bg-indigo-50 border border-indigo-100 text-indigo-600 text-[10px] font-bold uppercase tracking-wider rounded-full">
                                {{ $mark->round->name }} • {{ $mark->subject->name }}
                            </span>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">{{ $mark->student->name }}</h4>
                        <p class="text-sm text-gray-400 mt-0.5">{{ $mark->student->email }}</p>
                    </div>

                    <!-- Judge -->
                    <div class="flex-[0.5] flex flex-col items-center">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Judge</span>
                        <span class="text-sm font-medium text-gray-700">{{ $mark->judge_name }}</span>
                    </div>

                    <!-- Marks -->
                    <div class="flex-[0.5] flex flex-col items-center">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Marks</span>
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center">
                            <span class="text-2xl font-display font-bold text-indigo-600">{{ $mark->marks }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($canEdit || $canDelete)
                    <div class="lg:w-16 flex lg:flex-col gap-3 justify-end border-t lg:border-t-0 lg:border-l border-gray-100 pt-4 lg:pt-0 lg:pl-6">
                        @if($canEdit)
                        <button wire:click="openForm({{ $mark->id }})"
                            class="w-10 h-10 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-center text-indigo-500 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all" title="Edit">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                        </button>
                        @endif
                        @if($canDelete)
                        <button wire:click="delete({{ $mark->id }})" wire:confirm="Delete this mark?"
                            wire:loading.attr="disabled" wire:target="delete({{ $mark->id }})"
                            class="w-10 h-10 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-center text-red-400 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all disabled:opacity-50" title="Delete">
                            <i data-lucide="trash-2" class="w-4 h-4" wire:loading.remove wire:target="delete({{ $mark->id }})"></i>
                            <div wire:loading wire:target="delete({{ $mark->id }})" class="w-4 h-4 border-2 border-red-300 border-t-red-500 rounded-full animate-spin"></div>
                        </button>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="glass-card rounded-3xl p-20 text-center border border-gray-100">
                <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="clipboard-x" class="w-10 h-10 text-gray-400"></i>
                </div>
                <h3 class="text-xl font-display font-bold text-gray-900 mb-2">No records found</h3>
                <p class="text-gray-400 max-w-xs mx-auto text-sm">No offline marks recorded yet or filters don't match any records.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8 flex justify-center">
        {{ $marksList->links() }}
    </div>

    <!-- Modal -->
    @if($showForm)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4"
             x-data x-init="setTimeout(() => window.lucide && lucide.createIcons(), 50)">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeForm"></div>

            <!-- Card -->
            <div class="relative z-10 w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white rounded-[2rem] p-8 lg:p-10 shadow-2xl border border-gray-100 animate__animated animate__zoomIn animate__faster no-scrollbar">

                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-2xl font-display font-bold text-gray-900">{{ $editingId ? 'Edit Marks' : 'Record Offline Marks' }}</h3>
                        <p class="text-sm text-gray-400 mt-1">Enter marks given by a judge for a student.</p>
                    </div>
                    <button wire:click="closeForm"
                        class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-gray-900 transition-all">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Group Filter -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Group Filter <span class="normal-case font-normal text-gray-300">(optional)</span></label>
                            <select wire:model.live="group_id"
                                class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-300 focus:outline-none">
                                <option value="">Any Group</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Student Dropdown -->
                        <div class="space-y-1.5 relative"
                             x-data="{ open: false, search: @entangle('searchStudentQuery').live, studentName: @entangle('selectedStudentName') }"
                             @click.away="open = false">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Student</label>

                            <!-- Trigger -->
                            <div @click="open = !open"
                                 class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm cursor-pointer flex justify-between items-center hover:border-indigo-300 transition-colors">
                                <span :style="studentName ? 'color:#000000;font-weight:500' : 'color:#9ca3af'"
                                      x-text="studentName || 'Select Student'"></span>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform shrink-0" :class="open ? 'rotate-180' : ''"></i>
                            </div>

                            <!-- Dropdown Panel -->
                            <div x-show="open" x-cloak x-transition
                                 class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-xl overflow-hidden pb-2">
                                <div class="p-2.5 border-b border-gray-100 bg-gray-50">
                                    <input type="text" x-model="search" placeholder="Search name or ID..."
                                        class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-300 focus:outline-none placeholder-gray-400"
                                        @click.stop>
                                </div>
                                <div class="max-h-52 overflow-y-auto px-2 pt-2 no-scrollbar">
                                    @forelse($students as $student)
                                        <div wire:click="setStudent({{ $student->id }}, '{{ addslashes($student->name) }}')"
                                             @click="open = false"
                                             class="px-3 py-2.5 text-gray-900 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg cursor-pointer transition-colors flex items-center gap-3">
                                            <div class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold shrink-0">
                                                {{ $student->id }}
                                            </div>
                                            <span class="text-sm font-medium">{{ $student->name }}</span>
                                        </div>
                                    @empty
                                        <div class="px-4 py-4 text-center text-gray-400 text-sm">No students found</div>
                                    @endforelse
                                </div>
                            </div>

                            @error('student_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Round -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Round</label>
                            <select wire:model.live="round_id"
                                class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-300 focus:outline-none">
                                <option value="">Select Round</option>
                                @foreach($rounds as $round)
                                    <option value="{{ $round->id }}">{{ $round->name }}</option>
                                @endforeach
                            </select>
                            @error('round_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Subject -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Subject</label>
                            <select wire:model="subject_id"
                                class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-300 focus:outline-none">
                                <option value="">Select Subject</option>
                                @foreach($formSubjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            @error('subject_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Judge Name -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Judge Name</label>
                            <input type="text" wire:model="judge_name" placeholder="e.g. John Doe"
                                class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-300 focus:outline-none placeholder-gray-400">
                            @error('judge_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Marks -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Marks Awarded</label>
                            <input type="number" step="0.01" wire:model="marks" placeholder="0"
                                class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-300 focus:outline-none placeholder-gray-400">
                            @error('marks') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-2">
                        <button type="button" wire:click="closeForm"
                            class="flex-1 py-3.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:text-gray-900 transition-all">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-[2] py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/20 flex items-center justify-center gap-2 disabled:opacity-60"
                            wire:loading.attr="disabled">
                            <i data-lucide="check" class="w-4 h-4" wire:loading.remove wire:target="save"></i>
                            <div wire:loading wire:target="save" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            <span>{{ $editingId ? 'Update Marks' : 'Save Marks' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
