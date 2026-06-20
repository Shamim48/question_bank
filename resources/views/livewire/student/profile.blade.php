<div class="space-y-8 animate__animated animate__fadeIn max-w-4xl mx-auto">

    <!-- Page Header -->
    <div>
        <h2 class="text-3xl font-display font-bold text-gray-900 mb-1">My Profile</h2>
        <p class="text-sm text-gray-500">View and manage your personal information and account settings.</p>
    </div>

    <!-- Avatar + Identity Card -->
    <div class="glass-card rounded-[2.5rem] p-8 border border-gray-100 flex flex-col sm:flex-row items-center sm:items-start gap-8">
        <!-- Avatar -->
        <div class="shrink-0 flex flex-col items-center gap-3">
            <div class="w-28 h-28 rounded-[2rem] overflow-hidden border-4 border-indigo-100 shadow-lg shadow-indigo-100">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff&size=200"
                    class="w-full h-full object-cover" alt="{{ $user->name }}">
            </div>
            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-indigo-100 text-indigo-700 border border-indigo-200' }}">
                {{ ucfirst($user->role) }}
            </span>
        </div>

        <!-- Identity Info -->
        <div class="flex-1 min-w-0">
            <h3 class="text-2xl font-display font-bold text-gray-900 mb-1 truncate">{{ $user->name }}</h3>
            <p class="text-sm text-gray-400 mb-6">{{ $user->email }}</p>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @php
                    $meta = [
                        ['label' => 'Group',    'value' => $user->group    ?? '—', 'icon' => 'users'],
                        ['label' => 'Class',    'value' => $user->class    ?? '—', 'icon' => 'graduation-cap'],
                        ['label' => 'Phone',    'value' => $user->phone    ?? '—', 'icon' => 'phone'],
                        ['label' => 'Division', 'value' => $user->division ?? '—', 'icon' => 'map-pin'],
                        ['label' => 'District', 'value' => $user->district ?? '—', 'icon' => 'map'],
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

    <!-- Edit Profile Section -->
    <div class="glass-card rounded-[2.5rem] border border-gray-100 overflow-hidden">
        <!-- Section Header -->
        <div class="flex items-center justify-between p-8 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600">
                    <i data-lucide="user-pen" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-base font-bold text-gray-900">Personal Information</h4>
                    <p class="text-xs text-gray-400">Update your name and contact number</p>
                </div>
            </div>
            @if(!$editingProfile)
                <button wire:click="$set('editingProfile', true)"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-sm shadow-indigo-600/20">
                    Edit
                </button>
            @endif
        </div>

        <div class="p-8">
            @if($profileMessage)
                <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                    <i data-lucide="check-circle" class="w-4 h-4 shrink-0 text-emerald-500"></i>
                    {{ $profileMessage }}
                </div>
            @endif

            @if($editingProfile)
                <form wire:submit="saveProfile" class="space-y-5">
                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Full Name</label>
                        <input wire:model="name" type="text" placeholder="Your full name"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all">
                        @error('name') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Phone Number</label>
                        <input wire:model="phone" type="text" placeholder="e.g. 01712345678"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all">
                        @error('phone') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-sm shadow-indigo-600/20 flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Save Changes
                        </button>
                        <button type="button" wire:click="cancelProfile"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Full Name</p>
                        <p class="text-base font-semibold text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Email Address</p>
                        <p class="text-base font-semibold text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Phone Number</p>
                        <p class="text-base font-semibold text-gray-900">{{ $user->phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Member Since</p>
                        <p class="text-base font-semibold text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Change Password Section -->
    <div class="glass-card rounded-[2.5rem] border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between p-8 border-b border-gray-100 bg-gray-50">
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
                    class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-sm shadow-amber-500/20">
                    Change
                </button>
            @endif
        </div>

        <div class="p-8">
            @if($passwordMessage)
                <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                    <i data-lucide="check-circle" class="w-4 h-4 shrink-0 text-emerald-500"></i>
                    {{ $passwordMessage }}
                </div>
            @endif

            @if($changingPassword)
                <form wire:submit="savePassword" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Current Password</label>
                        <input wire:model="currentPassword" type="password" placeholder="Enter current password"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all">
                        @error('currentPassword') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">New Password</label>
                        <input wire:model="newPassword" type="password" placeholder="Minimum 8 characters"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all">
                        @error('newPassword') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Confirm New Password</label>
                        <input wire:model="newPasswordConfirmation" type="password" placeholder="Repeat new password"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all">
                        @error('newPasswordConfirmation') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="px-6 py-3 bg-amber-500 hover:bg-amber-400 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-sm shadow-amber-500/20 flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-4 h-4"></i> Update Password
                        </button>
                        <button type="button" wire:click="cancelPassword"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold uppercase tracking-widest rounded-xl transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            @else
                <p class="text-sm text-gray-400">Password last updated: <span class="font-semibold text-gray-600">{{ $user->updated_at->format('M d, Y') }}</span></p>
            @endif
        </div>
    </div>

    <!-- Read-only Account Details -->
    <div class="glass-card rounded-[2.5rem] border border-gray-100 overflow-hidden">
        <div class="flex items-center gap-3 p-8 border-b border-gray-100 bg-gray-50">
            <div class="w-9 h-9 rounded-xl bg-gray-200 flex items-center justify-center text-gray-500">
                <i data-lucide="info" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-base font-bold text-gray-900">Account Details</h4>
                <p class="text-xs text-gray-400">Managed by administrator — contact admin to update</p>
            </div>
        </div>
        <div class="p-8 grid grid-cols-2 sm:grid-cols-4 gap-5">
            @php
                $details = [
                    ['label' => 'Group',    'value' => $user->group    ?? '—'],
                    ['label' => 'Class',    'value' => $user->class    ?? '—'],
                    ['label' => 'Division', 'value' => $user->division ?? '—'],
                    ['label' => 'District', 'value' => $user->district ?? '—'],
                ];
            @endphp
            @foreach($details as $detail)
                <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $detail['label'] }}</p>
                    <p class="text-sm font-bold text-gray-700">{{ $detail['value'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

</div>
