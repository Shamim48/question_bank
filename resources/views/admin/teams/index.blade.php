<x-layouts.app>
@section('title', 'Team Members')

<div class="space-y-8">

    <div>
        <h2 class="text-3xl font-display font-bold text-gray-900 mb-1">Team Members</h2>
        <p class="text-sm text-gray-500">Approve registrations. Permissions are applied automatically based on each member's role.</p>
    </div>

    {{-- ── PENDING ──────────────────────────────────────────────────── --}}
    <div class="glass-card rounded-[2rem] border border-orange-100 overflow-hidden">
        <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-orange-50">
            <div class="w-9 h-9 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600">
                <i data-lucide="clock" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-base font-bold text-gray-900">Pending Approval</h4>
                <p class="text-xs text-gray-400">{{ $pending->count() }} member(s) waiting</p>
            </div>
        </div>

        @forelse($pending as $team)
            <div class="p-6 border-b border-gray-100 last:border-0">
                <div class="flex flex-col lg:flex-row gap-6 items-start">
                    {{-- Photo + Info --}}
                    <div class="flex items-start gap-4 flex-1">
                        <img src="{{ $team->image ? Storage::url($team->image) : 'https://ui-avatars.com/api/?name='.urlencode($team->user->name).'&background=f97316&color=fff' }}"
                            class="w-16 h-16 rounded-2xl object-cover border border-orange-100 shrink-0">
                        <div>
                            <p class="font-bold text-gray-900">{{ $team->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $team->user->email }} · {{ $team->user->phone }}</p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-700">{{ $team->role }}</span>
                                @if($team->season)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">{{ $team->season->name }}</span>
                                @endif
                                @if($team->institute_name)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">{{ $team->institute_name }}</span>
                                @endif
                                @if($team->division)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">{{ $team->division->name }}</span>
                                @endif
                            </div>
                            {{-- Role permissions preview --}}
                            @php
                                $roleModel = \App\Models\Role::with('permissions')->where('name', $team->role)->first();
                            @endphp
                            @if($roleModel && $roleModel->permissions->isNotEmpty())
                                <div class="mt-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Will get access to:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($roleModel->permissions as $perm)
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                                                {{ $perm->label }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <p class="mt-2 text-[10px] text-amber-500">⚠ No permissions set for "{{ $team->role }}" role. Configure in Roles page.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2 shrink-0">
                        <form method="POST" action="{{ route('admin.teams.approve', $team) }}">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-1">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i> Approve
                            </button>
                        </form>
                        <a href="{{ route('admin.teams.reject', $team) }}"
                            onclick="return confirm('Reject {{ $team->user->name }}?')"
                            class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-1">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i> Reject
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="p-6 text-sm text-gray-400">No pending registrations.</p>
        @endforelse
    </div>

    {{-- ── APPROVED ─────────────────────────────────────────────────── --}}
    <div class="glass-card rounded-[2rem] border border-emerald-100 overflow-hidden">
        <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-emerald-50">
            <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-base font-bold text-gray-900">Approved Members</h4>
                <p class="text-xs text-gray-400">{{ $approved->count() }} active member(s)</p>
            </div>
        </div>

        @forelse($approved as $team)
            <div class="p-6 border-b border-gray-100 last:border-0">
                <div class="flex items-start gap-4">
                    <img src="{{ $team->image ? Storage::url($team->image) : 'https://ui-avatars.com/api/?name='.urlencode($team->user->name).'&background=10b981&color=fff' }}"
                        class="w-14 h-14 rounded-2xl object-cover border border-emerald-100 shrink-0">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-bold text-gray-900">{{ $team->user->name }}</p>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">{{ $team->role }}</span>
                            @if($team->season)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">{{ $team->season->name }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $team->user->email }}</p>
                        {{-- Active permissions from role --}}
                        @php
                            $roleModel = \App\Models\Role::with('permissions')->where('name', $team->role)->first();
                        @endphp
                        @if($roleModel && $roleModel->permissions->isNotEmpty())
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach($roleModel->permissions as $perm)
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                                        {{ $perm->label }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-1 text-[10px] text-amber-500">No permissions configured for this role.</p>
                        @endif
                    </div>
                    <div class="shrink-0">
                        <a href="{{ route('admin.teams.reject', $team) }}"
                            onclick="return confirm('Revoke access for {{ $team->user->name }}?')"
                            class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-1">
                            <i data-lucide="ban" class="w-3.5 h-3.5"></i> Revoke
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="p-6 text-sm text-gray-400">No approved members yet.</p>
        @endforelse
    </div>

    {{-- ── REJECTED ─────────────────────────────────────────────────── --}}
    @if($rejected->count())
        <div class="glass-card rounded-[2rem] border border-red-100 overflow-hidden">
            <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-red-50">
                <div class="w-9 h-9 rounded-xl bg-red-100 flex items-center justify-center text-red-500">
                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-base font-bold text-gray-900">Rejected</h4>
                    <p class="text-xs text-gray-400">{{ $rejected->count() }} member(s)</p>
                </div>
            </div>
            @foreach($rejected as $team)
                <div class="p-6 border-b border-gray-100 last:border-0 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($team->user->name) }}&background=ef4444&color=fff"
                            class="w-10 h-10 rounded-xl object-cover shrink-0">
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">{{ $team->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $team->role }} · {{ $team->user->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.teams.approve', $team) }}">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                            Re-approve
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Info Banner --}}
    <div class="flex items-start gap-4 px-5 py-4 bg-blue-50 border border-blue-100 rounded-2xl text-sm text-blue-700">
        <i data-lucide="info" class="w-5 h-5 mt-0.5 shrink-0 text-blue-500"></i>
        <p>Permissions are managed per <strong>Role</strong>, not per individual user.
           To change what a role can access, go to
           <a href="{{ route('admin.roles') }}" class="underline font-bold hover:text-blue-900">Roles & Permissions</a>.</p>
    </div>

</div>
</x-layouts.app>
