<?php

namespace App\Livewire\Admin;

use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class UserManagementDashboard extends Component
{
    public function render()
    {
        $teamRoles = Role::teamRoles();

        $userCountsByRole = User::whereIn('role', $teamRoles->pluck('name'))
            ->selectRaw('role, count(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role');

        $categories = $teamRoles->map(fn ($role) => [
            'label' => $role->display_name ?: $role->name,
            'count' => (int) ($userCountsByRole[$role->name] ?? 0),
        ]);

        $groups = Group::withCount('students')->orderBy('name')->get()->map(fn ($group) => [
            'label' => $group->name,
            'count' => $group->students_count,
        ]);

        return view('livewire.admin.user-management-dashboard', [
            'totalTeamUsers' => $categories->sum('count'),
            'totalStudents'  => $groups->sum('count'),
            'categories'     => $categories,
            'groups'         => $groups,
        ]);
    }
}
