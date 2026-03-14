<div class="space-y-12 animate__animated animate__fadeIn">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-display font-bold text-white mb-2">Participant Registry</h2>
            <p class="text-sm text-gray-400">Discover and coordinate with ambassadors across the strategic sectors.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 glass rounded-xl border-white/5 flex items-center gap-2">
                <i data-lucide="users" class="w-4 h-4 text-indigo-400"></i>
                <span class="text-xs font-bold text-white">Public Access</span>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="glass-card rounded-[2.5rem] p-3 border border-white/5">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-2">
            <div class="relative group">
                <i data-lucide="search"
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 group-focus-within:text-indigo-400 transition-colors"></i>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search Identity..."
                    class="w-full bg-white/5 border-none rounded-2xl pl-12 pr-4 py-3.5 text-white text-sm focus:ring-2 focus:ring-indigo-500/50">
            </div>

            @foreach(['filterTeam' => 'layers', 'filterEvent' => 'calendar', 'filterDivision' => 'map-pin', 'filterDistrict' => 'navigation'] as $model => $icon)
                <div class="relative group">
                    <i data-lucide="{{ $icon }}"
                        class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 group-focus-within:text-indigo-400 transition-colors"></i>
                    <select wire:model.live="{{ $model }}"
                        class="w-full bg-white/5 border-none rounded-2xl pl-12 pr-10 py-3.5 text-white text-sm focus:ring-2 focus:ring-indigo-500/50 appearance-none">
                        <option value="">All {{ str_replace('filter', '', $model) }}s</option>
                        @foreach(${$model === 'filterTeam' ? 'teams' : ($model === 'filterEvent' ? 'events' : ($model === 'filterDivision' ? 'divisions' : 'districts'))} as $opt)
                            <option value="{{ $opt }}">{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Results Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($ambassadors as $amb)
            <div class="glass-card rounded-[2.5rem] p-8 border border-white/5 group relative overflow-hidden hover:bg-white/[0.02] transition-all animate__animated animate__fadeInUp"
                style="animation-delay: {{ $loop->index * 0.05 }}s">
                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-center gap-6 mb-8">
                        <div
                            class="w-16 h-16 rounded-[1.25rem] bg-gradient-to-br from-indigo-500/20 to-purple-600/20 flex items-center justify-center text-xl font-display font-black text-white group-hover:scale-110 transition-transform border border-white/10">
                            {{ strtoupper(substr($amb->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white group-hover:text-indigo-400 transition-colors">
                                {{ $amb->name }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Active
                                    Liaison</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 flex-1">
                        @if($amb->team)
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-500 group-hover:text-indigo-400 transition-colors">
                                    <i data-lucide="layers" class="w-4 h-4"></i>
                                </div>
                                <span class="text-xs text-gray-400 font-medium">Team: <span
                                        class="text-white">{{ $amb->team }}</span></span>
                            </div>
                        @endif

                        @if($amb->event)
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-500 group-hover:text-purple-400 transition-colors">
                                    <i data-lucide="award" class="w-4 h-4"></i>
                                </div>
                                <span
                                    class="text-xs text-gray-400 font-medium whitespace-nowrap overflow-hidden text-ellipsis">Event:
                                    <span class="text-white">{{ $amb->event }}</span></span>
                            </div>
                        @endif

                        @if($amb->division)
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-500 group-hover:text-emerald-400 transition-colors">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                </div>
                                <span class="text-xs text-gray-400 font-medium">{{ $amb->division }},
                                    {{ $amb->district }}</span>
                            </div>
                        @endif
                    </div>

                    @if($amb->phone)
                        <div class="mt-8 pt-6 border-t border-white/5 flex items-center justify-between">
                            <span class="text-[8px] font-bold text-gray-700 uppercase tracking-widest">Secure Contact</span>
                            <span
                                class="text-xs font-mono text-gray-500 group-hover:text-white transition-colors">{{ $amb->phone }}</span>
                        </div>
                    @endif
                </div>

                <!-- Background Accent -->
                <div
                    class="absolute -right-10 -bottom-10 w-32 h-32 bg-indigo-600/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity">
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 text-center">
                <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="user-x-2" class="w-10 h-10 text-gray-800"></i>
                </div>
                <h3 class="text-xl font-display font-bold text-gray-500 mb-2">No Matches in Local Registry</h3>
                <p class="text-gray-600">The filters provided returned zero active liaisons from the database.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-12 flex justify-center">
        {{ $ambassadors->links() }}
    </div>
</div>