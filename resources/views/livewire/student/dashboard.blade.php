<div class="space-y-10 animate__animated animate__fadeIn">
    <!-- Hero / Welcome Banner -->
    <div class="relative overflow-hidden glass rounded-[2.5rem] p-8 lg:p-12 mb-10 border border-white/10 group">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="max-w-xl">
                <div class="flex items-center gap-3 mb-6">
                    <span
                        class="px-4 py-1.5 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase tracking-widest rounded-full">
                        Candidate Portal
                    </span>
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                </div>
                <h2 class="text-4xl lg:text-5xl font-display font-black text-white mb-4 leading-tight">
                    Welcome back,<br />
                    <span class="gradient-text">{{ auth()->user()->name }}</span>
                </h2>
                <p class="text-gray-400 text-lg leading-relaxed">
                    Continuity of learning is the path to mastery. Your strategic overviews and pending challenges are
                    indexed below.
                </p>
                <div class="flex gap-4 mt-8">
                    <a href="{{ route('student.exams') }}"
                        class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-sm font-bold transition-all shadow-xl shadow-indigo-600/20 flex items-center gap-2 group/btn">
                        Commence Assessment <i data-lucide="arrow-right"
                            class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
            <div class="relative">
                <div
                    class="w-40 h-40 lg:w-56 lg:h-56 rounded-[3rem] bg-gradient-to-br from-indigo-500/20 to-purple-600/20 backdrop-blur-3xl flex items-center justify-center border border-white/10 relative z-20 animate-float">
                    <i data-lucide="graduation-cap" class="w-20 h-20 lg:w-28 lg:h-28 text-white/80"></i>
                </div>
                <!-- Background decoration -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-600/10 rounded-full blur-3xl animate-pulse">
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
            class="glass-card rounded-[2rem] p-8 border border-white/5 relative overflow-hidden group hover:border-indigo-500/30 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-400">
                    <i data-lucide="zap" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Aggregate Score</span>
            </div>
            <p class="text-4xl font-display font-black text-white">{{ number_format($totalScore, 1) }}</p>
            <p class="text-xs text-gray-500 mt-2">Cumulative performance metric</p>
        </div>

        <div
            class="glass-card rounded-[2rem] p-8 border border-white/5 relative overflow-hidden group hover:border-emerald-500/30 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Achievements</span>
            </div>
            <p class="text-4xl font-display font-black text-white">
                {{ $recentExams->where('status', 'completed')->count() }}</p>
            <p class="text-xs text-gray-500 mt-2">Validated certifications and exams</p>
        </div>

        <div
            class="glass-card rounded-[2rem] p-8 border border-white/5 relative overflow-hidden group hover:border-purple-500/30 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-400">
                    <i data-lucide="layout" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Active Phases</span>
            </div>
            <p class="text-4xl font-display font-black text-white">{{ $activeRounds->count() }}</p>
            <p class="text-xs text-gray-500 mt-2">Current available competition tiers</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Active Rounds Registry -->
        <div class="lg:col-span-12">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-2xl font-display font-bold text-white">Active Tiers</h3>
                    <p class="text-sm text-gray-400">Currently live and accessible phases</p>
                </div>
                <a href="{{ route('student.exams') }}"
                    class="text-xs font-bold text-indigo-400 uppercase tracking-widest hover:text-white transition-colors">Expand
                    Full Roster</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($activeRounds as $round)
                    <div
                        class="glass-card rounded-[2rem] p-8 border border-white/5 group relative overflow-hidden hover:bg-white/[0.02] transition-colors">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-6">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 group-hover:scale-110 transition-all duration-500">
                                    <i data-lucide="layers" class="w-6 h-6"></i>
                                </div>
                                @if($round->is_final)
                                    <span
                                        class="px-3 py-1 bg-purple-500/10 border border-purple-500/20 text-purple-400 text-[8px] font-bold uppercase tracking-widest rounded-lg">Championship</span>
                                @else
                                    <span
                                        class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[8px] font-bold uppercase tracking-widest rounded-lg">Live</span>
                                @endif
                            </div>
                            <h4 class="text-xl font-display font-bold text-white mb-3">{{ $round->name }}</h4>
                            <p class="text-sm text-gray-500 leading-relaxed mb-8 line-clamp-2">
                                {{ $round->description ?: 'Mission parameters for this phase are being finalized.' }}</p>

                            <a href="{{ route('student.exams') }}"
                                class="inline-flex items-center gap-2 text-xs font-bold text-indigo-400 uppercase tracking-widest hover:text-white transition-colors group-hover:gap-4 duration-300">
                                Enter Phase <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                        <!-- Background decoration -->
                        <div
                            class="absolute -right-10 -bottom-10 w-32 h-32 bg-indigo-600/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity">
                        </div>
                    </div>
                @empty
                    <div class="glass-card rounded-[2rem] p-12 text-center col-span-3 border-dashed border-white/10">
                        <i data-lucide="clock" class="w-12 h-12 text-gray-700 mx-auto mb-4"></i>
                        <p class="text-gray-500 font-medium">No active competition rounds detected in system.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Comprehensive Appraisal Feed -->
        <div class="lg:col-span-12 mt-10">
            <div class="glass-card rounded-[2.5rem] p-8 lg:p-12 overflow-hidden border border-white/5 relative">
                <div class="flex items-center justify-between mb-12">
                    <div>
                        <h3 class="text-2xl font-display font-bold text-white">Appraisal History</h3>
                        <p class="text-sm text-gray-400">Historical performance data and domain scores</p>
                    </div>
                    <div class="w-12 h-12 glass rounded-2xl flex items-center justify-center text-indigo-400">
                        <i data-lucide="trending-up" class="w-6 h-6"></i>
                    </div>
                </div>

                @if($marks->count() > 0)
                    <div class="space-y-6">
                        @foreach($marks as $mark)
                            <div
                                class="group flex flex-col md:flex-row md:items-center justify-between gap-6 p-6 rounded-3xl bg-white/[0.02] border border-white/5 hover:border-indigo-500/30 transition-all">
                                <div class="flex items-center gap-6">
                                    <div
                                        class="w-14 h-14 rounded-2xl bg-indigo-600/10 flex items-center justify-center text-indigo-400 text-xl font-bold">
                                        {{ strtoupper(substr($mark->subject->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-white group-hover:text-indigo-400 transition-colors">
                                            {{ $mark->subject->name ?? 'Core Domain' }}</h4>
                                        <p class="text-[10px] text-gray-600 uppercase tracking-widest mt-1">
                                            {{ $mark->round->name ?? 'Standard Phase' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-12 px-6 md:px-0">
                                    <div class="text-right">
                                        <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-1">Breakdown
                                        </p>
                                        <p class="text-xs text-gray-500">On: {{ number_format($mark->online_marks, 1) }} | Man:
                                            {{ number_format($mark->manual_marks, 1) }}</p>
                                    </div>
                                    <div class="text-right min-w-[100px]">
                                        <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-1">Weighted
                                            Total</p>
                                        <p class="text-2xl font-display font-black text-indigo-400">
                                            {{ number_format($mark->total_marks, 1) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-20 text-center">
                        <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="bar-chart-3" class="w-10 h-10 text-gray-700"></i>
                        </div>
                        <p class="text-gray-500 font-medium text-lg italic">Passive monitoring... No appraisal transcripts
                            found.</p>
                        <a href="{{ route('student.exams') }}"
                            class="mt-4 inline-block text-indigo-400 text-xs font-bold uppercase tracking-widest hover:text-white transition-colors">Initialize
                            Performance Benchmarking</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>