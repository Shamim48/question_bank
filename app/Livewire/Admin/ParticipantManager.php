<?php

namespace App\Livewire\Admin;

use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;

class ParticipantManager extends Component
{
    use WithPagination;

    public $search = '';
    public $viewingId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function view($id)
    {
        $this->viewingId = $id;
    }

    public function closeView()
    {
        $this->viewingId = null;
    }

    public function render()
    {
        $query = Student::with(['classLevel', 'group', 'season', 'division', 'district', 'thana']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('student_id', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.admin.participant-manager', [
            'students'       => $query->latest()->paginate(15),
            'viewingStudent' => $this->viewingId ? Student::with(['classLevel', 'group', 'season', 'division', 'district', 'thana'])->find($this->viewingId) : null,
        ]);
    }
}
