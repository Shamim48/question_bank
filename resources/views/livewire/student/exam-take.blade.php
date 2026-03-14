<div class="min-h-[80vh] flex flex-col animate__animated animate__fadeIn">
    @if($examCompleted)
        <!-- Celebration / Completion Screen -->
        <div class="flex-1 flex items-center justify-center py-12">
            <div class="glass-card rounded-[3rem] p-12 lg:p-16 max-w-2xl w-full text-center relative overflow-hidden border border-white/10">
                <div class="relative z-10">
                    <div class="w-24 h-24 rounded-3xl bg-emerald-500/10 flex items-center justify-center mx-auto mb-8 animate-bounce">
                        <i data-lucide="award" class="w-12 h-12 text-emerald-400"></i>
                    </div>
                    <h2 class="text-4xl font-display font-black text-white mb-4">Mission Accomplished</h2>
                    <p class="text-gray-400 text-lg mb-10">Your strategic analysis is complete. Domain metrics have been archived.</p>
                    
                    <div class="bg-white/5 rounded-[2rem] p-10 mb-10 border border-white/5">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.3em] mb-2">Validated Score</p>
                        <p class="text-7xl font-display font-black text-white leading-none">{{ number_format($exam->total_score ?? 0, 1) }}</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('student.exams') }}"
                            class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-sm font-bold uppercase tracking-widest transition-all shadow-xl shadow-indigo-600/20 active:scale-95">
                            Return to Grid
                        </a>
                        <a href="{{ route('student.results') }}"
                            class="px-8 py-4 glass hover:bg-white/10 text-white rounded-2xl text-sm font-bold uppercase tracking-widest transition-all border-white/10">
                            Detailed Briefing
                        </a>
                    </div>
                </div>
                
                <!-- Background Accent -->
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>
            </div>
        </div>
    @elseif($currentQuestion)
        <!-- Top HUD (Fixed) -->
        <div class="sticky top-0 z-50 glass-card rounded-[2rem] p-6 mb-10 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-2xl">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-600/10 flex items-center justify-center text-indigo-400">
                    <i data-lucide="layers" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">{{ $exam->subject->name }}</h3>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Question {{ $currentQuestionIndex + 1 }} <span class="text-gray-700 mx-1">/</span> {{ $totalQuestions }}</p>
                </div>
            </div>

            <div class="flex-1 max-w-md hidden md:block px-8">
                <div class="w-full bg-white/5 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-full rounded-full transition-all duration-700 ease-out"
                        style="width: {{ (($currentQuestionIndex + 1) / $totalQuestions) * 100 }}%"></div>
                </div>
            </div>

            <div class="flex items-center gap-6" x-data="timer({{ $timeRemaining }})" x-init="startTimer()">
                <div class="text-right">
                    <p class="text-[8px] font-bold text-gray-500 uppercase tracking-[0.2em] mb-1">Time Remaining</p>
                    <p class="text-2xl font-display font-black leading-none tracking-tight" :class="seconds <= 10 ? 'text-red-500 animate-pulse' : 'text-indigo-400'" x-text="formatTime()"></p>
                </div>
                <div class="w-px h-8 bg-white/5"></div>
                <button wire:click="submitAnswer" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-indigo-600 text-white transition-all flex items-center justify-center">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <!-- Progress Mobile -->
        <div class="md:hidden w-full bg-white/5 rounded-full h-1 mb-6 overflow-hidden">
            <div class="bg-indigo-500 h-full rounded-full transition-all" style="width: {{ (($currentQuestionIndex + 1) / $totalQuestions) * 100 }}%"></div>
        </div>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col max-w-4xl mx-auto w-full">
            <div class="glass-card rounded-[3rem] p-8 lg:p-12 mb-8 border border-white/5 relative overflow-hidden group">
                <!-- Media Area -->
                @if($currentQuestion['type'] !== 'text' && $currentQuestion['media_url'])
                    <div class="mb-10 rounded-[2rem] overflow-hidden border border-white/5 bg-black/20 p-2 shadow-inner">
                        @if($currentQuestion['type'] === 'image')
                            <img src="{{ asset('storage/' . $currentQuestion['media_url']) }}" class="w-full max-h-[400px] object-contain rounded-2xl group-hover:scale-[1.02] transition-transform duration-700">
                        @elseif($currentQuestion['type'] === 'video')
                            <div class="aspect-video rounded-2xl overflow-hidden">
                                @if(str_contains($currentQuestion['media_url'], 'youtube') || str_contains($currentQuestion['media_url'], 'youtu.be'))
                                    <iframe src="{{ $currentQuestion['media_url'] }}" class="w-full h-full" allowfullscreen></iframe>
                                @else
                                    <video controls class="w-full h-full"><source src="{{ asset('storage/' . $currentQuestion['media_url']) }}"></video>
                                @endif
                            </div>
                        @elseif($currentQuestion['type'] === 'audio')
                            <div class="p-8 flex items-center justify-center">
                                <audio controls class="w-full max-w-sm custom-audio"><source src="{{ asset('storage/' . $currentQuestion['media_url']) }}"></audio>
                            </div>
                        @endif
                    </div>
                @endif

                @if($currentQuestion['content'])
                    <h2 class="text-2xl lg:text-3xl font-display font-bold text-black mb-10 leading-relaxed">
                        {{ $currentQuestion['content'] }}
                    </h2>
                @endif

                <!-- Options Sector -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($currentQuestion['options'] as $option)
                        <button wire:click="selectOption({{ $option['id'] }})"
                            class="group relative flex items-center gap-4 p-6 rounded-[2rem] border transition-all duration-300 text-left {{ $selectedOption == $option['id'] ? 'bg-indigo-600 border-indigo-500 shadow-xl shadow-indigo-600/30' : 'bg-white/5 border-white/5 hover:border-white/20 hover:bg-white/[0.08]' }}">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-black transition-colors {{ $selectedOption == $option['id'] ? 'bg-white text-indigo-600' : 'bg-white/10 text-gray-500 group-hover:bg-white/20 group-hover:text-white' }}">
                                {{ chr(64 + $option['option_number']) }}
                            </div>
                            <span class="text-base font-medium {{ $selectedOption == $option['id'] ? 'text-white' : 'text-gray-400 group-hover:text-white' }} transition-colors">
                                {{ $option['option_text'] }}
                            </span>
                            @if($selectedOption == $option['id'])
                                <div class="absolute right-6">
                                    <i data-lucide="check" class="w-5 h-5 text-white"></i>
                                </div>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Global Action Bar -->
            <div class="flex items-center justify-between pb-12">
                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Status: <span class="text-emerald-500">Connected to Grid</span></p>
                <button wire:click="submitAnswer"
                    class="group px-10 py-5 bg-white text-gray-900 hover:bg-indigo-500 hover:text-white rounded-[2rem] text-xs font-black uppercase tracking-[0.2em] transition-all shadow-2xl active:scale-95 disabled:opacity-50 flex items-center gap-3"
                    {{ !$selectedOption ? 'disabled' : '' }}>
                    {{ $currentQuestionIndex < $totalQuestions - 1 ? 'Next Node' : 'Finalize Analysis' }}
                    <i data-lucide="zap" class="w-4 h-4 group-hover:rotate-12 transition-transform"></i>
                </button>
            </div>
        </div>
    @else
        <div class="flex-1 flex items-center justify-center">
            <div class="text-center">
                <i data-lucide="cloud-off" class="w-12 h-12 text-gray-700 mx-auto mb-4"></i>
                <p class="text-gray-500 font-medium">No valid assessment data found.</p>
                <a href="{{ route('student.exams') }}" class="mt-4 inline-block text-indigo-400 text-xs font-bold uppercase tracking-widest hover:text-white">Relink Registry</a>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function timer(initialSeconds) {
        return {
            seconds: initialSeconds,
            interval: null,
            startTimer() {
                if (this.interval) clearInterval(this.interval);
                this.interval = setInterval(() => {
                    if (this.seconds > 0) {
                        this.seconds--;
                    } else {
                        clearInterval(this.interval);
                        @this.timeUp();
                    }
                }, 1000);
            },
            formatTime() {
                const m = Math.floor(this.seconds / 60);
                const s = this.seconds % 60;
                return (m > 0 ? m + ':' : '') + (s < 10 ? '0' : '') + s;
            }
        }
    }
</script>
@endpush