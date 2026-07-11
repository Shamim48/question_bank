<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    protected $search;
    protected $role;
    protected $status;

    public function __construct($search = null, $role = null, $status = null)
    {
        $this->search = $search;
        $this->role   = $role;
        $this->status = $status;
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Phone', 'Role', 'Status', 'Referral Code', 'Referrals', 'Joined'];
    }

    public function collection()
    {
        $query = User::where('role', '!=', 'student')
            ->where(fn ($q) => $q->where('role', 'admin')->orWhereHas('team'))
            ->with('team');

        if ($this->search) {
            $query->where(fn ($q) => $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('phone', 'like', '%' . $this->search . '%'));
        }

        if ($this->role) {
            $query->where('role', $this->role);
        }

        if ($this->status === 'pending') {
            $query->whereHas('team', fn ($t) => $t->where('status', 0));
        } elseif ($this->status === 'approved') {
            $query->where(fn ($q) => $q->where('role', 'admin')->orWhereHas('team', fn ($t) => $t->where('status', 1)));
        } elseif ($this->status === 'rejected') {
            $query->whereHas('team', fn ($t) => $t->where('status', 2));
        }

        return $query->latest()->get()->map(function ($user) {
            $statusLabel = match (true) {
                $user->role === 'admin' => 'Active',
                $user->team?->status === 1 => 'Approved',
                $user->team?->status === 2 => 'Rejected',
                default => 'Pending',
            };

            return [
                'name'           => $user->name,
                'email'          => $user->email,
                'phone'          => $user->phone,
                'role'           => $user->role,
                'status'         => $statusLabel,
                'referral_code'  => $user->referral_code ?? '',
                'referrals'      => $user->commissions()->count(),
                'joined'         => optional($user->created_at)->format('Y-m-d'),
            ];
        });
    }
}
