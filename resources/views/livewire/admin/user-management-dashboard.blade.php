<div class="space-y-10 pb-12">
    <!-- Hero / Welcome Section -->
    <div class="relative overflow-hidden glass rounded-[2rem] p-8 lg:p-12 mb-10">
        <div class="relative z-10 max-w-2xl">
            <h2
                class="text-4xl lg:text-5xl font-display font-bold text-gray-900 mb-4 animate__animated animate__fadeInDown">
                Welcome back, <span class="gradient-text">{{ explode(' ', auth()->user()->name)[0] }}!</span>
            </h2>
            <p class="text-lg text-gray-600 mb-8 animate__animated animate__fadeInUp animate__delay-1s">
                Here's a snapshot of your team and participants.
            </p>
            <div class="flex flex-wrap gap-4 animate__animated animate__fadeInUp animate__delay-1s">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.users.create') }}"
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-semibold transition-all shadow-lg shadow-indigo-600/30 flex items-center gap-2">
                        <i data-lucide="user-plus" class="w-5 h-5"></i> Add User
                    </a>
                @endif
                <a href="{{ route('admin.exam-dashboard') }}"
                    class="px-6 py-3 bg-indigo-50 hover:bg-indigo-100 text-gray-900 rounded-xl font-semibold transition-all border border-indigo-100 flex items-center gap-2">
                    <i data-lucide="database" class="w-5 h-5"></i> Go to Exam Dashboard
                </a>
            </div>
        </div>
        <!-- Decorative Elements -->
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-indigo-600/20 rounded-full blur-[80px]"></div>
        <div class="absolute -right-10 top-10 w-40 h-40 bg-purple-600/20 rounded-full blur-[60px] animate-pulse"></div>
        <i data-lucide="users" class="absolute right-20 bottom-10 w-32 h-32 text-indigo-500/10 rotate-12"></i>
    </div>

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="glass-card rounded-[1.5rem] p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center">
                    <i data-lucide="user-round-check" class="w-6 h-6 text-indigo-500"></i>
                </div>
            </div>
            <h3 class="text-3xl font-display font-bold text-gray-900 mb-1">{{ number_format($totalTeamUsers) }}</h3>
            <p class="text-xs text-gray-500 uppercase tracking-widest font-bold">Total Team Members</p>
        </div>
        <div class="glass-card rounded-[1.5rem] p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-500/10 flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-rose-500"></i>
                </div>
            </div>
            <h3 class="text-3xl font-display font-bold text-gray-900 mb-1">{{ number_format($totalStudents) }}</h3>
            <p class="text-xs text-gray-500 uppercase tracking-widest font-bold">Total Participants</p>
        </div>
    </div>

    <!-- Category Wise Total User -->
    <div>
        <div class="flex items-center gap-3 mb-6">
            <span class="h-2 w-8 rounded-full bg-indigo-500"></span>
            <h3 class="text-xl font-display font-bold text-gray-900">Category Wise Total User</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($categories as $category)
                <div class="glass-card rounded-[1.5rem] p-6 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-500 flex items-center justify-center shrink-0">
                        <i data-lucide="user-round-check" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">{{ $category['label'] }}</p>
                        <h4 class="text-2xl font-display font-bold text-gray-900">{{ number_format($category['count']) }}</h4>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No team categories found yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Group Wise Participant Data -->
    <div>
        <div class="flex items-center gap-3 mb-6">
            <span class="h-2 w-8 rounded-full bg-rose-500"></span>
            <h3 class="text-xl font-display font-bold text-gray-900">Group Wise Participant Data</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($groups as $group)
                <div class="glass-card rounded-[1.5rem] p-6 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-rose-500 flex items-center justify-center shrink-0">
                        <i data-lucide="users" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">{{ $group['label'] }}</p>
                        <h4 class="text-2xl font-display font-bold text-gray-900">{{ number_format($group['count']) }}</h4>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No groups found yet.</p>
            @endforelse
        </div>
    </div>
</div>
