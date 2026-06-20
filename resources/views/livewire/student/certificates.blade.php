<div class="space-y-12 animate__animated animate__fadeIn">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900 mb-2">Achievement Records</h2>
            <p class="text-sm text-gray-500">Official credentials and certifications earned through domain excellence.</p>
        </div>
        <div class="px-5 py-3 glass-card rounded-2xl border border-gray-100 flex items-center gap-3">
            <i data-lucide="medal" class="w-5 h-5 text-amber-500"></i>
            <span class="text-xs font-bold text-gray-700">{{ $certificates->count() }} Credentials Earned</span>
        </div>
    </div>

    @if($certificates->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($certificates as $cert)
                <div class="glass-card rounded-[2.5rem] p-8 border border-gray-100 group relative overflow-hidden flex flex-col justify-between hover:border-amber-200 hover:shadow-md transition-all">
                    <div class="relative z-10">
                        <div class="flex items-start justify-between mb-8">
                            <div class="w-16 h-16 rounded-2xl bg-amber-100 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform">
                                <i data-lucide="scroll" class="w-8 h-8"></i>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 bg-indigo-100 border border-indigo-200 text-indigo-700 text-[8px] font-bold uppercase tracking-widest rounded-lg">Verified</span>
                            </div>
                        </div>

                        <h3 class="text-xl font-display font-bold text-gray-900 mb-2 leading-tight group-hover:text-indigo-600 transition-colors">
                            {{ $cert->round->name ?? 'Credential' }}
                            @if($cert->group)
                                <span class="text-sm font-normal text-gray-500 block mt-1">{{ $cert->group->name }}</span>
                            @endif
                        </h3>
                        <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em] mb-4">Registry ID: {{ $cert->certificate_number }}</p>

                        <div class="flex items-center gap-2 mb-8">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                            <span class="text-xs text-gray-400 font-medium italic">Authenticated on {{ $cert->issued_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <div class="relative z-10 pt-6 border-t border-gray-100" x-data="{ downloading: false }" @clear-manual-loaders.window="downloading = false">
                        <a href="{{ route('certificate.download', $cert) }}"
                            @click="downloading = true; setTimeout(() => downloading = false, 6000)"
                            class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-[10px] font-bold uppercase tracking-[0.2em] transition-all shadow-lg shadow-indigo-600/20 flex items-center justify-center gap-3 active:scale-95">
                            <i data-lucide="download" class="w-4 h-4" x-show="!downloading"></i>
                            <div x-show="downloading" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            <span x-text="downloading ? 'Processing...' : 'Secure PDF Export'"></span>
                        </a>
                    </div>

                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
            @endforeach
        </div>
    @else
        <div class="glass-card rounded-[3rem] p-24 text-center border border-dashed border-gray-200">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-8">
                <i data-lucide="award" class="w-10 h-10 text-gray-300"></i>
            </div>
            <h3 class="text-xl font-display font-bold text-gray-600 mb-2">No Credentials Issued</h3>
            <p class="text-gray-400 max-w-sm mx-auto">Maintain your assessment trajectory to unlock official certifications once requirements are validated.</p>
            <a href="{{ route('student.exams') }}"
                class="mt-8 inline-block px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-indigo-600/20">
                Initialize Assessment
            </a>
        </div>
    @endif
</div>
