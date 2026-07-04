<x-layouts.app>
    @section('title', 'Add User')

    <div class="fade-in max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center justify-center w-9 h-9 rounded-xl glass hover:bg-indigo-500/20 text-indigo-300 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold gradient-text">Add User</h1>
                <p class="text-indigo-300/60 text-sm mt-1">Create a team/staff account — active immediately with the selected role's permissions</p>
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

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color:#000">
                            Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            placeholder="Full name"
                            class="w-full bg-indigo-950/50 border border-indigo-500/30 rounded-xl px-4 py-3 placeholder-indigo-400/40 text-sm focus:outline-none"
                            style="color:#000"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color:#000">
                            Email <span class="text-red-400">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            placeholder="name@example.com"
                            class="w-full bg-indigo-950/50 border border-indigo-500/30 rounded-xl px-4 py-3 placeholder-indigo-400/40 text-sm focus:outline-none"
                            style="color:#000"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color:#000">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            placeholder="01XXXXXXXXX"
                            class="w-full bg-indigo-950/50 border border-indigo-500/30 rounded-xl px-4 py-3 placeholder-indigo-400/40 text-sm focus:outline-none"
                            style="color:#000">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color:#000">
                            Role <span class="text-red-400">*</span>
                        </label>
                        <select name="role"
                            class="w-full bg-indigo-950/50 border border-indigo-500/30 rounded-xl px-4 py-3 text-sm focus:outline-none"
                            style="color:#000"
                            required>
                            <option value="">Select role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                    {{ $role->display_name ?? $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color:#000">
                                Password <span class="text-red-400">*</span>
                            </label>
                            <input type="password" name="password"
                                class="w-full bg-indigo-950/50 border border-indigo-500/30 rounded-xl px-4 py-3 text-sm focus:outline-none"
                                style="color:#000"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color:#000">
                                Confirm Password <span class="text-red-400">*</span>
                            </label>
                            <input type="password" name="password_confirmation"
                                class="w-full bg-indigo-950/50 border border-indigo-500/30 rounded-xl px-4 py-3 text-sm focus:outline-none"
                                style="color:#000"
                                required>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-8">
                    <button type="submit"
                        class="btn-glow flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-all">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        Create User
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                        class="px-6 py-2.5 rounded-xl glass-light hover:bg-indigo-500/10 text-indigo-300 text-sm font-medium transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
