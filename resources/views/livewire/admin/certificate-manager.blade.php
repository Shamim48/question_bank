<div class="space-y-8 animate__animated animate__fadeIn">
    @php
        $u = auth()->user();
        $canCreate = $u->isAdmin() || $u->hasPermission('certificates-create');
        $canDelete = $u->isAdmin() || $u->hasPermission('certificates-delete');
    @endphp
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-display font-bold text-white mb-2">Credential Registry</h2>
            <p class="text-sm text-gray-400">Validate and issue official certificates of achievement</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 glass rounded-xl border-white/5 flex items-center gap-2">
                <i data-lucide="award" class="w-4 h-4 text-amber-500"></i>
                <span class="text-xs font-bold text-white">{{ $certificates->count() }} Issued</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Generation Module -->
        @if($canCreate)
        <div class="lg:col-span-4 lg:sticky lg:top-8 self-start">
            <div class="glass-card rounded-[2.5rem] p-8 lg:p-10 border border-white/5 relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-8">
                        <div
                            class="w-10 h-10 rounded-xl bg-indigo-600/10 flex items-center justify-center text-indigo-400 text-6xl">
                            <i data-lucide="scroll" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-xl font-display font-bold text-white">Issue Credential</h3>
                    </div>

                    <form wire:submit.prevent="generate" class="space-y-6">
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Recipient</label>
                            <select wire:model="user_id"
                                class="w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 text-white text-sm focus:ring-2 focus:ring-indigo-500/50">
                                <option value="">Select Candidate</option>
                                @foreach($students as $student)<option value="{{ $student->id }}">{{ $student->name }}
                                </option>@endforeach
                            </select>
                            @error('user_id') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Achievement
                                Phase</label>
                            <select wire:model="round_id"
                                class="w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 text-white text-sm focus:ring-2 focus:ring-indigo-500/50">
                                <option value="">Select Round</option>
                                @foreach($rounds as $round)<option value="{{ $round->id }}">{{ $round->name }}</option>
                                @endforeach
                            </select>
                            @error('round_id') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Group Domain</label>
                            <select wire:model="group_id"
                                class="w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 text-white text-sm focus:ring-2 focus:ring-indigo-500/50">
                                <option value="">Select Group</option>
                                @foreach($groups as $group)<option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                            @error('group_id') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" wire:loading.attr="disabled"
                            class="w-full py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-2xl text-xs font-bold uppercase tracking-widest transition-all shadow-xl shadow-indigo-600/20 disabled:opacity-70 flex items-center justify-center gap-3">
                            <i data-lucide="check-circle" class="w-4 h-4" wire:loading.remove wire:target="generate"></i>
                            <div wire:loading wire:target="generate" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            <span>Finalize & Issue</span>
                        </button>
                    </form>
                </div>

                <!-- Background Accent -->
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-indigo-600/5 rounded-full blur-3xl"></div>
            </div>
        </div>
        @endif

        <!-- Registry List -->
        <div class="{{ $canCreate ? 'lg:col-span-8' : 'lg:col-span-12' }}">
            <div class="glass-card rounded-[2.5rem] p-8 lg:p-10 border border-white/5">
                <div class="flex items-center justify-between mb-10">
                    <h3 class="text-xl font-display font-bold text-white">Historical Records</h3>
                    <div
                        class="w-10 h-10 glass rounded-xl flex items-center justify-center text-gray-500 hover:text-white transition-colors">
                        <i data-lucide="filter" class="w-5 h-5"></i>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($certificates as $cert)
                        <div
                            class="group flex flex-col md:flex-row md:items-center justify-between gap-6 p-6 rounded-3xl bg-white/[0.02] border border-white/5 hover:border-indigo-500/30 transition-all">
                            <div class="flex items-center gap-6">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500 relative group-hover:scale-110 transition-transform">
                                    <i data-lucide="medal" class="w-7 h-7"></i>
                                    <div
                                        class="absolute inset-0 bg-amber-500/10 blur-xl opacity-0 group-hover:opacity-100 transition-opacity">
                                    </div>
                                </div>
                                <div>
                                    <h4
                                        class="text-base font-bold text-white group-hover:text-indigo-400 transition-colors">
                                        {{ $cert->user->name ?? 'Candidate Unknown' }}</h4>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">ID:
                                        {{ $cert->certificate_number }} · {{ $cert->round->name }}
                                        @if($cert->group) · {{ $cert->group->name }} @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 px-6 md:px-0">
                                <div class="text-right hidden sm:block">
                                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-1">
                                        Authenticated</p>
                                    <p class="text-xs text-gray-400">{{ $cert->issued_at->format('M d, Y') }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('certificate.download', $cert) }}"
                                        class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600/10 hover:bg-indigo-600 text-indigo-400 hover:text-white rounded-xl text-[10px] font-bold uppercase transition-all">
                                        <i data-lucide="download" class="w-3 h-3"></i> Download
                                    </a>
                                    @if($canDelete)
                                    <button wire:click="delete({{ $cert->id }})"
                                        wire:confirm="Revoke this certificate permanently?"
                                        wire:loading.attr="disabled" wire:target="delete({{ $cert->id }})"
                                        class="w-10 h-10 glass flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition-all disabled:opacity-50">
                                        <i data-lucide="trash-2" class="w-4 h-4" wire:loading.remove wire:target="delete({{ $cert->id }})"></i>
                                        <div wire:loading wire:target="delete({{ $cert->id }})" class="w-4 h-4 border-2 border-red-500/30 border-t-red-500 rounded-full animate-spin"></div>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-24 text-center">
                            <i data-lucide="search-x" class="w-12 h-12 text-gray-700 mx-auto mb-4"></i>
                            <h4 class="text-gray-500 font-medium">No valid credentials found in current registry.</h4>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
