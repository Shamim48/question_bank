<div class="space-y-10 animate__animated animate__fadeIn">
    @php $canControl = auth()->user()->isAdmin() || auth()->user()->hasPermission('exams-control') @endphp
    <!-- Hero / Action Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-display font-bold text-white mb-2">Operational Command</h2>
            <p class="text-sm text-gray-400">Real-time control over competition phases and live activity</p>
        </div>
        <div class="flex items-center gap-3">
            <span
                class="flex items-center gap-2 px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-widest rounded-xl">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span> System Live
            </span>
        </div>
    </div>

    <!-- Active Phases Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($rounds as $round)
            <div class="glass-card rounded-[2rem] p-8 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-[0.2em] mb-1 block">Phase
                                {{ $loop->iteration }}</span>
                            <h3 class="text-xl font-display font-bold text-white">{{ $round->name }}</h3>
                        </div>
                        @if($canControl)
                        <button wire:click="toggleRound({{ $round->id }})"
                            class="relative w-16 h-8 rounded-full transition-all duration-500 {{ $round->is_active ? 'bg-indigo-600 shadow-lg shadow-indigo-600/30' : 'bg-white/5 border border-white/10' }}">
                            <div
                                class="absolute top-1 w-6 h-6 bg-white rounded-full transition-all duration-500 shadow-md {{ $round->is_active ? 'left-9' : 'left-1' }}">
                            </div>
                        </button>
                        @else
                        <div class="relative w-16 h-8 rounded-full {{ $round->is_active ? 'bg-indigo-600/50' : 'bg-white/5 border border-white/10' }} cursor-not-allowed opacity-60" title="No permission">
                            <div class="absolute top-1 w-6 h-6 bg-white rounded-full shadow-md {{ $round->is_active ? 'left-9' : 'left-1' }}"></div>
                        </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="glass p-4 rounded-2xl border-white/5">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Domains</p>
                            <p class="text-lg font-bold text-white">{{ $round->subjects_count }}</p>
                        </div>
                        <div class="glass p-4 rounded-2xl border-white/5">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Challenges</p>
                            <p class="text-lg font-bold text-white">{{ $round->questions_count }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500 font-medium">Live Participation</span>
                            <span class="text-indigo-400 font-bold">{{ $round->exams_count }} Souls</span>
                        </div>
                        <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-600 rounded-full"
                                style="width: {{ min(100, $round->exams_count * 5) }}%"></div>
                        </div>
                    </div>

                    @if($round->is_final)
                        <div class="mt-6 pt-6 border-t border-white/5 flex items-center gap-2">
                            <i data-lucide="crown" class="w-4 h-4 text-purple-400"></i>
                            <span class="text-[10px] font-bold text-purple-400 uppercase tracking-widest">Grand Finale Logic
                                Enabled</span>
                        </div>
                    @endif
                </div>

                <!-- Decorative Layer -->
                <div
                    class="absolute -right-10 -bottom-10 w-32 h-32 bg-indigo-600/5 rounded-full blur-3xl group-hover:bg-indigo-600/10 transition-colors">
                </div>
            </div>
        @endforeach
    </div>

    <!-- Live Activity Feed -->
    <div class="glass-card rounded-[2.5rem] p-8 lg:p-12">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h3 class="text-2xl font-display font-bold text-white">Transmission Feed</h3>
                <p class="text-sm text-gray-500">Monitoring real-time exam status across all sectors</p>
            </div>
            <i data-lucide="activity" class="w-6 h-6 text-indigo-500 animate-pulse"></i>
        </div>

        <div class="space-y-6">
            @forelse($recentExams as $exam)
                <div
                    class="group flex flex-col md:flex-row md:items-center justify-between gap-6 p-6 rounded-3xl hover:bg-white/[0.02] border border-transparent hover:border-white/5 transition-all">
                    <div class="flex items-center gap-6">
                        <div class="relative">
                            <div
                                class="w-14 h-14 rounded-2xl bg-gradient-to-tr {{ $exam->status === 'completed' ? 'from-emerald-500 to-teal-400' : 'from-indigo-600 to-purple-500' }} flex items-center justify-center text-white text-xl font-bold shadow-lg">
                                {{ strtoupper(substr($exam->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div
                                class="absolute -right-1 -bottom-1 w-5 h-5 rounded-full border-4 border-[#0f172a] {{ $exam->status === 'completed' ? 'bg-emerald-500' : 'bg-indigo-500' }}">
                            </div>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-black group-hover:text-indigo-400 transition-colors">
                                {{ $exam->user->name ?? 'Unknown Sentinel' }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $exam->round->name }} <span
                                    class="mx-2 text-gray-700">/</span> {{ $exam->subject->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-8 px-6 md:px-0">
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-1">Current State</p>
                            <span
                                class="inline-flex px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $exam->status === 'completed' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-yellow-500/10 text-yellow-500' }}">
                                {{ str_replace('_', ' ', $exam->status) }}
                            </span>
                        </div>

                        @if($exam->total_score !== null)
                            <div class="text-right min-w-[80px]">
                                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-1">Performance</p>
                                <p class="text-xl font-display font-black text-indigo-400">{{ $exam->total_score }} <span
                                        class="text-[10px] text-gray-500 font-medium">PTS</span></p>
                            </div>
                        @endif

                        <a href="{{ route('admin.exams') }}"
                            class="w-10 h-10 glass rounded-xl flex items-center justify-center text-gray-500 hover:text-white hover:bg-indigo-600 transition-all">
                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center">
                    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="radio" class="w-10 h-10 text-gray-700"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Passive monitoring... No active transmissions detected.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>