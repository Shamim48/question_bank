<div class="space-y-8">
    @php $isAdmin = auth()->user()->isAdmin(); @endphp

    <div>
        <h2 class="text-3xl font-display font-bold text-gray-900 mb-1">Referrals &amp; Commissions</h2>
        <p class="text-sm text-gray-500">Track referral registrations and commission payouts.</p>
    </div>

    @if($myReferralCode)
        <div class="glass-card rounded-2xl border border-indigo-100 p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Your Referral Code</p>
                <p class="text-lg font-mono font-bold text-indigo-600">{{ $myReferralCode }}</p>
            </div>
            <div class="flex-1 min-w-0 md:max-w-md">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Shareable Registration Link</p>
                <input type="text" readonly value="{{ $myShareLink }}" onclick="this.select()"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-700 text-xs font-mono">
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="glass-card rounded-2xl p-6 flex items-center justify-between">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Pending Commission</span>
            <span class="text-xl font-display font-bold text-amber-500">৳{{ number_format($pendingTotal, 2) }}</span>
        </div>
        <div class="glass-card rounded-2xl p-6 flex items-center justify-between">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Paid Commission</span>
            <span class="text-xl font-display font-bold text-emerald-500">৳{{ number_format($paidTotal, 2) }}</span>
        </div>
    </div>

    <div class="glass rounded-3xl p-2">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-2">
            <div class="md:col-span-3">
                <select wire:model.live="filterStatus"
                    class="w-full bg-white/5 border-none rounded-2xl px-4 py-3.5 text-gray-900 text-sm focus:ring-2 focus:ring-indigo-500/50">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                </select>
            </div>
        </div>
    </div>

    <div class="glass rounded-[2rem] overflow-hidden border border-white/5">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5">
                    @if($isAdmin)
                        <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Referrer</th>
                    @endif
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Student</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Amount</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Status</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Date</th>
                    <th class="py-5 px-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($commissions as $commission)
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        @if($isAdmin)
                            <td class="py-5 px-8">
                                <p class="text-sm font-bold text-gray-900">{{ $commission->referrer->name ?? '—' }}</p>
                                <p class="text-[10px] text-gray-500 font-mono">{{ $commission->referrer->referral_code ?? '' }}</p>
                            </td>
                        @endif
                        <td class="py-5 px-8">
                            <p class="text-sm text-gray-900">{{ $commission->student->name ?? '—' }}</p>
                            <p class="text-[10px] text-gray-500">{{ $commission->student->student_id ?? '' }}</p>
                        </td>
                        <td class="py-5 px-8">
                            <span class="text-sm font-bold text-gray-900">৳{{ number_format($commission->amount, 2) }}</span>
                        </td>
                        <td class="py-5 px-8">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg {{ $commission->status === 'paid' ? 'bg-emerald-500/10 border border-emerald-500/20 text-emerald-600' : 'bg-amber-500/10 border border-amber-500/20 text-amber-600' }}">
                                {{ ucfirst($commission->status) }}
                            </span>
                        </td>
                        <td class="py-5 px-8">
                            <p class="text-xs text-gray-500">{{ $commission->created_at->format('M d, Y') }}</p>
                        </td>
                        <td class="py-5 px-8 text-right">
                            @if($commission->status === 'pending')
                                <button wire:click="markPaid({{ $commission->id }})" wire:confirm="Mark this commission as paid?"
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-bold uppercase tracking-wider rounded-xl transition-all">
                                    Mark Paid
                                </button>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $isAdmin ? 6 : 5 }}" class="py-20 text-center">
                            <i data-lucide="badge-percent" class="w-12 h-12 text-gray-700 mx-auto mb-4"></i>
                            <p class="text-gray-500 font-medium">No commission records found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($commissions->hasPages())
        <div class="flex justify-center">
            {{ $commissions->links() }}
        </div>
    @endif
</div>
