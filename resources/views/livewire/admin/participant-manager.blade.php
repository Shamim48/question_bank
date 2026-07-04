<div class="space-y-8 animate__animated animate__fadeIn">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900 mb-2">Participant Management</h2>
            <p class="text-sm text-gray-400">Browse registered students</p>
        </div>
        <div class="relative group w-full md:w-80">
            <i data-lucide="search"
                class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search name, ID, phone, email..."
                class="w-full bg-white border border-gray-200 rounded-2xl pl-12 pr-4 py-3.5 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-500/50">
        </div>
    </div>

    <!-- Content Table -->
    <div class="glass rounded-[2rem] overflow-hidden border border-white/5">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5">
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Student</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Contact</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Class / Group</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Institute</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Season</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Status</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($students as $student)
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        <td class="py-5 px-8">
                            <div class="flex items-center gap-4">
                                <img src="{{ $student->image ? asset('storage/' . $student->image) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=6366f1&color=fff' }}"
                                    class="w-10 h-10 rounded-xl object-cover border border-gray-200 shrink-0">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $student->name }}</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">{{ $student->student_id ?: '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-8">
                            <p class="text-xs text-gray-700">{{ $student->phone ?: '—' }}</p>
                            <p class="text-[10px] text-gray-500">{{ $student->email ?: '—' }}</p>
                        </td>
                        <td class="py-5 px-8">
                            <p class="text-xs text-gray-700">{{ $student->classLevel->name ?? '—' }}</p>
                            <p class="text-[10px] text-gray-500">{{ $student->group->name ?? '—' }}</p>
                        </td>
                        <td class="py-5 px-8">
                            <p class="text-xs text-gray-700 max-w-[200px] truncate">{{ $student->institute_name ?: '—' }}</p>
                        </td>
                        <td class="py-5 px-8">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-500/10 border border-purple-500/20 text-purple-400 text-[10px] font-bold uppercase tracking-wider rounded-lg">
                                {{ $student->season->name ?? '—' }}
                            </span>
                        </td>
                        <td class="py-5 px-8">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg {{ $student->status ? 'bg-emerald-500/10 border border-emerald-500/20 text-emerald-400' : 'bg-gray-500/10 border border-gray-500/20 text-gray-400' }}">
                                {{ $student->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="py-5 px-8 text-right">
                            <button wire:click="view({{ $student->id }})"
                                class="w-9 h-9 glass rounded-lg flex items-center justify-center text-indigo-400 hover:bg-indigo-600 hover:text-white transition-all">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-20 text-center">
                            <i data-lucide="users" class="w-12 h-12 text-gray-700 mx-auto mb-4"></i>
                            <p class="text-gray-500 font-medium">No participants registered yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($students->hasPages())
        <div class="flex justify-center">
            {{ $students->links() }}
        </div>
    @endif

    <!-- View Modal -->
    @if($viewingStudent)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4" x-data x-init="setTimeout(() => window.lucide && lucide.createIcons(), 50)">
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md" wire:click="closeView"></div>
            <div class="glass relative z-10 w-full max-w-2xl rounded-[2.5rem] p-8 lg:p-10 shadow-2xl border-white/10 animate__animated animate__zoomIn animate__faster max-h-[85vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <img src="{{ $viewingStudent->image ? asset('storage/' . $viewingStudent->image) : 'https://ui-avatars.com/api/?name=' . urlencode($viewingStudent->name) . '&background=6366f1&color=fff' }}"
                            class="w-16 h-16 rounded-2xl object-cover border border-gray-200 shrink-0">
                        <div>
                            <h3 class="text-2xl font-display font-bold text-gray-900">{{ $viewingStudent->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $viewingStudent->student_id ?: '—' }}</p>
                        </div>
                    </div>
                    <button wire:click="closeView"
                        class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-all shrink-0">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                    @php
                        $fields = [
                            'Email'            => $viewingStudent->email,
                            'Phone'            => $viewingStudent->phone,
                            'Gender'           => $viewingStudent->gender ? ucfirst($viewingStudent->gender) : null,
                            'Date of Birth'    => optional($viewingStudent->dob)->format('d M Y'),
                            'Class'            => $viewingStudent->classLevel->name ?? null,
                            'Group'            => $viewingStudent->group->name ?? null,
                            'Season'           => $viewingStudent->season->name ?? null,
                            'Institute'        => $viewingStudent->institute_name,
                            'Institute Phone'  => $viewingStudent->institute_mobile,
                            'Institute Email'  => $viewingStudent->institute_email,
                            'Board'            => $viewingStudent->board,
                            'Division'         => $viewingStudent->division->name ?? null,
                            'District'         => $viewingStudent->district->name ?? null,
                            'Thana'            => $viewingStudent->thana->name ?? null,
                            'Address'          => $viewingStudent->address,
                            'Father\'s Name'   => $viewingStudent->father_name,
                            'Mother\'s Name'   => $viewingStudent->mother_name,
                            'Guardian Phone'   => $viewingStudent->guardian_phone,
                            'Known From'       => $viewingStudent->known_from,
                        ];
                    @endphp
                    @foreach($fields as $label => $value)
                        @if($value)
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $label }}</p>
                                <p class="text-sm text-gray-900 mt-0.5">{{ $value }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="flex justify-end pt-8">
                    <button wire:click="closeView"
                        class="px-6 py-3 bg-gray-100/50 rounded-2xl text-xs font-bold text-gray-500 hover:text-gray-900 transition-all border border-gray-200">Close</button>
                </div>
            </div>
        </div>
    @endif
</div>
