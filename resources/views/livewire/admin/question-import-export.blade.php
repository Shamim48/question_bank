<div class="slide-up mt-8">
    <div class="glass rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Import / Export Questions</h3>
        @if(session('message'))
            <div class="mb-4 rounded-xl p-3 border-l-4 border-emerald-500 bg-emerald-500/10">
                <p class="text-emerald-300 text-sm">{{ session('message') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded-xl p-3 border-l-4 border-red-500 bg-red-500/10">
                <p class="text-red-300 text-sm">{{ session('error') }}</p>
            </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Import -->
            <div class="glass-light rounded-xl p-5">
                <h4 class="text-sm font-medium text-indigo-300 mb-3">Import from Excel</h4>
                <form wire:submit.prevent="import" class="space-y-3">
                    <input type="file" wire:model="file"
                        class="w-full bg-gray-800/50 border border-gray-700 rounded-xl px-4 py-2 text-white text-sm"
                        accept=".xlsx,.xls,.csv">
                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-medium transition-all disabled:opacity-50 flex items-center justify-center gap-2">
                        <i data-lucide="upload" class="w-4 h-4" wire:loading.remove wire:target="import"></i>
                        <div wire:loading wire:target="import" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                        <span>Upload & Import</span>
                    </button>
                </form>
                <p class="text-xs text-gray-500 mt-3">Columns: round, subject, group, question, option_1, option_2, option_3, option_4,
                    correct_option, type, time_limit, points, media_url</p>
            </div>
            <!-- Export -->
            <div class="glass-light rounded-xl p-5 flex flex-col">
                <h4 class="text-sm font-medium text-indigo-300 mb-3">Export to Excel</h4>
                <p class="text-sm text-gray-400 mb-4">Download questions by applying conditions (filters).</p>
                <div class="space-y-3 mb-4">
                    <select wire:model="export_round_id" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-black text-sm focus:ring-2 focus:ring-indigo-500/50">
                        <option value="">All Rounds</option>
                        @foreach($rounds as $round)
                            <option value="{{ $round->id }}">{{ $round->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model="export_subject_id" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-black text-sm focus:ring-2 focus:ring-indigo-500/50">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->round->name }})</option>
                        @endforeach
                    </select>
                    <select wire:model="export_group_id" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-black text-sm focus:ring-2 focus:ring-indigo-500/50">
                        <option value="">All Groups</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button wire:click="export" wire:loading.attr="disabled"
                    class="mt-auto w-full px-4 py-2.5 bg-purple-600 hover:bg-purple-500 text-white rounded-xl text-sm font-medium transition-all disabled:opacity-50 flex items-center justify-center gap-2">
                    <i data-lucide="download" class="w-4 h-4" wire:loading.remove wire:target="export"></i>
                    <div wire:loading wire:target="export" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                    <span>Download Excel</span>
                </button>
            </div>
        </div>
    </div>
</div>