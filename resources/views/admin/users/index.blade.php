<x-layouts.app>
@section('title', 'User List')

<div class="space-y-8">

    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900 mb-1">User List</h2>
            <p class="text-sm text-gray-500">Active team/staff accounts. Permissions are managed per role.</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl transition-all flex items-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Add User
        </a>
    </div>

    <div class="glass rounded-[2rem] overflow-hidden border border-white/5">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5">
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">User</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Contact</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Role</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Status</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($users as $user)
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        <td class="py-5 px-8">
                            <div class="flex items-center gap-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff"
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
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-[10px] font-bold uppercase tracking-wider rounded-lg">
                                Active
                            </span>
                        </td>
                        <td class="py-5 px-8 text-right">
                            @if($user->role !== 'admin' && $user->team)
                                <a href="{{ route('admin.users.reject', $user->team) }}"
                                    onclick="return confirm('Revoke access for {{ $user->name }}?')"
                                    class="w-9 h-9 glass rounded-lg inline-flex items-center justify-center text-red-500 hover:bg-red-600 hover:text-white transition-all">
                                    <i data-lucide="ban" class="w-4 h-4"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
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
</x-layouts.app>
