<x-layouts.app>
    @section('title', 'Edit Season')

    <div class="fade-in max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.seasons.index') }}"
                class="flex items-center justify-center w-9 h-9 rounded-xl glass hover:bg-indigo-500/20 text-indigo-300 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold gradient-text">Edit Season</h1>
                <p class="text-indigo-300/60 text-sm mt-1">Update "{{ $season->name }}"</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="glass rounded-2xl p-8">
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30">
                    <ul class="text-red-400 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.seasons.update', $season->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color:#000">
                            Season Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $season->name) }}"
                            placeholder="e.g. Season 1, Season 2025..."
                            class="w-full bg-indigo-950/50 border border-indigo-500/30 rounded-xl px-4 py-3 placeholder-indigo-400/40 text-sm focus:outline-none"
                            style="color:#000"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color:#000">Status</label>
                        <select name="status"
                            class="w-full bg-indigo-950/50 border border-indigo-500/30 rounded-xl px-4 py-3 text-sm focus:outline-none"
                            style="color:#000">
                            <option value="1" {{ old('status', $season->status) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $season->status) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-8">
                    <button type="submit"
                        class="btn-glow flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Season
                    </button>
                    <a href="{{ route('admin.seasons.index') }}"
                        class="px-6 py-2.5 rounded-xl glass-light hover:bg-indigo-500/10 text-indigo-300 text-sm font-medium transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
