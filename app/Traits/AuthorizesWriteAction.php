<?php

namespace App\Traits;

trait AuthorizesWriteAction
{
    /**
     * Check write permission. Returns false and flashes error if denied.
     * Use at the top of any mutating Livewire method:
     *   if (!$this->requireWrite('admin.rounds.write')) return;
     */
    protected function requireWrite(string $permission): bool
    {
        $user = auth()->user();

        if ($user->isAdmin() || $user->hasPermission($permission)) {
            return true;
        }

        session()->flash('error', 'You do not have permission to perform this action.');
        return false;
    }
}
