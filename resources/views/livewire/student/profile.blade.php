<div class="space-y-8 animate__animated animate__fadeIn max-w-4xl mx-auto">

    <!-- Page Header -->
    <div>
        <h2 class="text-3xl font-display font-bold text-gray-900 mb-1">My Profile</h2>
        <p class="text-sm text-gray-500">View and manage your personal information and account settings.</p>
    </div>

    <!-- Avatar + Identity Card -->
    <div class="glass-card rounded-[2.5rem] p-8 border border-gray-100 flex flex-col sm:flex-row items-center sm:items-start gap-8">
        <div class="shrink-0 flex flex-col items-center gap-3">
            @if($student?->image)
                <div class="w-28 h-28 rounded-[2rem] overflow-hidden border-4 border-indigo-100 shadow-lg shadow-indigo-100">
                    <img src="{{ Storage::url($student->image) }}" class="w-full h-full object-cover" alt="{{ $user->name }}">
                </div>
            @else
                <div class="w-28 h-28 rounded-[2rem] overflow-hidden border-4 border-indigo-100 shadow-lg shadow-indigo-100">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff&size=200"
                        class="w-full h-full object-cover" alt="{{ $user->name }}">
                </div>
            @endif
            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-100 text-indigo-700 border border-indigo-200">
                {{ ucfirst($user->role) }}
            </span>
            @if($student?->student_id)
                <span class="text-xs text-gray-400 font-mono">{{ $student->student_id }}</span>
            @endif
        </div>

        <div class="flex-1 min-w-0">
            <h3 class="text-2xl font-display font-bold text-gray-900 mb-1 truncate">{{ $user->name }}</h3>
            <p class="text-sm text-gray-400 mb-6">{{ $user->email }}</p>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @php
                    $meta = [
                        ['label' => 'Group',    'value' => $user->group ?? '—', 'icon' => 'users'],
                        ['label' => 'Class',    'value' => $user->class ?? '—', 'icon' => 'graduation-cap'],
                        ['label' => 'Phone',    'value' => $user->phone ?? '—', 'icon' => 'phone'],
                        ['label' => 'Division', 'value' => $student?->division?->name ?? '—', 'icon' => 'map-pin'],
                        ['label' => 'District', 'value' => $student?->district?->name ?? '—', 'icon' => 'map'],
                        ['label' => 'Season',   'value' => $student?->season?->name ?? '—', 'icon' => 'calendar'],
                    ];
                @endphp
                @foreach($meta as $item)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                            <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $item['label'] }}</p>
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $item['value'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ─── 1. PERSONAL INFORMATION ─────────────────────────────────── --}}
    <div class="glass-card rounded-[2.5rem] border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600">
                    <i data-lucide="user-pen" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-base font-bold text-gray-900">Personal Information</h4>
                    <p class="text-xs text-gray-400">Name, phone, gender, date of birth</p>
                </div>
            </div>
            @if(!$editingPersonal)
                <button wire:click="$set('editingPersonal', true)"
                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                    Edit
                </button>
            @endif
        </div>
        <div class="p-6">
            @if($personalMessage)
                <div class="mb-5 flex items-center gap-2 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                    <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i> {{ $personalMessage }}
                </div>
            @endif
            @if($editingPersonal)
                <form wire:submit="savePersonal" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Full Name *</label>
                            <input wire:model="name" type="text" placeholder="Your full name"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all">
                            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Phone</label>
                            <input wire:model="phone" type="text" placeholder="01XXXXXXXXX"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all">
                            @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Gender</label>
                            <select wire:model="gender" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm focus:outline-none focus:border-indigo-400 transition-all">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="others">Others</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Date of Birth</label>
                            <input wire:model="dob" type="date"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm focus:outline-none focus:border-indigo-400 transition-all">
                            @error('dob') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">NID / Birth Certificate</label>
                            <input wire:model="nid_birth" type="text" placeholder="NID or Birth Reg. No."
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-indigo-400 transition-all">
                        </div>
                    </div>
                    <div class="flex gap-3 pt-1">
                        <button type="submit"
                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Save
                        </button>
                        <button type="button" wire:click="$set('editingPersonal', false)"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                    @foreach([
                        ['Full Name',             $user->name],
                        ['Phone',                 $user->phone ?? '—'],
                        ['Gender',                ucfirst($student?->gender ?? '—')],
                        ['Date of Birth',         $student?->dob ?? '—'],
                        ['NID / Birth Cert.',     $student?->nid_birth ?? '—'],
                    ] as [$label, $value])
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $label }}</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ─── 2. INSTITUTE INFORMATION ────────────────────────────────── --}}
    <div class="glass-card rounded-[2.5rem] border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                    <i data-lucide="school" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-base font-bold text-gray-900">Institute Information</h4>
                    <p class="text-xs text-gray-400">School / College, class, group, board</p>
                </div>
            </div>
            @if(!$editingInstitute)
                <button wire:click="$set('editingInstitute', true)"
                    class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                    Edit
                </button>
            @endif
        </div>
        <div class="p-6">
            @if($instituteMessage)
                <div class="mb-5 flex items-center gap-2 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                    <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i> {{ $instituteMessage }}
                </div>
            @endif
            @if($editingInstitute)
                <form wire:submit="saveInstitute" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Institute Name</label>
                            <input wire:model="institute_name" type="text" placeholder="School / College / University"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Institute Email</label>
                            <input wire:model="institute_email" type="email" placeholder="institute@example.com"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-blue-400 transition-all">
                            @error('institute_email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Institute Mobile</label>
                            <input wire:model="institute_mobile" type="text" placeholder="01XXXXXXXXX"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-blue-400 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Class</label>
                            <select wire:model="class_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm focus:outline-none focus:border-blue-400 transition-all">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Group</label>
                            <select wire:model="group_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm focus:outline-none focus:border-blue-400 transition-all">
                                <option value="">Select Group</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Board</label>
                            <input wire:model="board" type="text" placeholder="e.g. Dhaka Board"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-blue-400 transition-all">
                        </div>
                    </div>
                    <div class="flex gap-3 pt-1">
                        <button type="submit"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Save
                        </button>
                        <button type="button" wire:click="$set('editingInstitute', false)"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                    @foreach([
                        ['Institute Name',   $student?->institute_name ?? '—'],
                        ['Institute Email',  $student?->institute_email ?? '—'],
                        ['Institute Mobile', $student?->institute_mobile ?? '—'],
                        ['Class',            $user->class ?? '—'],
                        ['Group',            $user->group ?? '—'],
                        ['Board',            $student?->board ?? '—'],
                    ] as [$label, $value])
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $label }}</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ─── 3. ADDRESS ──────────────────────────────────────────────── --}}
    <div class="glass-card rounded-[2.5rem] border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-green-100 flex items-center justify-center text-green-600">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-base font-bold text-gray-900">Address</h4>
                    <p class="text-xs text-gray-400">Division, district, thana and full address</p>
                </div>
            </div>
            @if(!$editingAddress)
                <button wire:click="$set('editingAddress', true)"
                    class="px-5 py-2 bg-green-600 hover:bg-green-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                    Edit
                </button>
            @endif
        </div>
        <div class="p-6">
            @if($addressMessage)
                <div class="mb-5 flex items-center gap-2 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                    <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i> {{ $addressMessage }}
                </div>
            @endif
            @if($editingAddress)
                <form wire:submit="saveAddress" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Division</label>
                            <select wire:model.live="division_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm focus:outline-none focus:border-green-400 transition-all">
                                <option value="">Select Division</option>
                                @foreach($divisions as $div)
                                    <option value="{{ $div->id }}">{{ $div->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">District</label>
                            <select wire:model.live="district_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm focus:outline-none focus:border-green-400 transition-all">
                                <option value="">Select District</option>
                                @foreach($districts as $dist)
                                    <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Thana / Upazilla</label>
                            <select wire:model="thana_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm focus:outline-none focus:border-green-400 transition-all">
                                <option value="">Select Thana</option>
                                @foreach($thanas as $thana)
                                    <option value="{{ $thana->id }}">{{ $thana->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Full Address</label>
                            <input wire:model="address" type="text" placeholder="Village / Road / Area"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-green-400 transition-all">
                        </div>
                    </div>
                    <div class="flex gap-3 pt-1">
                        <button type="submit"
                            class="px-6 py-3 bg-green-600 hover:bg-green-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Save
                        </button>
                        <button type="button" wire:click="$set('editingAddress', false)"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-5">
                    @foreach([
                        ['Division', $student?->division?->name ?? '—'],
                        ['District', $student?->district?->name ?? '—'],
                        ['Thana',    $student?->thana?->name ?? '—'],
                        ['Address',  $student?->address ?? '—'],
                    ] as [$label, $value])
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $label }}</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ─── 4. FAMILY INFORMATION ───────────────────────────────────── --}}
    <div class="glass-card rounded-[2.5rem] border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600">
                    <i data-lucide="heart" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-base font-bold text-gray-900">Family Information</h4>
                    <p class="text-xs text-gray-400">Father, mother, guardian contact</p>
                </div>
            </div>
            @if(!$editingFamily)
                <button wire:click="$set('editingFamily', true)"
                    class="px-5 py-2 bg-rose-500 hover:bg-rose-400 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                    Edit
                </button>
            @endif
        </div>
        <div class="p-6">
            @if($familyMessage)
                <div class="mb-5 flex items-center gap-2 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                    <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i> {{ $familyMessage }}
                </div>
            @endif
            @if($editingFamily)
                <form wire:submit="saveFamily" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Father's Name</label>
                            <input wire:model="father_name" type="text" placeholder="Father's full name"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-rose-400 focus:ring-2 focus:ring-rose-100 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Mother's Name</label>
                            <input wire:model="mother_name" type="text" placeholder="Mother's full name"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-rose-400 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Guardian Phone</label>
                            <input wire:model="guardian_phone" type="text" placeholder="01XXXXXXXXX"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-rose-400 transition-all">
                        </div>
                    </div>
                    <div class="flex gap-3 pt-1">
                        <button type="submit"
                            class="px-6 py-3 bg-rose-500 hover:bg-rose-400 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Save
                        </button>
                        <button type="button" wire:click="$set('editingFamily', false)"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                    @foreach([
                        ["Father's Name",    $student?->father_name ?? '—'],
                        ["Mother's Name",    $student?->mother_name ?? '—'],
                        ['Guardian Phone',   $student?->guardian_phone ?? '—'],
                    ] as [$label, $value])
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $label }}</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ─── 5. ADDITIONAL INFORMATION ──────────────────────────────── --}}
    <div class="glass-card rounded-[2.5rem] border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-base font-bold text-gray-900">Additional Information</h4>
                    <p class="text-xs text-gray-400">Hobby, aim, favourite quote, idol</p>
                </div>
            </div>
            @if(!$editingExtra)
                <button wire:click="$set('editingExtra', true)"
                    class="px-5 py-2 bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                    Edit
                </button>
            @endif
        </div>
        <div class="p-6">
            @if($extraMessage)
                <div class="mb-5 flex items-center gap-2 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                    <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i> {{ $extraMessage }}
                </div>
            @endif
            @if($editingExtra)
                <form wire:submit="saveExtra" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Known From</label>
                            <select wire:model="known_from" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm focus:outline-none focus:border-violet-400 transition-all">
                                <option value="">Select</option>
                                <option value="Facebook">Facebook</option>
                                <option value="Mentor">Mentor</option>
                                <option value="Ambassador">Ambassador</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Hobby</label>
                            <select wire:model="hobby" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm focus:outline-none focus:border-violet-400 transition-all">
                                <option value="">Select</option>
                                <option value="Reading">Reading</option>
                                <option value="Sports">Sports</option>
                                <option value="Music">Music</option>
                                <option value="Travel">Travel</option>
                                <option value="Art">Art</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Aim in Life</label>
                            <select wire:model="aim_in_life" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm focus:outline-none focus:border-violet-400 transition-all">
                                <option value="">Select</option>
                                <option value="Engineer">Engineer</option>
                                <option value="Doctor">Doctor</option>
                                <option value="Teacher">Teacher</option>
                                <option value="Entrepreneur">Entrepreneur</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Idol</label>
                            <input wire:model="idol" type="text" placeholder="Your idol / role model"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-violet-400 transition-all">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Favourite Quote</label>
                            <input wire:model="favourite_quote" type="text" placeholder="Your favourite quote"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-violet-400 transition-all">
                        </div>
                    </div>
                    <div class="flex gap-3 pt-1">
                        <button type="submit"
                            class="px-6 py-3 bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Save
                        </button>
                        <button type="button" wire:click="$set('editingExtra', false)"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                    @foreach([
                        ['Known From',       $student?->known_from ?? '—'],
                        ['Hobby',            $student?->hobby ?? '—'],
                        ['Aim in Life',      $student?->aim_in_life ?? '—'],
                        ['Idol',             $student?->idol ?? '—'],
                        ['Favourite Quote',  $student?->favourite_quote ?? '—'],
                    ] as [$label, $value])
                        <div class="{{ $label === 'Favourite Quote' ? 'sm:col-span-2' : '' }}">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $label }}</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ─── 6. PROFILE PHOTO ────────────────────────────────────────── --}}
    <div class="glass-card rounded-[2.5rem] border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600">
                    <i data-lucide="camera" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-base font-bold text-gray-900">Profile Photo</h4>
                    <p class="text-xs text-gray-400">Upload your profile picture (max 2MB)</p>
                </div>
            </div>
            @if(!$editingPhoto)
                <button wire:click="$set('editingPhoto', true)"
                    class="px-5 py-2 bg-orange-500 hover:bg-orange-400 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                    Change
                </button>
            @endif
        </div>
        <div class="p-6">
            @if($photoMessage)
                <div class="mb-5 flex items-center gap-2 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                    <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i> {{ $photoMessage }}
                </div>
            @endif
            @if($editingPhoto)
                <form wire:submit="savePhoto" class="space-y-4">
                    <div>
                        <input wire:model="photo" type="file" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-100 file:text-orange-700 hover:file:bg-orange-200 transition-all">
                        @error('photo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    @if($photo)
                        <div>
                            <p class="text-xs text-gray-400 mb-2">Preview:</p>
                            <img src="{{ $photo->temporaryUrl() }}" class="w-24 h-24 rounded-2xl object-cover border border-gray-200">
                        </div>
                    @endif
                    <div class="flex gap-3">
                        <button type="submit"
                            class="px-6 py-3 bg-orange-500 hover:bg-orange-400 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                            <i data-lucide="upload" class="w-4 h-4"></i> Upload
                        </button>
                        <button type="button" wire:click="$set('editingPhoto', false)"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            @else
                <div class="flex items-center gap-4">
                    @if($student?->image)
                        <img src="{{ Storage::url($student->image) }}" class="w-20 h-20 rounded-2xl object-cover border border-gray-200">
                        <p class="text-sm text-gray-500">Current profile photo</p>
                    @else
                        <p class="text-sm text-gray-400">No photo uploaded yet.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- ─── 7. CHANGE PASSWORD ──────────────────────────────────────── --}}
    <div class="glass-card rounded-[2.5rem] border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
                    <i data-lucide="lock-keyhole" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-base font-bold text-gray-900">Change Password</h4>
                    <p class="text-xs text-gray-400">Keep your account secure with a strong password</p>
                </div>
            </div>
            @if(!$changingPassword)
                <button wire:click="$set('changingPassword', true)"
                    class="px-5 py-2 bg-amber-500 hover:bg-amber-400 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                    Change
                </button>
            @endif
        </div>
        <div class="p-6">
            @if($passwordMessage)
                <div class="mb-5 flex items-center gap-2 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                    <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i> {{ $passwordMessage }}
                </div>
            @endif
            @if($changingPassword)
                <form wire:submit="savePassword" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Current Password</label>
                            <input wire:model="currentPassword" type="password" placeholder="Current password"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition-all">
                            @error('currentPassword') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">New Password</label>
                            <input wire:model="newPassword" type="password" placeholder="Min. 6 characters"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-amber-400 transition-all">
                            @error('newPassword') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Confirm New Password</label>
                            <input wire:model="newPasswordConfirmation" type="password" placeholder="Repeat new password"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-amber-400 transition-all">
                        </div>
                    </div>
                    <div class="flex gap-3 pt-1">
                        <button type="submit"
                            class="px-6 py-3 bg-amber-500 hover:bg-amber-400 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-4 h-4"></i> Update Password
                        </button>
                        <button type="button" wire:click="$set('changingPassword', false)"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            @else
                <p class="text-sm text-gray-400">Last updated: <span class="font-semibold text-gray-600">{{ $user->updated_at->format('M d, Y') }}</span></p>
            @endif
        </div>
    </div>

</div>
