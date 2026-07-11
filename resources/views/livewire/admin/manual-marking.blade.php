<div class="space-y-8 animate__animated animate__fadeIn">
    @php $canCreate = auth()->user()->isAdmin() || auth()->user()->hasPermission('marks-create') @endphp
    <!-- Header -->
    <div>
        <h2 class="text-3xl font-display font-bold text-gray-900 mb-2">Performance Appraisal</h2>
        <p class="text-sm text-gray-400">Manual adjustments and score refinement for specific domains</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Entry Form -->
        <div class="lg:col-span-4 lg:sticky lg:top-8 self-start">
            <div class="glass-card rounded-[2.5rem] p-8 lg:p-10 border border-gray-100">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600">
                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-xl font-display font-bold text-gray-900">Score Adjustment</h3>
                </div>

                <form wire:submit.prevent="save" class="space-y-6">
                    <div class="space-y-2 relative" x-data="{ open: false, search: @entangle('searchStudent').live, studentName: @entangle('selectedStudentName') }" @click.away="open = false">
                        <label
                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Candidate</label>
                        <div @click="open = !open" class="w-full bg-white border border-gray-200 rounded-2xl px-4 py-3.5 text-sm cursor-pointer flex justify-between items-center transition-colors hover:border-indigo-300 hover:bg-gray-50">
                            <span
                                x-text="studentName || 'Select Target Candidate'"
                                :style="studentName ? 'color:#000000; font-weight:500' : 'color:#9ca3af'"></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                        </div>

                        <div x-show="open" x-cloak x-transition class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden pb-2">
                            <div class="p-3 border-b border-gray-100 bg-gray-50">
                                <input type="text" x-model="search" placeholder="Search ID or Name..."
                                    class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-black text-sm focus:ring-2 focus:ring-indigo-300 focus:outline-none placeholder-gray-400"
                                    @click.stop>
                            </div>
                            <div class="max-h-56 overflow-y-auto px-2 pt-2 no-scrollbar">
                                @forelse($students as $student)
                                    <div wire:click="setStudent({{ $student->id }}, '{{ addslashes($student->name) }}')"
                                         @click="open = false"
                                         class="px-4 py-3 text-black hover:bg-indigo-50 hover:text-indigo-700 rounded-xl cursor-pointer transition-colors flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold shrink-0">{{ $student->id }}</div>
                                        <span class="text-sm font-medium">{{ $student->name }}</span>
                                    </div>
                                @empty
                                    <div class="px-4 py-4 text-center text-gray-400 text-sm">No candidates found</div>
                                @endforelse
                            </div>
                        </div>
                        @error('user_id') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Strategic
                            Round</label>
                        <select wire:model.live="round_id"
                            class="w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-500/50">
                            <option value="">Select Phase</option>
                            @foreach($rounds as $round)<option value="{{ $round->id }}">{{ $round->name }}</option>
                            @endforeach
                        </select>
                        @error('round_id') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Subject
                            Domain</label>
                        <select wire:model="subject_id"
                            class="w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-500/50">
                            <option value="">Select Domain</option>
                            @foreach($subjects as $subject)<option value="{{ $subject['id'] }}">{{ $subject['name'] }}
                            </option>@endforeach
                        </select>
                        @error('subject_id') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Manual
                            Increment</label>
                        <div class="relative">
                            <i data-lucide="plus"
                                class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"></i>
                            <input type="number" step="0.01" wire:model="manual_marks"
                                class="w-full bg-white border-gray-200 rounded-2xl pl-12 pr-4 py-3.5 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-500/50"
                                min="0" placeholder="0.00">
                        </div>
                        @error('manual_marks') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    @if($canCreate)
                    <button type="submit"
                        class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-xs font-bold uppercase tracking-widest transition-all shadow-xl shadow-indigo-600/20 active:scale-95">
                        Commit Adjustment
                    </button>
                    @else
                    <div class="w-full py-4 bg-gray-100 rounded-2xl text-xs font-bold uppercase tracking-widest text-center text-gray-400 cursor-not-allowed">
                        View Only — No Write Access
                    </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Marks History -->
        <div class="lg:col-span-8">
            <div class="glass-card rounded-[2.5rem] overflow-hidden border border-gray-100">
                <div class="p-8 lg:p-10 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-xl font-display font-bold text-gray-900">Appraisal History</h3>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Real-time
                            Feed</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white/[0.02]">
                                <th class="py-5 px-8 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                    Candidate</th>
                                <th class="py-5 px-8 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                    Domain Focus</th>
                                <th class="py-5 px-8 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                    Evaluator</th>
                                <th
                                    class="py-5 px-8 text-[10px] font-bold text-gray-500 uppercase tracking-widest text-right">
                                    Online</th>
                                <th
                                    class="py-5 px-8 text-[10px] font-bold text-gray-500 uppercase tracking-widest text-right">
                                    Manual</th>
                                <th
                                    class="py-5 px-8 text-[10px] font-bold text-gray-500 uppercase tracking-widest text-right">
                                    Metric Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($allMarks as $mark)
                                <tr class="group hover:bg-gray-50 transition-colors">
                                    <td class="py-6 px-8">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-[10px] font-bold">
                                                {{ strtoupper(substr($mark->user->name ?? '?', 0, 1)) }}
                                            </div>
                                            <span
                                                class="text-sm font-bold text-gray-900">{{ $mark->user->name ?? 'Sentinel' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-6 px-8">
                                        <div class="flex flex-col">
                                            <span class="text-xs text-gray-400">{{ $mark->subject->name ?? 'N/A' }}</span>
                                            <span
                                                class="text-[10px] text-gray-600 uppercase">{{ $mark->round->name ?? 'Generic Phase' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-6 px-8">
                                        <div class="flex flex-col">
                                            <span class="text-xs text-indigo-400 font-medium pb-1">{{ $mark->admin->name ?? 'System' }}</span>
                                            <span class="text-[10px] text-gray-600 uppercase tracking-wide">Evaluator</span>
                                        </div>
                                    </td>
                                    <td class="py-6 px-8 text-right text-sm text-gray-400 font-medium">
                                        {{ number_format($mark->online_marks, 2) }}</td>
                                    <td class="py-6 px-8 text-right text-sm text-purple-400 font-medium">
                                        +{{ number_format($mark->manual_marks, 2) }}</td>
                                    <td class="py-6 px-8 text-right">
                                        <span
                                            class="text-base font-display font-bold text-emerald-400">{{ number_format($mark->total_marks, 2) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-24 text-center">
                                        <i data-lucide="clipboard-list" class="w-12 h-12 text-gray-700 mx-auto mb-4"></i>
                                        <p class="text-gray-500 font-medium">No performance records detected.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
