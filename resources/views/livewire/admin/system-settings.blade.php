<div class="space-y-8 max-w-2xl">

    <div>
        <h2 class="text-3xl font-display font-bold text-gray-900 mb-1">System Settings</h2>
        <p class="text-sm text-gray-500">Site-wide access controls.</p>
    </div>

    <div class="glass-card rounded-[2.5rem] border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between p-8 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-red-100 flex items-center justify-center text-red-600">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-base font-bold text-gray-900">Login Lockdown</h4>
                    <p class="text-xs text-gray-400">Blocks login for everyone except Super Admin</p>
                </div>
            </div>
        </div>

        <div class="p-8">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" wire:model="loginLocked" class="w-5 h-5 rounded text-red-600 focus:ring-red-500/50">
                <span class="text-sm font-semibold text-gray-900">
                    Temporarily disable login for all Team &amp; Student accounts
                </span>
            </label>
            <p class="text-xs text-gray-400 mt-2 ml-8">
                Admins can always log in regardless of this setting.
            </p>

            <button wire:click="save" wire:confirm="Are you sure? This will immediately block login for all non-admin accounts."
                class="mt-6 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Save Settings
            </button>
        </div>
    </div>

</div>
