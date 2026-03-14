<div class="space-y-12 animate__animated animate__fadeIn">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-display font-bold text-white mb-2">Assessment Inventory</h2>
            <p class="text-sm text-gray-400">Select a domain challenge from the active strategic phases below.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 glass rounded-xl border-white/5 text-xs font-bold text-gray-500 uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i> Sentinel Verified
            </span>
        </div>
    </div>

    @if(session('error'))
        <div class="glass border-l-4 border-red-500 rounded-2xl p-6 flex items-center gap-4 animate__animated animate__shakeX">
            <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center text-red-500">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <p class="text-red-300 text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    @forelse($activeRounds as $round)
        <div class="space-y-6">
            <div class="flex items-center gap-4 group">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>
                <div class="flex items-center gap-3">
                    <h3 class="text-lg font-display font-black text-white uppercase tracking-[0.3em] group-hover:text-indigo-400 transition-colors">{{ $round->name }}</h3>
                    @if($round->is_final)
                        <span class="px-3 py-1 bg-purple-500/10 border border-purple-500/20 text-purple-400 text-[8px] font-bold uppercase tracking-widest rounded-lg">Grand Finale</span>
                    @endif
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                </div>
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($round->subjects as $subject)
                    @php
                        $completed = $completedExams->where('round_id', $round->id)->where('subject_id', $subject->id)->first();
                    @endphp
                    <div class="glass-card rounded-[2.5rem] p-8 border border-white/5 relative overflow-hidden group hover:bg-white/[0.02] transition-all">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-8">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 flex items-center justify-center text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                                    @if($completed)
                                        <i data-lucide="check-check" class="w-6 h-6"></i>
                                    @else
                                        <i data-lucide="terminal" class="w-6 h-6"></i>
                                    @endif
                                </div>
                                <div class="flex flex-col items-end">
                                    @if($completed)
                                        <span class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[8px] font-bold uppercase tracking-widest rounded-lg">Archive Ready</span>
                                    @else
                                        <span class="px-3 py-1 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[8px] font-bold uppercase tracking-widest rounded-lg">Unlocked</span>
                                    @endif
                                </div>
                            </div>

                            <h4 class="text-xl font-display font-bold text-white mb-2 leading-tight group-hover:text-indigo-400 transition-colors">{{ $subject->name }}</h4>
                            <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-6 block">{{ $subject->questions()->count() }} Strategic Nodes Detected</p>
                            
                            @if($completed)
                                <div class="mt-4 pt-6 border-t border-white/5 flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span class="text-[8px] font-bold text-gray-700 uppercase tracking-widest">Performance Score</span>
                                        <span class="text-xl font-display font-black text-emerald-400">{{ number_format($completed->total_score ?? 0, 1) }}</span>
                                    </div>
                                    <div class="w-10 h-10 glass rounded-xl flex items-center justify-center text-gray-600">
                                        <i data-lucide="lock" class="w-4 h-4"></i>
                                    </div>
                                </div>
                            @else
                                <button wire:click="startExam({{ $round->id }}, {{ $subject->id }})"
                                    class="w-full mt-2 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-[10px] font-bold uppercase tracking-widest transition-all shadow-xl shadow-indigo-600/20 active:scale-95 flex items-center justify-center gap-2">
                                    Commence Analysis <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                </button>
                            @endif
                        </div>

                        <!-- Decorative Elements -->
                        <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-600/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-purple-600/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="glass-card rounded-[3rem] p-24 text-center border-dashed border-white/10">
            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-8">
                <i data-lucide="monitor-x" class="w-10 h-10 text-gray-700"></i>
            </div>
            <h3 class="text-xl font-display font-bold text-gray-500 mb-2">No Challenges Active</h3>
            <p class="text-gray-600 max-w-md mx-auto">The strategic grid is currently undergoing maintenance or no phases have been authorized for your sector yet.</p>
        </div>
    @endforelse
</div>