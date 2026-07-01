<?php

namespace App\Livewire\Admin;

use App\Models\OfflineMark;
use App\Models\Round;
use App\Models\Subject;
use App\Models\User;
use App\Traits\AuthorizesWriteAction;
use Livewire\Component;
use Livewire\WithPagination;

class OfflineMarkEntry extends Component
{
    use WithPagination, AuthorizesWriteAction;

    public $student_id = '';
    public $group_id = '';
    public $round_id = '';
    public $subject_id = '';
    public $judge_name = '';
    public $marks = '';
    public $editingId = null;
    public $showForm = false;
    
    public $searchStudentQuery = '';
    public $selectedStudentName = '';

    public $searchStudent = '';
    public $filterGroup = '';
    public $filterRound = '';
    public $filterSubject = '';

    protected $rules = [
        'student_id' => 'required|exists:users,id',
        'round_id' => 'required|exists:rounds,id',
        'subject_id' => 'required|exists:subjects,id',
        'judge_name' => 'required|string|max:255',
        'marks' => 'required|numeric|min:0',
    ];

    public function openForm($id = null)
    {
        if (!$this->requireWrite($id ? 'offline-marks-edit' : 'offline-marks-create')) return;

        $this->resetValidation();
        if ($id) {
            $mark = OfflineMark::findOrFail($id);
            $this->editingId = $mark->id;
            $this->group_id = ''; // Note: group_id is not saved in offline_marks table, but used to filter students
            $this->judge_name = $mark->judge_name;
            $this->marks = $mark->marks;
            $this->selectedStudentName = $mark->student->name;
        } else {
            $this->reset(['editingId', 'student_id', 'group_id', 'round_id', 'subject_id', 'marks', 'selectedStudentName', 'searchStudentQuery']);
            $this->judge_name = auth()->user()->name;
        }
        $this->showForm = true;
    }

    public function setStudent($id, $name)
    {
        $this->student_id = $id;
        $this->selectedStudentName = $name;
        $this->searchStudentQuery = '';
    }

    public function updatedGroupId($value)
    {
        $this->student_id = '';
        $this->selectedStudentName = '';
    }

    public function closeForm()
    {
        $this->showForm = false;
    }

    public function save()
    {
        if (!$this->requireWrite($this->editingId ? 'offline-marks-edit' : 'offline-marks-create')) return;

        $this->validate();

        OfflineMark::updateOrCreate(
            ['id' => $this->editingId],
            [
                'student_id' => $this->student_id,
                'round_id' => $this->round_id,
                'subject_id' => $this->subject_id,
                'judge_name' => $this->judge_name,
                'marks' => $this->marks,
            ]
        );

        session()->flash('message', $this->editingId ? 'Mark updated!' : 'Mark recorded!');
        $this->closeForm();
    }

    public function delete($id)
    {
        if (!$this->requireWrite('offline-marks-delete')) return;

        OfflineMark::findOrFail($id)->delete();
        session()->flash('message', 'Mark deleted!');
    }

    public function render()
    {
        $query = OfflineMark::with(['student', 'round', 'subject']);

        if ($this->searchStudent) {
            $query->whereHas('student', function($q) {
                $q->where('name', 'like', '%' . $this->searchStudent . '%')
                  ->orWhere('id', 'like', '%' . $this->searchStudent . '%')
                  ->orWhere('email', 'like', '%' . $this->searchStudent . '%');
            });
        }
        if ($this->filterGroup) {
             $query->whereHas('student', function($q) {
                 $q->where('group', $this->filterGroup);
             });
        }
        if ($this->filterRound) {
            $query->where('round_id', $this->filterRound);
        }
        if ($this->filterSubject) {
            $query->where('subject_id', $this->filterSubject);
        }

        $formStudentsQuery = User::where('role', 'student');
        if ($this->group_id) {
             // Assuming User model has a group_id or group string. Adjust if necessary.
             $formStudentsQuery->where('group', \App\Models\Group::find($this->group_id)?->name ?? $this->group_id);
        }
        if (!empty($this->searchStudentQuery)) {
             $formStudentsQuery->where(function($q) {
                $q->where('name', 'like', '%' . $this->searchStudentQuery . '%')
                  ->orWhere('id', 'like', '%' . $this->searchStudentQuery . '%');
             });
        }

        $students = $formStudentsQuery->orderBy('name')->take(20)->get();
        $rounds = Round::orderBy('order')->get();
        $groups = \App\Models\Group::all();
        
        // Load subjects based on selected round in the form
        $formSubjects = $this->round_id ? Subject::where('round_id', $this->round_id)->get() : [];
        $filterSubjects = $this->filterRound ? Subject::where('round_id', $this->filterRound)->get() : Subject::all();

        return view('livewire.admin.offline-mark-entry', [
            'marksList' => $query->latest()->paginate(15),
            'students' => $students,
            'rounds' => $rounds,
            'groups' => $groups,
            'formSubjects' => $formSubjects,
            'filterSubjects' => $filterSubjects
        ]);
    }
}
