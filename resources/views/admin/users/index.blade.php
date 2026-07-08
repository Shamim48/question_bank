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

    <div class="glass-card rounded-[2rem] border border-gray-100 overflow-hidden">
        @forelse($users as $user)
            <div class="p-6 border-b border-gray-100 last:border-0 flex items-start gap-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff"
                    class="w-14 h-14 rounded-2xl object-cover border border-indigo-100 shrink-0">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="font-bold text-gray-900">{{ $user->name }}</p>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700 uppercase">{{ $user->role }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $user->email }}
                        @if($user->phone) &middot; {{ $user->phone }} @endif
                    </p>
                </div>
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}"
                        class="w-9 h-9 flex items-center justify-center bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-xl transition-all">
                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                    </a>
                    @if($user->role !== 'admin' && $user->team)
                        <a href="{{ route('admin.users.reject', $user->team) }}"
                            onclick="return confirm('Revoke access for {{ $user->name }}?')"
                            class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-1">
                            <i data-lucide="ban" class="w-3.5 h-3.5"></i> Revoke
                        </a>
                    @endif
                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                            onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-9 h-9 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-700 rounded-xl transition-all">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p class="p-6 text-sm text-gray-400">No users found.</p>
        @endforelse
    </div>

    {{ $users->links() }}

</div>
</x-layouts.app>
