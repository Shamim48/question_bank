<div class="space-y-6 max-w-4xl">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-gray-900">Roles & Permissions</h2>
            <p class="text-sm text-gray-500 mt-0.5">Manage team roles and assign permissions to each role.</p>
        </div>
        @if(!$showForm)
            <button wire:click="openCreate"
                class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Role
            </button>
        @endif
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium">
            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Create / Edit Form --}}
    @if($showForm)
        <div class="glass-card rounded-2xl border border-indigo-100 p-6">
            <h4 class="text-sm font-bold text-gray-700 uppercase tracking-widest mb-4">
                {{ $editingId ? 'Edit Role' : 'New Role' }}
            </h4>
            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">
                            Role Name <span class="text-red-500">*</span>
                        </label>
                        <input wire:model="name" type="text" placeholder="e.g. Coordinator"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">
                            Display Name
                        </label>
                        <input wire:model="display_name" type="text" placeholder="e.g. Programme Coordinator"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all">
                        <p class="mt-1 text-[10px] text-gray-400">Shown in dropdowns. Defaults to Role Name if empty.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        {{ $editingId ? 'Update' : 'Create' }}
                    </button>
                    <button type="button" wire:click="cancel"
                        class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Roles Table --}}
    <div class="glass-card rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">#</th>
                    <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Role Name</th>
                    <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Display Name</th>
                    <th class="px-6 py-3 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($roles as $role)
                    @php $isSystem = in_array($role->name, ['admin', 'student']); @endphp
                    <tr class="hover:bg-gray-50 transition-colors {{ $managingPermissionsId === $role->id ? 'bg-indigo-50/50' : '' }}">
                        <td class="px-6 py-4 text-gray-400 text-xs">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-900">{{ $role->name }}</span>
                            @if($isSystem)
                                <span class="ml-2 px-1.5 py-0.5 rounded text-[9px] font-bold bg-gray-100 text-gray-500">system</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $role->display_name ?? '—' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($isSystem)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-600">Active</span>
                            @else
                                <button wire:click="toggleStatus({{ $role->id }})"
                                    class="px-2.5 py-1 rounded-full text-[10px] font-bold transition-colors
                                        {{ $role->status ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}">
                                    {{ $role->status ? 'Active' : 'Inactive' }}
                                </button>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if(!$isSystem)
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="managePermissions({{ $role->id }})"
                                        title="Manage Permissions"
                                        class="p-2 rounded-lg transition-colors {{ $managingPermissionsId === $role->id ? 'bg-indigo-600 text-white' : 'bg-indigo-50 hover:bg-indigo-100 text-indigo-600' }}">
                                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button wire:click="edit({{ $role->id }})"
                                        class="p-2 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 transition-colors">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button wire:click="delete({{ $role->id }})"
                                        wire:confirm="Delete '{{ $role->name }}'?"
                                        class="p-2 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 transition-colors">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400 text-sm">No roles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Permission Management Panel --}}
    @if($managingPermissionsId)
        <div class="glass-card rounded-2xl border border-indigo-200 overflow-hidden">
            {{-- Panel Header --}}
            <div class="flex items-center justify-between px-6 py-4 bg-indigo-50 border-b border-indigo-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <i data-lucide="shield-check" class="w-4 h-4 text-indigo-600"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900">Permissions — {{ $managingPermissionsName }}</h4>
                        <p class="text-xs text-gray-500">Check the pages this role can access</p>
                    </div>
                </div>
                <button wire:click="closePermissions" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            {{-- Permission Groups --}}
            <div class="p-6 space-y-6">
                @foreach($groupedPermissions as $group => $perms)
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">{{ $group }}</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach($perms as $perm)
                                <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                                    <input type="checkbox"
                                        wire:model="rolePermissions"
                                        value="{{ $perm->name }}"
                                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                    <span class="text-sm text-gray-700 group-hover:text-indigo-700 transition-colors">
                                        {{ $perm->label }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @if(!$loop->last)
                        <div class="border-t border-gray-100"></div>
                    @endif
                @endforeach
            </div>

            {{-- Save / Cancel --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center gap-3">
                <button wire:click="savePermissions"
                    class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-sm">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i> Save Permissions
                </button>
                <button wire:click="closePermissions"
                    class="px-5 py-2.5 bg-white hover:bg-gray-100 border border-gray-200 text-gray-600 text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                    Cancel
                </button>
                <span class="ml-auto text-xs text-gray-400">
                    {{ count($rolePermissions) }} permission(s) selected
                </span>
            </div>
        </div>
    @endif

</div>
