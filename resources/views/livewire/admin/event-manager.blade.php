<div class="space-y-8 animate__animated animate__fadeIn">
    @php
        $u = auth()->user();
        $canCreate = $u->isAdmin() || $u->hasPermission('events-create');
        $canEdit   = $u->isAdmin() || $u->hasPermission('events-edit');
        $canDelete = $u->isAdmin() || $u->hasPermission('events-delete');
    @endphp
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900 mb-2">Event Manager</h2>
            <p class="text-sm text-gray-400">Manage events shown on the public website</p>
        </div>
        @if($canCreate)
        <button wire:click="openForm" wire:loading.attr="disabled"
            class="flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/30 disabled:opacity-50">
            <i data-lucide="plus-circle" class="w-5 h-5" wire:loading.remove wire:target="openForm"></i>
            <div wire:loading wire:target="openForm" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
            New Event
        </button>
        @endif
    </div>

    <!-- Content Table -->
    <div class="glass rounded-[2rem] overflow-hidden border border-white/5">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5">
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Event</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Class / Season</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Schedule</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Status</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($events as $event)
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        <td class="py-5 px-8">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 group-hover:scale-110 transition-transform">
                                    <i data-lucide="calendar-days" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $event->name }}</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">{{ $event->category ?: '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-8">
                            <p class="text-xs text-gray-700">{{ $event->classLevel->name ?? '—' }}</p>
                            <p class="text-[10px] text-gray-500">{{ $event->season->name ?? '—' }}</p>
                        </td>
                        <td class="py-5 px-8">
                            <p class="text-xs text-gray-700">{{ optional($event->start_date)->format('d M Y, h:i A') ?: '—' }}</p>
                            <p class="text-[10px] text-gray-500">to {{ optional($event->end_date)->format('d M Y, h:i A') ?: '—' }}</p>
                        </td>
                        <td class="py-5 px-8">
                            <button
                                @if($canEdit) wire:click="toggleStatus({{ $event->id }})" @endif
                                @disabled(!$canEdit)
                                class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg transition-all disabled:cursor-not-allowed {{ $event->status ? 'bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 hover:bg-emerald-500/20' : 'bg-gray-500/10 border border-gray-500/20 text-gray-400 hover:bg-gray-500/20' }}">
                                <i data-lucide="{{ $event->status ? 'check-circle' : 'circle-slash' }}" class="w-3 h-3"></i>
                                {{ $event->status ? 'Published' : 'Hidden' }}
                            </button>
                        </td>
                        <td class="py-5 px-8 text-right">
                            @if($canEdit || $canDelete)
                            <div class="flex items-center justify-end gap-2">
                                @if($canEdit)
                                <button wire:click="openForm({{ $event->id }})"
                                    class="w-9 h-9 glass rounded-lg flex items-center justify-center text-indigo-400 hover:bg-indigo-600 hover:text-white transition-all">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>
                                @endif
                                @if($canDelete)
                                <button wire:click="delete({{ $event->id }})" wire:confirm="Delete this event?"
                                    wire:loading.attr="disabled" wire:target="delete({{ $event->id }})"
                                    class="w-9 h-9 glass rounded-lg flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white transition-all disabled:opacity-50">
                                    <i data-lucide="trash-2" class="w-4 h-4" wire:loading.remove wire:target="delete({{ $event->id }})"></i>
                                    <div wire:loading wire:target="delete({{ $event->id }})" class="w-4 h-4 border-2 border-red-500/30 border-t-red-500 rounded-full animate-spin"></div>
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
                        <td colspan="5" class="py-20 text-center">
                            <i data-lucide="calendar-days" class="w-12 h-12 text-gray-700 mx-auto mb-4"></i>
                            <p class="text-gray-500 font-medium">No events created yet.</p>
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
            <div class="glass relative z-10 w-full max-w-lg rounded-[2.5rem] p-8 lg:p-10 shadow-2xl border-white/10 animate__animated animate__zoomIn animate__faster max-h-[85vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-display font-bold text-gray-900">
                        {{ $editingId ? 'Edit Event' : 'New Event' }}</h3>
                    <button wire:click="closeForm"
                        class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-all">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Event Name</label>
                        <input type="text" wire:model="name"
                            class="w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50 text-gray-900"
                            placeholder="e.g. Education Game">
                        @error('name') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Category</label>
                            <input type="text" wire:model="category"
                                class="w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50 text-gray-900"
                                placeholder="e.g. Education Game">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Website Link</label>
                            <input type="text" wire:model="url"
                                class="w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50 text-gray-900"
                                placeholder="https://...">
                            @error('url') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Start Date &amp; Time</label>
                            <input type="datetime-local" wire:model="start_date"
                                class="w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50 text-gray-900">
                            @error('start_date') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">End Date &amp; Time</label>
                            <input type="datetime-local" wire:model="end_date"
                                class="w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50 text-gray-900">
                            @error('end_date') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Class (Optional)</label>
                            <select wire:model="class_id"
                                class="w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50 text-gray-900">
                                <option value="">—</option>
                                @foreach($classLevels as $classLevel)
                                    <option value="{{ $classLevel->id }}">{{ $classLevel->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Season (Optional)</label>
                            <select wire:model="season_id"
                                class="w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50 text-gray-900">
                                <option value="">—</option>
                                @foreach($seasons as $season)
                                    <option value="{{ $season->id }}">{{ $season->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="status" class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500/50">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Published (visible on website)</span>
                        </label>
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
