<div class="space-y-8 max-w-2xl mx-auto">

    <div>
        <h2 class="text-3xl font-display font-bold text-gray-900 mb-1">My Banner</h2>
        <p class="text-sm text-gray-500">Generate a shareable banner and post it on your social media.</p>
    </div>

    <div class="glass-card rounded-[2.5rem] p-8 border border-gray-100 space-y-6">

        @if($bannerUrl)
            <div class="rounded-2xl overflow-hidden border border-gray-200">
                <img src="{{ $bannerUrl }}?v={{ now()->timestamp }}" alt="My Banner" class="w-full">
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-gray-300 p-12 text-center">
                <i data-lucide="image" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                <p class="text-sm text-gray-400">No banner generated yet. Click below to create one.</p>
            </div>
        @endif

        <button wire:click="generate" wire:loading.attr="disabled"
            class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-sm font-bold uppercase tracking-widest transition-all shadow-lg shadow-indigo-600/20 flex items-center justify-center gap-2">
            <i data-lucide="sparkles" class="w-5 h-5" wire:loading.remove></i>
            <div wire:loading class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
            {{ $bannerUrl ? 'Regenerate Banner' : 'Generate Banner' }}
        </button>

        @if($bannerUrl)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ $bannerUrl }}" download
                    class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                    <i data-lucide="download" class="w-4 h-4"></i> Download
                </a>
                <a href="{{ $shareUrl }}" target="_blank"
                    class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                    <i data-lucide="external-link" class="w-4 h-4"></i> View Share Page
                </a>
            </div>

            <div class="pt-4 border-t border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Share On</p>
                <div class="flex gap-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank"
                        class="flex-1 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold uppercase tracking-widest transition-all text-center">
                        Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}" target="_blank"
                        class="flex-1 py-3 bg-gray-900 hover:bg-gray-800 text-white rounded-xl text-xs font-bold uppercase tracking-widest transition-all text-center">
                        X / Twitter
                    </a>
                    <a href="https://wa.me/?text={{ urlencode(($student->name ?? 'I') . ' registered! ' . $shareUrl) }}" target="_blank"
                        class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold uppercase tracking-widest transition-all text-center">
                        WhatsApp
                    </a>
                </div>
            </div>
        @endif

    </div>

</div>
