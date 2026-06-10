<div class="space-y-8 animate__animated animate__fadeIn">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-display font-bold text-white mb-2">Competition Rounds</h2>
            <p class="text-sm text-gray-400">Orchestrate the progression of your competitive events</p>
        </div>
        <button wire:click="openForm" wire:loading.attr="disabled"
            class="flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/30 disabled:opacity-50">
            <i data-lucide="plus-circle" class="w-5 h-5" wire:loading.remove wire:target="openForm"></i>
            <div wire:loading wire:target="openForm" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
            New Round
        </button>
    </div>

    <!-- Stats Snapshot -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass flex items-center justify-between p-5 rounded-2xl border-white/5">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400">
                <i data-lucide="layers" class="w-5 h-5"></i>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Total</p>
                <p class="text-xl font-display font-bold text-white">{{ $rounds->count() }}</p>
            </div>
        </div>
        <div class="glass flex items-center justify-between p-5 rounded-2xl border-white/5">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Active</p>
                <p class="text-xl font-display font-bold text-white">{{ $rounds->where('is_active', true)->count() }}
                </p>
            </div>
        </div>
        <div class="glass flex items-center justify-between p-5 rounded-2xl border-white/5">
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400">
                <i data-lucide="star" class="w-5 h-5"></i>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Finals</p>
                <p class="text-xl font-display font-bold text-white">{{ $rounds->where('is_final', true)->count() }}</p>
            </div>
        </div>
        <div class="glass flex items-center justify-between p-5 rounded-2xl border-white/5">
            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-400">
                <i data-lucide="calendar" class="w-5 h-5"></i>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Upcoming</p>
                <p class="text-xl font-display font-bold text-white">{{ $rounds->where('is_active', false)->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Rounds Data Table -->
    <div class="glass rounded-[2rem] overflow-hidden border border-white/5">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5">
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Rank</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Designation
                    </th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Properties</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Operational
                        Status</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-right">
                        Management</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($rounds as $round)
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        <td class="py-6 px-8">
                            <span
                                class="text-sm font-display font-bold text-gray-500 group-hover:text-indigo-400 transition-colors">
                                {{ str_pad($round->order, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td class="py-6 px-8">
                            <div>
                                <h4 class="text-sm font-bold text-white">{{ $round->name }}</h4>
                                <p class="text-[10px] text-gray-600 mt-1 max-w-[200px] truncate">
                                    {{ $round->description ?: 'No briefing provided.' }}</p>
                            </div>
                        </td>
                        <td class="py-6 px-8">
                            <div class="flex flex-wrap gap-2">
                                @if($round->is_final)
                                    <span
                                        class="px-2 py-0.5 bg-purple-500/10 border border-purple-500/20 text-purple-400 text-[8px] font-bold uppercase tracking-widest rounded">Championship</span>
                                @else
                                    <span
                                        class="px-2 py-0.5 bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[8px] font-bold uppercase tracking-widest rounded">Qualifier</span>
                                @endif
                                <span
                                    class="px-2 py-0.5 bg-white/5 border border-white/5 text-gray-500 text-[8px] font-bold uppercase tracking-widest rounded">
                                    {{ $round->subjects_count ?? $round->subjects()->count() }} Domains
                                </span>
                            </div>
                        </td>
                        <td class="py-6 px-8">
                            <button wire:click="toggleActive({{ $round->id }})"
                                class="flex items-center gap-2 group/toggle">
                                <div
                                    class="w-8 h-4 bg-gray-800 rounded-full relative transition-colors duration-300 {{ $round->is_active ? 'bg-indigo-600' : 'bg-gray-700' }}">
                                    <div
                                        class="absolute top-0.5 left-0.5 w-3 h-3 bg-white rounded-full transition-transform duration-300 {{ $round->is_active ? 'translate-x-4' : 'translate-x-0' }}">
                                    </div>
                                </div>
                                <span
                                    class="text-[10px] font-bold uppercase tracking-wider {{ $round->is_active ? 'text-indigo-400' : 'text-gray-600' }}">
                                    {{ $round->is_active ? 'Online' : 'Offline' }}
                                </span>
                            </button>
                        </td>
                        <td class="py-6 px-8 text-right">
                            <div
                                class="flex items-center justify-end gap-3 opacity-60 group-hover:opacity-100 transition-opacity">
                                <button wire:click="openForm({{ $round->id }})"
                                    class="p-2 hover:bg-white/5 rounded-lg text-indigo-400 transition-colors"
                                    title="Edit Configuration">
                                    <i data-lucide="pen" class="w-4 h-4"></i>
                                </button>
                                 <button wire:click="delete({{ $round->id }})" wire:confirm="Eliminate this round?"
                                    wire:loading.attr="disabled" wire:target="delete({{ $round->id }})"
                                    class="p-2 hover:bg-white/5 rounded-lg text-red-500 transition-colors disabled:opacity-50"
                                    title="Remove Entry">
                                    <i data-lucide="trash-2" class="w-4 h-4" wire:loading.remove wire:target="delete({{ $round->id }})"></i>
                                    <div wire:loading wire:target="delete({{ $round->id }})" class="w-4 h-4 border-2 border-red-500/30 border-t-red-500 rounded-full animate-spin"></div>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-24 text-center">
                            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i data-lucide="layers-2" class="w-10 h-10 text-gray-700"></i>
                            </div>
                            <h3 class="text-lg font-display font-medium text-gray-500">No organizational rounds found</h3>
                            <button wire:click="openForm"
                                class="mt-4 text-indigo-400 text-xs font-bold uppercase tracking-widest hover:text-white transition-colors">Generate
                                Initial Round</button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Management Modal -->
    @if($showForm)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4" x-data x-init="setTimeout(() => window.lucide && lucide.createIcons(), 50)">
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md" wire:click="closeForm"></div>
            <div
                class="glass relative z-10 w-full max-w-lg rounded-[2.5rem] p-8 lg:p-10 shadow-2xl border-white/10 animate__animated animate__zoomIn animate__faster">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-2xl font-display font-bold text-gray-900">
                            {{ $editingId ? 'Modify Strategic Round' : 'Initialize New Round' }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Define the parameters of this competition phase.</p>
                    </div>
                    <button wire:click="closeForm"
                        class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-all">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-3 space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Round
                                Designation</label>
                            <input type="text" wire:model="name"
                                class="w-full bg-white/5 border-white/10 rounded-2xl px-4 py-4 focus:ring-2 focus:ring-indigo-500/50 text-white"
                                placeholder="e.g. Preliminary Qualifiers">
                            @error('name') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Sort
                                Key</label>
                            <input type="number" wire:model="order"
                                class="w-full bg-white/5 border-white/10 rounded-2xl px-4 py-4 focus:ring-2 focus:ring-indigo-500/50 text-white text-center">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Strategic
                            Description</label>
                        <textarea wire:model="description" rows="3"
                            class="w-full bg-white/5 border-white/10 rounded-2xl px-4 py-4 focus:ring-2 focus:ring-indigo-500/50 text-white placeholder-gray-600"
                            placeholder="Define the rules or scope for participants..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <label
                            class="relative flex flex-col items-center justify-center p-6 glass-card rounded-3xl cursor-pointer hover:bg-gray-50 transition-all group overflow-hidden">
                            <input type="checkbox" wire:model="is_active" class="hidden">
                            <div
                                class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3 transition-colors {{ $is_active ? 'bg-indigo-600' : 'bg-gray-100' }}">
                                <i data-lucide="power" class="w-6 h-6 {{ $is_active ? 'text-white' : 'text-gray-400' }}"></i>
                            </div>
                            <span
                                class="text-[10px] font-bold uppercase tracking-widest {{ $is_active ? 'text-indigo-600' : 'text-gray-500' }}">Active
                                Mode</span>
                            <div
                                class="absolute inset-0 border-2 transition-colors pointer-events-none rounded-3xl {{ $is_active ? 'border-indigo-500/50' : 'border-transparent' }}">
                            </div>
                        </label>

                        <label
                            class="relative flex flex-col items-center justify-center p-6 glass-card rounded-3xl cursor-pointer hover:bg-gray-50 transition-all group overflow-hidden">
                            <input type="checkbox" wire:model="is_final" class="hidden">
                            <div
                                class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3 transition-colors {{ $is_final ? 'bg-purple-600' : 'bg-gray-100' }}">
                                <i data-lucide="trophy" class="w-6 h-6 {{ $is_final ? 'text-white' : 'text-gray-400' }}"></i>
                            </div>
                            <span
                                class="text-[10px] font-bold uppercase tracking-widest {{ $is_final ? 'text-purple-600' : 'text-gray-500' }}">Final
                                Stage</span>
                            <div
                                class="absolute inset-0 border-2 transition-colors pointer-events-none rounded-3xl {{ $is_final ? 'border-purple-500/50' : 'border-transparent' }}">
                            </div>
                        </label>
                    </div>

                    <div class="flex gap-4 pt-6">
                        <button type="button" wire:click="closeForm"
                            class="flex-1 py-4 bg-gray-100/50 rounded-2xl text-xs font-bold text-gray-500 hover:text-gray-900 transition-all border border-gray-200">Discard</button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="flex-[2] py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-xs font-bold transition-all shadow-xl shadow-indigo-600/30 flex items-center justify-center gap-2">
                            <i data-lucide="check" class="w-4 h-4" wire:loading.remove wire:target="save"></i>
                            <div wire:loading wire:target="save" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            <span>{{ $editingId ? 'Update' : 'Submit' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>