<x-layouts.app>
    @section('title', 'Seasons')

    <div class="fade-in">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold gradient-text">Seasons</h1>
                <p class="text-indigo-300/60 text-sm mt-1">Manage all seasons</p>
            </div>
            <a href="{{ route('admin.seasons.create') }}"
                class="btn-glow flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Season
            </a>
        </div>

        <!-- Table Card -->
        <div class="glass rounded-2xl overflow-hidden">
            <div class="p-6 border-b border-indigo-500/20">
                <h2 class="text-lg font-semibold" style="color:#000">All Seasons</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-indigo-500/20">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color:#000">#</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color:#000">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color:#000">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color:#000">Created</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider" style="color:#000">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-indigo-500/10">
                        @forelse ($seasons as $index => $season)
                            <tr class="hover:bg-indigo-500/5 transition-colors">
                                <td class="px-6 py-4" style="color:#000">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-medium" style="color:#000">{{ $season->name }}</td>
                                <td class="px-6 py-4">
                                    @if ($season->status == 1)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 pulse-dot"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-400 border border-red-500/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs" style="color:#000">
                                    {{ $season->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.seasons.edit', $season->id) }}"
                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-500/20 hover:bg-indigo-500/40 text-indigo-300 text-xs font-medium transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.seasons.destroy', $season->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this season?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-500/20 hover:bg-red-500/40 text-red-400 text-xs font-medium transition-all">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center" style="color:#000">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    No seasons found. <a href="{{ route('admin.seasons.create') }}" class="text-indigo-400 hover:underline">Add one now.</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
