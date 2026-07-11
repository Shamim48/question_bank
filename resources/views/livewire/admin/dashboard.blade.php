<div class="space-y-10 pb-12">
    <!-- Hero / Welcome Section -->
    <div class="relative overflow-hidden glass rounded-[2rem] p-8 lg:p-12 mb-10">
        <div class="relative z-10 max-w-2xl">
            <h2
                class="text-4xl lg:text-5xl font-display font-bold text-gray-900 mb-4 animate__animated animate__fadeInDown">
                Welcome back, <span class="gradient-text">{{ explode(' ', auth()->user()->name)[0] }}!</span>
            </h2>
            <p class="text-lg text-gray-600 mb-8 animate__animated animate__fadeInUp animate__delay-1s">
                Your learning management system is performing beautifully. Here's a snapshot of your platform's activity
                today.
            </p>
            <div class="flex flex-wrap gap-4 animate__animated animate__fadeInUp animate__delay-1s">
                <a href="{{ route('admin.questions') }}"
                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-semibold transition-all shadow-lg shadow-indigo-600/30 flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i> New Question
                </a>
                <a href="{{ route('admin.exams') }}"
                    class="px-6 py-3 bg-indigo-50 hover:bg-indigo-100 text-gray-900 rounded-xl font-semibold transition-all border border-indigo-100 flex items-center gap-2">
                    <i data-lucide="settings-2" class="w-5 h-5"></i> Manage Exams
                </a>
            </div>
        </div>
        <!-- Decorative Elements -->
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-indigo-600/20 rounded-full blur-[80px]"></div>
        <div class="absolute -right-10 top-10 w-40 h-40 bg-purple-600/20 rounded-full blur-[60px] animate-pulse"></div>
        <i data-lucide="sparkles" class="absolute right-20 bottom-10 w-32 h-32 text-indigo-500/10 rotate-12"></i>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $stats = [
                ['label' => 'Total Questions', 'value' => $totalQuestions, 'sub' => 'In bank', 'icon' => 'help-circle', 'color' => 'blue'],
                ['label' => 'Active Students', 'value' => $totalStudents, 'sub' => 'Registered', 'icon' => 'users', 'color' => 'purple'],
                ['label' => 'Total Rounds', 'value' => $totalRounds, 'sub' => $activeRounds . ' active now', 'icon' => 'layers', 'color' => 'emerald'],
                ['label' => 'Completed Exams', 'value' => $completedExams, 'sub' => 'Achievement unlocked', 'icon' => 'check-circle', 'color' => 'pink'],
            ];
        @endphp

        @foreach($stats as $stat)
            <div class="glass-card rounded-[1.5rem] p-6 group">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-{{ $stat['color'] }}-500/10 flex items-center justify-center group-hover:bg-{{ $stat['color'] }}-500/20 transition-colors">
                        <i data-lucide="{{ $stat['icon'] }}" class="w-6 h-6 text-{{ $stat['color'] }}-400"></i>
                    </div>
                    <div class="h-1 w-12 bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-{{ $stat['color'] }}-500/50 w-2/3"></div>
                    </div>
                </div>
                <h3 class="text-3xl font-display font-bold text-gray-900 mb-1">{{ number_format($stat['value']) }}</h3>
                <p class="text-xs text-gray-500 uppercase tracking-widest font-bold">{{ $stat['label'] }}</p>
                <p class="text-xs text-indigo-600 mt-2">{{ $stat['sub'] }}</p>
            </div>
        @endforeach
    </div>

    <!-- Charts & Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Question Distribution Chart -->
        <div class="lg:col-span-2 glass-card rounded-[2rem] p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-display font-bold text-gray-900">Question Ecosystem</h3>
                    <p class="text-sm text-gray-500">Distribution of content types</p>
                </div>
                <i data-lucide="pie-chart" class="w-6 h-6 text-indigo-600"></i>
            </div>
            <div class="h-[300px] relative">
                <canvas id="questionsChart"></canvas>
            </div>
        </div>

        <!-- System Health / Quick List -->
        <div class="glass-card rounded-[2rem] p-8">
            <h3 class="text-xl font-display font-bold text-gray-900 mb-6">Recent Activity</h3>
            <div class="space-y-6">
                @foreach($recentExams as $exam)
                    <div class="flex items-center gap-4 group">
                        <div
                            class="w-10 h-10 rounded-full glass flex items-center justify-center shrink-0 border-white/5 group-hover:border-indigo-500/30 transition-colors">
                            <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $exam->user->name ?? 'Student' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $exam->subject->name ?? 'N/A' }} •
                                {{ $exam->round->name ?? 'N/A' }}</p>
                        </div>
                        <span
                            class="text-[10px] font-bold px-2 py-1 rounded-lg uppercase tracking-wider {{ $exam->status === 'completed' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-indigo-500/10 text-indigo-400' }}">
                            {{ $exam->status }}
                        </span>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('admin.exams') }}"
                class="mt-8 block w-full py-3 glass rounded-xl text-center text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-black/5 transition-all">
                View All Activity
            </a>
        </div>
    </div>

    <!-- Exam Management Menu -->
    <div>
        <h3 class="text-xl font-display font-bold text-gray-900 mb-6">Exam Management Menu</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $tools = [
                    ['label' => 'Student List', 'icon' => 'user-check', 'route' => 'admin.participants'],
                    ['label' => 'Exam Rounds', 'icon' => 'layers', 'route' => 'admin.rounds'],
                    ['label' => 'Question Bank', 'icon' => 'database', 'route' => 'admin.questions'],
                    ['label' => 'Exam Control', 'icon' => 'activity', 'route' => 'admin.exams'],
                    ['label' => 'Manual Marks', 'icon' => 'award', 'route' => 'admin.marks'],
                    ['label' => 'Offline Marks', 'icon' => 'clipboard-check', 'route' => 'admin.offline-marks'],
                    ['label' => 'Certificates', 'icon' => 'scroll', 'route' => 'admin.certificates'],
                    ['label' => 'PDF Books', 'icon' => 'book', 'route' => 'admin.pdf-books'],
                ];
            @endphp
            @foreach($tools as $tool)
                <a href="{{ route($tool['route']) }}" class="glass-card rounded-2xl p-4 text-center group">
                    <div
                        class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center mx-auto mb-3 group-hover:bg-indigo-600 group-hover:text-white transition-all text-indigo-600">
                        <i data-lucide="{{ $tool['icon'] }}" class="w-5 h-5"></i>
                    </div>
                    <span
                        class="text-xs font-medium text-gray-600 group-hover:text-gray-900 transition-colors">{{ $tool['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:navigated', initCharts);
        document.addEventListener('DOMContentLoaded', initCharts);

        function initCharts() {
            const ctx = document.getElementById('questionsChart');
            if (!ctx) return;

            // Destroy existing chart if it exists to prevent memory leaks
            if (window.myChart) window.myChart.destroy();

            window.myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Text', 'Image', 'Audio', 'Video'],
                    datasets: [{
                        label: 'Question Types',
                        data: [
                            {{ $questionsByType['text'] ?? 0 }},
                            {{ $questionsByType['image'] ?? 0 }},
                            {{ $questionsByType['audio'] ?? 0 }},
                            {{ $questionsByType['video'] ?? 0 }}
                        ],
                        backgroundColor: [
                            'rgba(99, 102, 241, 0.5)',
                            'rgba(168, 85, 247, 0.5)',
                            'rgba(16, 185, 129, 0.5)',
                            'rgba(236, 72, 153, 0.5)'
                        ],
                        borderColor: [
                            '#6366f1', '#a855f7', '#10b981', '#ec4899'
                        ],
                        borderWidth: 2,
                        borderRadius: 12,
                        barThickness: 40,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { family: 'Outfit', size: 14 },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 12,
                            cornerRadius: 12,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false },
                            ticks: { color: '#64748b', font: { family: 'Inter' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#94a3b8', font: { family: 'Outfit', weight: 'bold' } }
                        }
                    }
                }
            });

            // Re-init icons
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    </script>
@endpush