<?php

namespace App\Livewire\Admin;

use App\Models\Certificate;
use App\Models\Group;
use App\Models\Round;
use App\Models\User;
use App\Traits\AuthorizesWriteAction;
use Illuminate\Support\Str;
use Livewire\Component;

class CertificateManager extends Component
{
    use AuthorizesWriteAction;

    public $user_id  = '';
    public $round_id = '';
    public $group_id = '';

    protected $rules = [
        'user_id'  => 'required|exists:users,id',
        'round_id' => 'required|exists:rounds,id',
        'group_id' => 'required|exists:groups,id',
    ];

    public function generate()
    {
        if (!$this->requireWrite('certificates-create')) return;

        $this->validate();

        $existing = Certificate::where('user_id', $this->user_id)
            ->where('round_id', $this->round_id)
            ->where('group_id', $this->group_id)
            ->first();

        if ($existing) {
            session()->flash('error', 'Certificate already exists for this student, round, and group!');
            return;
        }

        Certificate::create([
            'user_id'            => $this->user_id,
            'round_id'           => $this->round_id,
            'group_id'           => $this->group_id,
            'certificate_number' => 'CERT-' . strtoupper(Str::random(8)),
            'issued_at'          => now(),
        ]);

        session()->flash('message', 'Certificate generated successfully!');
        $this->reset(['user_id', 'round_id', 'group_id']);
    }

    public function delete($id)
    {
        if (!$this->requireWrite('certificates-delete')) return;

        Certificate::findOrFail($id)->delete();
        session()->flash('message', 'Certificate deleted!');
    }

    public function render()
    {
        return view('livewire.admin.certificate-manager', [
            'certificates' => Certificate::with(['user', 'round', 'group'])->latest()->get(),
            'students'     => User::where('role', 'student')->orderBy('name')->get(),
            'rounds'       => Round::orderBy('order')->get(),
            'groups'       => Group::all(),
        ]);
    }
}
