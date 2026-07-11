<?php

namespace App\Livewire\Admin;

use App\Exports\UsersExport;
use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterRole = '';
    public $filterStatus = '';
    public array $selectedIds = [];
    public bool $selectAll = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        $this->selectedIds = $value
            ? $this->baseQuery()->pluck('id')->map(fn ($id) => (string) $id)->toArray()
            : [];
    }

    protected function baseQuery()
    {
        $query = User::where('role', '!=', 'student')
            ->where(fn ($q) => $q->where('role', 'admin')->orWhereHas('team'))
            ->with('team')
            ->withCount('commissions');

        if ($this->search) {
            $query->where(fn ($q) => $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('phone', 'like', '%' . $this->search . '%'));
        }

        if ($this->filterRole) {
            $query->where('role', $this->filterRole);
        }

        if ($this->filterStatus === 'pending') {
            $query->whereHas('team', fn ($t) => $t->where('status', 0));
        } elseif ($this->filterStatus === 'approved') {
            $query->where(fn ($q) => $q->where('role', 'admin')->orWhereHas('team', fn ($t) => $t->where('status', 1)));
        } elseif ($this->filterStatus === 'rejected') {
            $query->whereHas('team', fn ($t) => $t->where('status', 2));
        }

        return $query;
    }

    public function export()
    {
        return Excel::download(
            new UsersExport($this->search, $this->filterRole, $this->filterStatus),
            'users-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function bulkDelete()
    {
        $ids = collect($this->selectedIds)
            ->reject(fn ($id) => (int) $id === auth()->id())
            ->all();

        User::whereIn('id', $ids)->where('role', '!=', 'admin')->get()->each(function (User $user) {
            $user->team?->delete();
            $user->delete();
        });

        $this->selectedIds = [];
        $this->selectAll = false;
        session()->flash('success', 'Selected users deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.user-list', [
            'users' => $this->baseQuery()->latest()->paginate(15),
            'roles' => Role::teamRoles(),
        ]);
    }
}
