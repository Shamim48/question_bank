<x-layouts.app>
    @section('title', 'Edit User')

    @php
        $inputClass = 'w-full bg-indigo-950/50 border border-indigo-500/30 rounded-xl px-4 py-3 placeholder-indigo-400/40 text-sm focus:outline-none';
        $labelClass = 'block text-sm font-medium mb-2';
    @endphp

    <div class="fade-in max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center justify-center w-9 h-9 rounded-xl glass hover:bg-indigo-500/20 text-indigo-300 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold gradient-text">Edit User</h1>
                <p class="text-indigo-300/60 text-sm mt-1">Update account details. Leave password blank to keep it unchanged.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="glass rounded-2xl p-8">
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30">
                    <ul class="text-red-400 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Account -->
                <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-4">Account</p>
                <div class="space-y-6 mb-8">
                    <div>
                        <label class="{{ $labelClass }}" style="color:#000">
                            Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            placeholder="Full name" class="{{ $inputClass }}" style="color:#000" required>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="{{ $labelClass }}" style="color:#000">
                                Email <span class="text-red-400">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                placeholder="name@example.com" class="{{ $inputClass }}" style="color:#000" required>
                        </div>
                        <div>
                            <label class="{{ $labelClass }}" style="color:#000">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                placeholder="01XXXXXXXXX" class="{{ $inputClass }}" style="color:#000">
                        </div>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}" style="color:#000">
                            Role <span class="text-red-400">*</span>
                        </label>
                        <select name="role" class="{{ $inputClass }}" style="color:#000" required>
                            <option value="">Select role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $user->role) === $role->name ? 'selected' : '' }}>
                                    {{ $role->display_name ?? $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="{{ $labelClass }}" style="color:#000">New Password</label>
                            <input type="password" name="password" class="{{ $inputClass }}" style="color:#000">
                        </div>
                        <div>
                            <label class="{{ $labelClass }}" style="color:#000">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="{{ $inputClass }}" style="color:#000">
                        </div>
                    </div>
                </div>

                <!-- Profile -->
                <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-4">Profile</p>
                <div class="space-y-6 mb-8">
                    <div>
                        <label class="{{ $labelClass }}" style="color:#000">Photo</label>
                        @if($team?->image)
                            <img src="{{ asset('storage/' . $team->image) }}" class="w-14 h-14 rounded-xl object-cover mb-2 border border-indigo-500/30">
                        @endif
                        <input type="file" name="image" accept="image/*" class="{{ $inputClass }}" style="color:#000">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="{{ $labelClass }}" style="color:#000">WhatsApp</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $team?->whatsapp) }}" class="{{ $inputClass }}" style="color:#000">
                        </div>
                        <div>
                            <label class="{{ $labelClass }}" style="color:#000">Telegram</label>
                            <input type="text" name="telegram" value="{{ old('telegram', $team?->telegram) }}" class="{{ $inputClass }}" style="color:#000">
                        </div>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}" style="color:#000">Season</label>
                        <select name="season_id" class="{{ $inputClass }}" style="color:#000">
                            <option value="">—</option>
                            @foreach($seasons as $season)
                                <option value="{{ $season->id }}" {{ old('season_id', $team?->season_id) == $season->id ? 'selected' : '' }}>
                                    {{ $season->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Institute -->
                <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-4">Institute</p>
                <div class="space-y-6 mb-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="{{ $labelClass }}" style="color:#000">Institute Name</label>
                            <input type="text" name="institute_name" value="{{ old('institute_name', $team?->institute_name) }}" class="{{ $inputClass }}" style="color:#000">
                        </div>
                        <div>
                            <label class="{{ $labelClass }}" style="color:#000">Designation</label>
                            <input type="text" name="designation" value="{{ old('designation', $team?->designation) }}" class="{{ $inputClass }}" style="color:#000">
                        </div>
                        <div>
                            <label class="{{ $labelClass }}" style="color:#000">Department</label>
                            <input type="text" name="department" value="{{ old('department', $team?->department) }}" class="{{ $inputClass }}" style="color:#000">
                        </div>
                        <div>
                            <label class="{{ $labelClass }}" style="color:#000">EIIN No.</label>
                            <input type="text" name="eiin_no" value="{{ old('eiin_no', $team?->eiin_no) }}" class="{{ $inputClass }}" style="color:#000">
                        </div>
                        <div>
                            <label class="{{ $labelClass }}" style="color:#000">Institute Mobile</label>
                            <input type="text" name="institute_mobile" value="{{ old('institute_mobile', $team?->institute_mobile) }}" class="{{ $inputClass }}" style="color:#000">
                        </div>
                        <div>
                            <label class="{{ $labelClass }}" style="color:#000">Institute Email</label>
                            <input type="email" name="institute_email" value="{{ old('institute_email', $team?->institute_email) }}" class="{{ $inputClass }}" style="color:#000">
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-4">Address</p>
                <div class="space-y-6 mb-8">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <label class="{{ $labelClass }}" style="color:#000">Division</label>
                            <select id="division" name="division_id" class="{{ $inputClass }}" style="color:#000">
                                <option value="">—</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ old('division_id', $team?->division_id) == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="{{ $labelClass }}" style="color:#000">District</label>
                            <select id="district" name="district_id" class="{{ $inputClass }}" style="color:#000">
                                <option value="">—</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ old('district_id', $team?->district_id) == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="{{ $labelClass }}" style="color:#000">Thana</label>
                            <select id="thana" name="thana_id" class="{{ $inputClass }}" style="color:#000">
                                <option value="">—</option>
                                @foreach($thanas as $thana)
                                    <option value="{{ $thana->id }}" {{ old('thana_id', $team?->thana_id) == $thana->id ? 'selected' : '' }}>
                                        {{ $thana->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="{{ $labelClass }}" style="color:#000">Address</label>
                        <input type="text" name="address" value="{{ old('address', $team?->address) }}" class="{{ $inputClass }}" style="color:#000">
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-8">
                    <button type="submit"
                        class="btn-glow flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-all">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Save Changes
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                        class="px-6 py-2.5 rounded-xl glass-light hover:bg-indigo-500/10 text-indigo-300 text-sm font-medium transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const divisionSelect = document.getElementById('division');
            const districtSelect = document.getElementById('district');
            const thanaSelect = document.getElementById('thana');

            function loadOptions(select, url, placeholder) {
                fetch(url)
                    .then(res => res.json())
                    .then(items => {
                        select.innerHTML = '<option value="">' + placeholder + '</option>' +
                            items.map(item => `<option value="${item.id}">${item.name}</option>`).join('');
                    });
            }

            divisionSelect.addEventListener('change', function () {
                loadOptions(districtSelect, '/get-districts/' + this.value, '—');
                thanaSelect.innerHTML = '<option value="">—</option>';
            });

            districtSelect.addEventListener('change', function () {
                loadOptions(thanaSelect, '/get-thanas/' + this.value, '—');
            });
        })();
    </script>
</x-layouts.app>
