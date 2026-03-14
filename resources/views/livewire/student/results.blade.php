<div class="space-y-12 animate__animated animate__fadeIn">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-display font-bold text-white mb-2">Performance Transcript</h2>
            <p class="text-sm text-gray-400">Validated records of your strategic assessments and domain mastery.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-5 py-3 glass rounded-[1.5rem] border border-white/5 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400">
                    <i data-lucide="zap" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[8px] font-bold text-gray-500 uppercase tracking-widest leading-tight">Aggregate
                        Metric</p>
                    <p class="text-2xl font-display font-black text-white leading-none">
                        {{ number_format($totalScore, 1) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Marks Breakdown Section -->
    @if($marks->count() > 0)
        <div class="glass-card rounded-[2.5rem] overflow-hidden border border-white/5">
            <div class="p-8 lg:p-10 border-b border-white/5 bg-white/[0.02] flex items-center justify-between">
                <h3 class="text-xl font-display font-bold text-white">Appraisal Inventory</h3>
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ $marks->count() }} Domain
                    Records</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white/[0.01]">
                            <th class="py-5 px-8 text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Tier /
                                Round</th>
                            <th class="py-5 px-8 text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Subject
                                Domain</th>
                            <th class="py-5 px-8 text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] text-right">
                                Autonomous</th>
                            <th class="py-5 px-8 text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] text-right">
                                Manual</th>
                            <th class="py-5 px-8 text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] text-right">
                                Final Logic</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($marks as $mark)
                            <tr class="group hover:bg-white/[0.02] transition-colors">
                                <td class="py-6 px-8">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-2 h-2 rounded-full bg-indigo-500 group-hover:scale-150 transition-transform">
                                        </div>
                                        <span
                                            class="text-sm font-bold text-white uppercase tracking-wider">{{ $mark->round->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="py-6 px-8 text-sm text-gray-400 font-medium">{{ $mark->subject->name ?? 'N/A' }}</td>
                                <td class="py-6 px-8 text-right text-sm text-gray-500 mono">
                                    {{ number_format($mark->online_marks, 2) }}</td>
                                <td class="py-6 px-8 text-right text-sm text-purple-400 mono">
                                    +{{ number_format($mark->manual_marks, 2) }}</td>
                                <td class="py-6 px-8 text-right">
                                    <span
                                        class="text-base font-display font-black text-emerald-400">{{ number_format($mark->total_marks, 2) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Operational History -->
    <div>
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-2xl font-display font-bold text-white">Deployment Log</h3>
            <p class="text-xs text-indigo-400 font-bold uppercase tracking-widest">Temporal Feed</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($exams as $exam)
                <div
                    class="glass-card rounded-[2rem] p-8 border border-white/5 relative overflow-hidden group hover:border-indigo-500/30 transition-all">
                    <div class="relative z-10 flex items-center justify-between">
                        <div class="flex items-center gap-5">
                            <div
                                class="w-14 h-14 rounded-2xl bg-indigo-600/10 flex items-center justify-center text-indigo-400 group-hover:scale-110 transition-transform">
                                <i data-lucide="activity" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-white group-hover:text-indigo-400 transition-colors">
                                    {{ $exam->subject->name ?? 'Unknown Domain' }}</h4>
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">
                                    {{ $exam->completed_at ? $exam->completed_at->format('M d, Y · H:i') : 'Timestamp Corrupted' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p
                                class="text-2xl font-display font-black text-white leading-none group-hover:translate-x-[-4px] transition-transform">
                                {{ number_format($exam->total_score ?? 0, 1) }}</p>
                            <p class="text-[10px] font-bold text-gray-700 uppercase tracking-widest mt-1">Weighted</p>
                        </div>
                    </div>
                    <!-- Background decoration -->
                    <div
                        class="absolute -right-10 -bottom-10 w-32 h-32 bg-indigo-600/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity">
                    </div>
                </div>
            @empty
                <div class="glass-card rounded-[2rem] p-16 text-center col-span-2 border-dashed border-white/10">
                    <i data-lucide="history" class="w-12 h-12 text-gray-700 mx-auto mb-4"></i>
                    <p class="text-gray-500 font-medium italic">No deployment history found in current sector.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>