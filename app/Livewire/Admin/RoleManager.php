<?php

namespace App\Livewire\Admin;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Component;

class RoleManager extends Component
{
    // Role form
    public string $name         = '';
    public string $display_name = '';
    public ?int   $editingId    = null;
    public bool   $showForm     = false;

    // Permission management
    public ?int   $managingPermissionsId   = null;
    public string $managingPermissionsName = '';
    public array  $rolePermissions         = [];

    protected function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:50',
                'unique:roles,name' . ($this->editingId ? ",{$this->editingId}" : ''),
            ],
            'display_name' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function openCreate(): void
    {
        $this->closePermissions();
        $this->reset('name', 'display_name', 'editingId');
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $this->closePermissions();
        $role               = Role::findOrFail($id);
        $this->editingId    = $role->id;
        $this->name         = $role->name;
        $this->display_name = $role->display_name ?? '';
        $this->showForm     = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editingId) {
            Role::findOrFail($this->editingId)->update([
                'name'         => $this->name,
                'display_name' => $this->display_name ?: $this->name,
            ]);
            session()->flash('success', 'Role updated successfully.');
        } else {
            Role::create([
                'name'         => $this->name,
                'display_name' => $this->display_name ?: $this->name,
                'status'       => 1,
            ]);
            session()->flash('success', 'Role created successfully.');
        }

        $this->reset('name', 'display_name', 'editingId', 'showForm');
    }

    public function toggleStatus(int $id): void
    {
        $role = Role::findOrFail($id);

        if (in_array($role->name, ['admin', 'student'])) {
            session()->flash('error', 'System roles cannot be modified.');
            return;
        }

        $role->update(['status' => $role->status ? 0 : 1]);
    }

    public function delete(int $id): void
    {
        $role = Role::findOrFail($id);

        if (in_array($role->name, ['admin', 'student'])) {
            session()->flash('error', 'System roles cannot be deleted.');
            return;
        }

        if ($this->managingPermissionsId === $id) {
            $this->closePermissions();
        }

        $role->delete();
        session()->flash('success', 'Role deleted.');
    }

    public function cancel(): void
    {
        $this->reset('name', 'display_name', 'editingId', 'showForm');
    }

    // ── Permission management ────────────────────────────────────────────────

    public function managePermissions(int $id): void
    {
        if ($this->managingPermissionsId === $id) {
            $this->closePermissions();
            return;
        }

        $this->showForm = false;
        $role = Role::with('permissions')->findOrFail($id);

        $this->managingPermissionsId   = $id;
        $this->managingPermissionsName = $role->display_name ?? $role->name;
        $this->rolePermissions         = $role->permissions->pluck('name')->toArray();
    }

    public function savePermissions(): void
    {
        $role = Role::findOrFail($this->managingPermissionsId);

        $permissionIds = Permission::whereIn('name', $this->rolePermissions)->pluck('id');
        $role->permissions()->sync($permissionIds);

        session()->flash('success', "Permissions updated for \"{$role->display_name}\".");
        $this->closePermissions();
    }

    public function closePermissions(): void
    {
        $this->reset('managingPermissionsId', 'managingPermissionsName', 'rolePermissions');
    }

    public function render()
    {
        return view('livewire.admin.role-manager', [
            'roles'              => Role::latest()->get(),
            'groupedPermissions' => Permission::allGrouped(),
        ]);
    }
}
