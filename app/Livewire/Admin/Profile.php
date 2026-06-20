<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Profile extends Component
{
    public string $name = '';
    public string $phone = '';

    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';

    public bool $editingProfile = false;
    public bool $changingPassword = false;
    public string $profileMessage = '';
    public string $passwordMessage = '';

    public function mount()
    {
        $user = Auth::user();
        $this->name  = $user->name;
        $this->phone = $user->phone ?? '';
    }

    public function saveProfile()
    {
        $this->validate([
            'name'  => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        Auth::user()->update([
            'name'  => trim($this->name),
            'phone' => trim($this->phone) ?: null,
        ]);

        $this->editingProfile = false;
        $this->profileMessage = 'Profile updated successfully.';
    }

    public function savePassword()
    {
        $this->validate([
            'currentPassword'         => ['required', 'string'],
            'newPassword'             => ['required', 'confirmed', Password::min(8)],
            'newPasswordConfirmation' => ['required'],
        ]);

        if (!Hash::check($this->currentPassword, Auth::user()->password)) {
            $this->addError('currentPassword', 'Current password is incorrect.');
            return;
        }

        Auth::user()->update([
            'password' => Hash::make($this->newPassword),
        ]);

        $this->currentPassword         = '';
        $this->newPassword             = '';
        $this->newPasswordConfirmation = '';
        $this->changingPassword        = false;
        $this->passwordMessage         = 'Password changed successfully.';
    }

    public function cancelProfile()
    {
        $user        = Auth::user();
        $this->name  = $user->name;
        $this->phone = $user->phone ?? '';
        $this->editingProfile = false;
        $this->profileMessage = '';
    }

    public function cancelPassword()
    {
        $this->currentPassword         = '';
        $this->newPassword             = '';
        $this->newPasswordConfirmation = '';
        $this->changingPassword        = false;
        $this->passwordMessage         = '';
    }

    public function render()
    {
        return view('livewire.admin.profile', [
            'user' => Auth::user(),
        ]);
    }
}
