<div class="space-y-8 animate__animated animate__fadeIn">
    @php
        $u = auth()->user();
        $canEdit   = $u->isAdmin() || $u->hasPermission('participants-edit');
        $canDelete = $u->isAdmin() || $u->hasPermission('participants-delete');
    @endphp
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
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="view({{ $student->id }})"
                                    class="w-9 h-9 glass rounded-lg flex items-center justify-center text-indigo-400 hover:bg-indigo-600 hover:text-white transition-all">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                @if($canEdit)
                                    <button wire:click="edit({{ $student->id }})"
                                        class="w-9 h-9 glass rounded-lg flex items-center justify-center text-indigo-400 hover:bg-indigo-600 hover:text-white transition-all">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>
                                @endif
                                @if($canDelete)
                                    <button wire:click="delete({{ $student->id }})" wire:confirm="Delete {{ $student->name }}? This cannot be undone."
                                        wire:loading.attr="disabled" wire:target="delete({{ $student->id }})"
                                        class="w-9 h-9 glass rounded-lg flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white transition-all disabled:opacity-50">
                                        <i data-lucide="trash-2" class="w-4 h-4" wire:loading.remove wire:target="delete({{ $student->id }})"></i>
                                        <div wire:loading wire:target="delete({{ $student->id }})" class="w-4 h-4 border-2 border-red-500/30 border-t-red-500 rounded-full animate-spin"></div>
                                    </button>
                                @endif
                            </div>
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
                            'Date of Birth'    => $viewingStudent->dob ? \Illuminate\Support\Carbon::parse($viewingStudent->dob)->format('d M Y') : null,
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

    <!-- Edit Modal -->
    @if($editingId)
        @php
            $inputClass = 'w-full bg-white border-gray-200 rounded-2xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500/50 text-gray-900';
            $labelClass = 'text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1';
        @endphp
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4" x-data x-init="setTimeout(() => window.lucide && lucide.createIcons(), 50)">
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md" wire:click="closeEdit"></div>
            <div class="glass relative z-10 w-full max-w-3xl rounded-[2.5rem] p-8 lg:p-10 shadow-2xl border-white/10 animate__animated animate__zoomIn animate__faster max-h-[85vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-display font-bold text-gray-900">Edit Participant</h3>
                    <button wire:click="closeEdit"
                        class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-all shrink-0">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form wire:submit.prevent="update" class="space-y-8" enctype="multipart/form-data">
                    <!-- Identity -->
                    <div class="space-y-4">
                        <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Identity</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Name</label>
                                <input type="text" wire:model="editName" class="{{ $inputClass }}">
                                @error('editName') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Student ID</label>
                                <input type="text" wire:model="editStudentId" class="{{ $inputClass }}">
                                @error('editStudentId') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Email</label>
                                <input type="email" wire:model="editEmail" class="{{ $inputClass }}">
                                @error('editEmail') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Phone</label>
                                <input type="text" wire:model="editPhone" class="{{ $inputClass }}">
                                @error('editPhone') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Gender</label>
                                <select wire:model="editGender" class="{{ $inputClass }}">
                                    <option value="">—</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Date of Birth</label>
                                <input type="date" wire:model="editDob" class="{{ $inputClass }}">
                                @error('editDob') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">NID / Birth Certificate No.</label>
                                <input type="text" wire:model="editNidBirth" class="{{ $inputClass }}">
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Photo</label>
                                <input type="file" wire:model="editImage" accept="image/*" class="{{ $inputClass }}">
                                <div wire:loading wire:target="editImage" class="text-[10px] text-indigo-500">Uploading...</div>
                                @error('editImage') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                                @if($editImage) <img src="{{ $editImage->temporaryUrl() }}" class="w-14 h-14 rounded-xl object-cover mt-2"> @endif
                            </div>
                        </div>
                    </div>

                    <!-- Academic -->
                    <div class="space-y-4">
                        <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Academic</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Class</label>
                                <select wire:model="editClassId" class="{{ $inputClass }}">
                                    <option value="">—</option>
                                    @foreach($classLevels as $classLevel)
                                        <option value="{{ $classLevel->id }}">{{ $classLevel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Group</label>
                                <select wire:model="editGroupId" class="{{ $inputClass }}">
                                    <option value="">—</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Season</label>
                                <select wire:model="editSeasonId" class="{{ $inputClass }}">
                                    <option value="">—</option>
                                    @foreach($seasons as $season)
                                        <option value="{{ $season->id }}">{{ $season->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2 sm:col-span-3">
                                <label class="{{ $labelClass }}">Known From</label>
                                <select wire:model="editKnownFrom" class="{{ $inputClass }}">
                                    <option value="">—</option>
                                    <option value="Facebook">Facebook</option>
                                    <option value="Mentor">Mentor</option>
                                    <option value="Ambassador">Ambassador</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Institute -->
                    <div class="space-y-4">
                        <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Institute</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Institute Name</label>
                                <input type="text" wire:model="editInstituteName" class="{{ $inputClass }}">
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Board</label>
                                <input type="text" wire:model="editBoard" class="{{ $inputClass }}">
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Institute Phone</label>
                                <input type="text" wire:model="editInstituteMobile" class="{{ $inputClass }}">
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Institute Email</label>
                                <input type="email" wire:model="editInstituteEmail" class="{{ $inputClass }}">
                                @error('editInstituteEmail') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Guardian -->
                    <div class="space-y-4">
                        <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Guardian</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Father's Name</label>
                                <input type="text" wire:model="editFatherName" class="{{ $inputClass }}">
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Mother's Name</label>
                                <input type="text" wire:model="editMotherName" class="{{ $inputClass }}">
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Guardian Phone</label>
                                <input type="text" wire:model="editGuardianPhone" class="{{ $inputClass }}">
                            </div>
                        </div>
                    </div>

                    <!-- Personal -->
                    <div class="space-y-4">
                        <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Personal</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Hobby</label>
                                <select wire:model="editHobby" class="{{ $inputClass }}">
                                    <option value="">—</option>
                                    @foreach(['Reading', 'Sports', 'Music', 'Travel', 'Art'] as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Aim in Life</label>
                                <select wire:model="editAimInLife" class="{{ $inputClass }}">
                                    <option value="">—</option>
                                    @foreach(['Engineer', 'Doctor', 'Teacher', 'Entrepreneur', 'Other'] as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Idol</label>
                                <input type="text" wire:model="editIdol" class="{{ $inputClass }}">
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Favourite Quote</label>
                                <input type="text" wire:model="editFavouriteQuote" class="{{ $inputClass }}">
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="space-y-4">
                        <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Address</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Division</label>
                                <select wire:model.live="editDivisionId" class="{{ $inputClass }}">
                                    <option value="">—</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}">{{ $division->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">District</label>
                                <select wire:model.live="editDistrictId" class="{{ $inputClass }}">
                                    <option value="">—</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="{{ $labelClass }}">Thana</label>
                                <select wire:model="editThanaId" class="{{ $inputClass }}">
                                    <option value="">—</option>
                                    @foreach($thanas as $thana)
                                        <option value="{{ $thana->id }}">{{ $thana->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2 sm:col-span-3">
                                <label class="{{ $labelClass }}">Address</label>
                                <input type="text" wire:model="editAddress" class="{{ $inputClass }}">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="editStatus" class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500/50">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Active</span>
                        </label>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" wire:click="closeEdit"
                            class="flex-1 py-4 bg-gray-100/50 rounded-2xl text-xs font-bold text-gray-500 hover:text-gray-900 transition-all border border-gray-200">Cancel</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="update"
                            class="flex-[2] py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-xs font-bold transition-all shadow-xl shadow-indigo-600/30 flex items-center justify-center gap-2 disabled:opacity-70">
                            <i data-lucide="check" class="w-4 h-4" wire:loading.remove wire:target="update"></i>
                            <div wire:loading wire:target="update" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
