<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use App\Traits\AuthorizesWriteAction;
use Livewire\Component;

class SystemSettings extends Component
{
    use AuthorizesWriteAction;

    public bool $loginLocked = false;

    public function mount()
    {
        $this->loginLocked = (bool) Setting::get('login_locked', false);
    }

    public function save()
    {
        if (!$this->requireWrite('settings-manage')) return;

        Setting::set('login_locked', $this->loginLocked ? '1' : '0');

        session()->flash('success', 'Settings saved successfully.');
    }

    public function render()
    {
        return view('livewire.admin.system-settings');
    }
}
