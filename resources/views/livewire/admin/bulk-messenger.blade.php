<div class="space-y-8">

    <div>
        <h2 class="text-3xl font-display font-bold text-gray-900 mb-1">Bulk SMS &amp; Email</h2>
        <p class="text-sm text-gray-500">Message students or team members by group, class, season, or role.</p>
    </div>

    <div class="glass-card rounded-[2.5rem] p-8 border border-gray-100 space-y-6">

        <!-- Audience Type -->
        <div class="flex gap-3">
            <button type="button" wire:click="$set('audienceType', 'students')"
                class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all {{ $audienceType === 'students' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Students
            </button>
            <button type="button" wire:click="$set('audienceType', 'team')"
                class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all {{ $audienceType === 'team' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Team Members
            </button>
        </div>

        <!-- Filters -->
        <div class="glass rounded-3xl p-2">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2">
                @if($audienceType === 'students')
                    <div class="md:col-span-4">
                        <select wire:model.live="filterGroupId"
                            class="w-full bg-white/5 border-none rounded-2xl px-4 py-3.5 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-500/50">
                            <option value="">All Groups</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-4">
                        <select wire:model.live="filterClassId"
                            class="w-full bg-white/5 border-none rounded-2xl px-4 py-3.5 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-500/50">
                            <option value="">All Classes</option>
                            @foreach($classLevels as $classLevel)
                                <option value="{{ $classLevel->id }}">{{ $classLevel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-4">
                        <select wire:model.live="filterSeasonId"
                            class="w-full bg-white/5 border-none rounded-2xl px-4 py-3.5 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-500/50">
                            <option value="">All Seasons</option>
                            @foreach($seasons as $season)
                                <option value="{{ $season->id }}">{{ $season->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="md:col-span-12">
                        <select wire:model.live="filterRole"
                            class="w-full bg-white/5 border-none rounded-2xl px-4 py-3.5 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-500/50">
                            <option value="">All Team Categories</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->display_name ?: $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>

        <div class="px-4 py-3 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center gap-2">
            <i data-lucide="users" class="w-4 h-4 text-indigo-600"></i>
            <span class="text-sm font-semibold text-indigo-700">{{ $recipientCount }} recipient(s) match this audience</span>
        </div>

        <!-- Channel -->
        <div class="flex gap-3">
            <button type="button" wire:click="$set('channel', 'email')"
                class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all flex items-center gap-2 {{ $channel === 'email' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                <i data-lucide="mail" class="w-4 h-4"></i> Email
            </button>
            <button type="button" wire:click="$set('channel', 'sms')"
                class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all flex items-center gap-2 {{ $channel === 'sms' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                <i data-lucide="message-square" class="w-4 h-4"></i> SMS
            </button>
        </div>

        @if($channel === 'email')
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Subject</label>
                <input type="text" wire:model="subject" placeholder="e.g. Important Update"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all">
                @error('subject') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        @endif

        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Message</label>
            <textarea wire:model="body" rows="5" placeholder="Type your message here..."
                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"></textarea>
            @error('body') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3">
            <button wire:click="saveDraft" wire:loading.attr="disabled"
                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Save Draft
            </button>
            <button wire:click="send" wire:confirm="Send this message to {{ $recipientCount }} recipient(s) now?" wire:loading.attr="disabled"
                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                <i data-lucide="send" class="w-4 h-4"></i> Send Now
            </button>
        </div>
    </div>

    <!-- Drafts -->
    @if($drafts->count() > 0)
        <div class="glass-card rounded-[2rem] border border-gray-100 overflow-hidden">
            <div class="px-8 py-4 border-b border-gray-100 bg-gray-50">
                <h4 class="text-sm font-bold text-gray-900">Saved Drafts</h4>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($drafts as $draft)
                    <div class="flex items-center justify-between gap-4 px-8 py-4">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $draft->subject ?: \Illuminate\Support\Str::limit($draft->body, 60) }}</p>
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest">{{ strtoupper($draft->channel) }} • {{ $draft->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button wire:click="loadDraft({{ $draft->id }})"
                                class="px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-[10px] font-bold uppercase tracking-wider rounded-xl transition-all">
                                Edit
                            </button>
                            <button wire:click="deleteDraft({{ $draft->id }})" wire:confirm="Delete this draft?"
                                class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-500 text-[10px] font-bold uppercase tracking-wider rounded-xl transition-all">
                                Delete
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- History -->
    <div class="glass-card rounded-[2rem] border border-gray-100 overflow-hidden">
        <div class="px-8 py-4 border-b border-gray-100 bg-gray-50">
            <h4 class="text-sm font-bold text-gray-900">Send History</h4>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($history as $item)
                <div class="flex items-center justify-between gap-4 px-8 py-4">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $item->subject ?: \Illuminate\Support\Str::limit($item->body, 60) }}</p>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest">{{ strtoupper($item->channel) }} • {{ $item->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-xs font-bold text-emerald-600">{{ $item->sent_count }} sent</p>
                        @if($item->failed_count > 0)
                            <p class="text-[10px] text-red-500">{{ $item->failed_count }} failed</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-8 py-12 text-center text-gray-400 text-sm">No messages sent yet.</div>
            @endforelse
        </div>
    </div>

</div>
