<?php

namespace App\Livewire\Admin;

use App\Models\Mark;
use App\Models\User;
use App\Models\Round;
use App\Models\Subject;
use Livewire\Component;

class ManualMarking extends Component
{
    public $user_id = '';
    public $round_id = '';
    public $subject_id = '';
    public $manual_marks = 0;
    public $subjects = [];

    public $searchStudent = '';
    public $selectedStudentName = '';

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'round_id' => 'required|exists:rounds,id',
        'subject_id' => 'required|exists:subjects,id',
        'manual_marks' => 'required|numeric|min:0',
    ];

    public function setStudent($id, $name)
    {
        $this->user_id = $id;
        $this->selectedStudentName = $name;
        $this->searchStudent = '';
    }

    public function updatedRoundId($value)
    {
        $this->subjects = Subject::where('round_id', $value)->get()->toArray();
        $this->subject_id = '';
    }

    public function save()
    {
        $this->validate();

        $mark = Mark::updateOrCreate(
            [
                'user_id' => $this->user_id,
                'round_id' => $this->round_id,
                'subject_id' => $this->subject_id,
            ],
            [
                'manual_marks' => $this->manual_marks,
                'admin_id' => auth()->id(),
            ]
        );

        $mark->total_marks = $mark->online_marks + $mark->manual_marks;
        $mark->save();

        session()->flash('message', 'Marks saved successfully!');
        $this->reset(['user_id', 'round_id', 'subject_id', 'manual_marks', 'selectedStudentName', 'searchStudent']);
    }

    public function render()
    {
        $studentsQuery = User::where('role', 'student');
        
        if (!empty($this->searchStudent)) {
            $studentsQuery->where(function($q) {
                $q->where('name', 'like', '%' . $this->searchStudent . '%')
                  ->orWhere('id', 'like', '%' . $this->searchStudent . '%');
            });
        }
        
        return view('livewire.admin.manual-marking', [
            'students' => $studentsQuery->orderBy('name')->take(20)->get(),
            'rounds' => Round::orderBy('order')->get(),
            'allMarks' => Mark::with(['user', 'round', 'subject', 'admin'])->latest()->take(20)->get(),
        ]);
    }
}
