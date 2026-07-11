<div class="space-y-8">

    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900 mb-1">User List</h2>
            <p class="text-sm text-gray-500">Active team/staff accounts. Permissions are managed per role.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="export"
                class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-xl transition-all flex items-center gap-2">
                <i data-lucide="file-down" class="w-4 h-4"></i> Export Excel
            </button>
            <a href="{{ route('admin.users.create') }}"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl transition-all flex items-center gap-2">
                <i data-lucide="user-plus" class="w-4 h-4"></i> Add User
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass rounded-3xl p-2">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-2">
            <div class="md:col-span-4 relative group">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 group-focus-within:text-indigo-400 transition-colors"></i>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search name, email, phone..."
                    class="w-full bg-white/5 border-none rounded-2xl pl-12 pr-4 py-3.5 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-500/50">
            </div>
            <div class="md:col-span-3">
                <select wire:model.live="filterRole"
                    class="w-full bg-white/5 border-none rounded-2xl px-4 py-3.5 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-500/50">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->display_name ?: $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3">
                <select wire:model.live="filterStatus"
                    class="w-full bg-white/5 border-none rounded-2xl px-4 py-3.5 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-500/50">
                    <option value="">All Statuses</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="md:col-span-2 flex items-center justify-center">
                <button wire:click="$refresh" class="w-full h-full glass rounded-2xl flex items-center justify-center text-gray-400 hover:text-white hover:bg-indigo-600 transition-all">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </div>

    @if(count($selectedIds) > 0)
        <div class="flex items-center justify-between px-6 py-4 bg-red-50 border border-red-200 rounded-2xl">
            <p class="text-sm font-semibold text-red-700">{{ count($selectedIds) }} user(s) selected</p>
            <button wire:click="bulkDelete" wire:confirm="Delete {{ count($selectedIds) }} selected user(s)? This cannot be undone."
                class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Delete Selected
            </button>
        </div>
    @endif

    <div class="glass rounded-[2rem] overflow-hidden border border-white/5">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5">
                    <th class="py-5 px-8 w-10">
                        <input type="checkbox" wire:model.live="selectAll" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500/50">
                    </th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">User</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Contact</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Role</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Referral Code</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Status</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($users as $user)
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        <td class="py-5 px-8">
                            <input type="checkbox" wire:model.live="selectedIds" value="{{ $user->id }}" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500/50">
                        </td>
                        <td class="py-5 px-8">
                            <div class="flex items-center gap-4">
                                <img src="{{ $user->team?->image ? Storage::url($user->team->image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff' }}"
                                    class="w-10 h-10 rounded-xl object-cover border border-gray-200 shrink-0">
                                <p class="text-sm font-bold text-gray-900">{{ $user->name }}</p>
                            </div>
                        </td>
                        <td class="py-5 px-8">
                            <p class="text-xs text-gray-700">{{ $user->email }}</p>
                            <p class="text-[10px] text-gray-500">{{ $user->phone ?: '—' }}</p>
                        </td>
                        <td class="py-5 px-8">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-500/10 border border-indigo-500/20 text-indigo-600 text-[10px] font-bold uppercase tracking-wider rounded-lg">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="py-5 px-8">
                            @if($user->referral_code)
                                <p class="text-xs font-mono font-semibold text-gray-700">{{ $user->referral_code }}</p>
                                <p class="text-[10px] text-gray-500">{{ $user->commissions_count }} referral(s)</p>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="py-5 px-8">
                            @php
                                $statusLabel = match(true) {
                                    $user->role === 'admin' => 'Active',
                                    $user->team?->status === 1 => 'Approved',
                                    $user->team?->status === 2 => 'Rejected',
                                    default => 'Pending',
                                };
                                $statusClass = match($statusLabel) {
                                    'Rejected' => 'bg-red-500/10 border-red-500/20 text-red-500',
                                    'Pending'  => 'bg-amber-500/10 border-amber-500/20 text-amber-500',
                                    default    => 'bg-emerald-500/10 border-emerald-500/20 text-emerald-600',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 border text-[10px] font-bold uppercase tracking-wider rounded-lg {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="py-5 px-8 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="w-9 h-9 glass rounded-lg inline-flex items-center justify-center text-indigo-500 hover:bg-indigo-600 hover:text-white transition-all">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                                @if($user->role !== 'admin' && $user->team)
                                    <a href="{{ route('admin.users.reject', $user->team) }}"
                                        onclick="return confirm('Revoke access for {{ $user->name }}?')"
                                        class="w-9 h-9 glass rounded-lg inline-flex items-center justify-center text-red-500 hover:bg-red-600 hover:text-white transition-all">
                                        <i data-lucide="ban" class="w-4 h-4"></i>
                                    </a>
                                @endif
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                        onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-9 h-9 glass rounded-lg inline-flex items-center justify-center text-red-500 hover:bg-red-600 hover:text-white transition-all">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-20 text-center">
                            <i data-lucide="users" class="w-12 h-12 text-gray-700 mx-auto mb-4"></i>
                            <p class="text-gray-500 font-medium">No users found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="flex justify-center">
            {{ $users->links() }}
        </div>
    @endif

</div>
