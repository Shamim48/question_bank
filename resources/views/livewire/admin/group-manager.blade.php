<div class="space-y-8 animate__animated animate__fadeIn">
    @php
        $u = auth()->user();
        $canCreate = $u->isAdmin() || $u->hasPermission('groups-create');
        $canEdit   = $u->isAdmin() || $u->hasPermission('groups-edit');
        $canDelete = $u->isAdmin() || $u->hasPermission('groups-delete');
    @endphp
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-display font-bold text-white mb-2">Group Manager</h2>
            <p class="text-sm text-gray-400">Manage participant groups and access levels</p>
        </div>
        @if($canCreate)
        <button wire:click="openForm" wire:loading.attr="disabled"
            class="flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/30 disabled:opacity-50">
            <i data-lucide="plus-circle" class="w-5 h-5" wire:loading.remove wire:target="openForm"></i>
            <div wire:loading wire:target="openForm" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
            New Group
        </button>
        @endif
    </div>

    <!-- Content Table -->
    <div class="glass rounded-[2rem] overflow-hidden border border-white/5">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5">
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Identification</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Questions</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($groups as $group)
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        <td class="py-5 px-8">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 group-hover:scale-110 transition-transform">
                                    <i data-lucide="users" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">{{ $group->name }}</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5 truncate max-w-[200px]">
                                        {{ $group->description ?: 'No description provided' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-8">
                            <div class="flex items-center gap-2">
                                <div class="flex -space-x-2">
                                    <div class="w-6 h-6 rounded-full border border-gray-900 bg-indigo-600 flex items-center justify-center text-[8px] font-bold text-white">
                                        {{ $group->questions_count }}</div>
                                </div>
                                <span class="text-xs text-gray-400">Total</span>
                            </div>
                        </td>
                        <td class="py-5 px-8 text-right">
                            @if($canEdit || $canDelete)
                            <div class="flex items-center justify-end gap-2">
                                @if($canEdit)
                                <button wire:click="openForm({{ $group->id }})"
                                    class="w-9 h-9 glass rounded-lg flex items-center justify-center text-indigo-400 hover:bg-indigo-600 hover:text-white transition-all">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>
                                @endif
                                @if($canDelete)
                                <button wire:click="delete({{ $group->id }})" wire:confirm="Delete this group?"
                                    wire:loading.attr="disabled" wire:target="delete({{ $group->id }})"
                                    class="w-9 h-9 glass rounded-lg flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white transition-all disabled:opacity-50">
                                    <i data-lucide="trash-2" class="w-4 h-4" wire:loading.remove wire:target="delete({{ $group->id }})"></i>
                                    <div wire:loading wire:target="delete({{ $group->id }})" class="w-4 h-4 border-2 border-red-500/30 border-t-red-500 rounded-full animate-spin"></div>
                                </button>
                                @endif
                            </div>
                            @else
                            <span class="text-xs text-gray-600">View only</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-20 text-center">
                            <i data-lucide="users" class="w-12 h-12 text-gray-700 mx-auto mb-4"></i>
                            <p class="text-gray-500 font-medium">No groups detected in system.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    @if($showForm)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4" x-data x-init="setTimeout(() => window.lucide && lucide.createIcons(), 50)">
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md" wire:click="closeForm"></div>
            <div class="glass relative z-10 w-full max-w-lg rounded-[2.5rem] p-8 lg:p-10 shadow-2xl border-white/10 animate__animated animate__zoomIn animate__faster">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-display font-bold text-gray-900">
                        {{ $editingId ? 'Refine Group' : 'Initialize Group' }}</h3>
                    <button wire:click="closeForm"
                        class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-all">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Group Name</label>
                        <input type="text" wire:model="name"
                            class="w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50 text-white"
                            placeholder="e.g. Science Class 2024">
                        @error('name') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Description (Optional)</label>
                        <textarea wire:model="description" rows="3"
                            class="w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50 text-white"
                            placeholder="Brief overview of this group..."></textarea>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" wire:click="closeForm"
                            class="flex-1 py-4 bg-gray-100/50 rounded-2xl text-xs font-bold text-gray-500 hover:text-gray-900 transition-all border border-gray-200">Cancel</button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="flex-[2] py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-xs font-bold transition-all shadow-xl shadow-indigo-600/30 flex items-center justify-center gap-2 disabled:opacity-70">
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

