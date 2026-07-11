<?php

namespace App\Livewire\Admin;

use App\Models\Commission;
use App\Traits\AuthorizesWriteAction;
use Livewire\Component;
use Livewire\WithPagination;

class CommissionManager extends Component
{
    use WithPagination, AuthorizesWriteAction;

    public $filterStatus = '';

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function markPaid($id)
    {
        if (!$this->requireWrite('commissions-mark-paid')) return;

        $commission = Commission::findOrFail($id);

        $user = auth()->user();
        if (!$user->isAdmin() && $commission->referrer_user_id !== $user->id) {
            session()->flash('error', 'You do not have permission to update this commission.');
            return;
        }

        $commission->update(['status' => 'paid']);
        session()->flash('success', 'Commission marked as paid.');
    }

    public function render()
    {
        $user = auth()->user();

        $baseQuery = Commission::query();
        if (!$user->isAdmin()) {
            $baseQuery->where('referrer_user_id', $user->id);
        }

        $listQuery = (clone $baseQuery)->with(['referrer', 'student']);
        if ($this->filterStatus) {
            $listQuery->where('status', $this->filterStatus);
        }

        return view('livewire.admin.commission-manager', [
            'commissions'    => $listQuery->latest()->paginate(15),
            'myReferralCode' => $user->referral_code,
            'myShareLink'    => $user->referral_code ? route('registration.participant', ['ref' => $user->referral_code]) : null,
            'pendingTotal'   => (clone $baseQuery)->where('status', 'pending')->sum('amount'),
            'paidTotal'      => (clone $baseQuery)->where('status', 'paid')->sum('amount'),
        ]);
    }
}
