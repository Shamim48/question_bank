<?php

namespace App\Livewire\Admin;

use App\Models\Exam;
use App\Models\Round;
use App\Models\User;
use App\Traits\AuthorizesWriteAction;
use Livewire\Component;

class ExamControl extends Component
{
    use AuthorizesWriteAction;

    public function toggleRound($id)
    {
        if (!$this->requireWrite('exams-control')) return;

        $round = Round::findOrFail($id);
        $round->update(['is_active' => !$round->is_active]);
        session()->flash('message', 'Round ' . ($round->is_active ? 'activated' : 'deactivated') . '!');
    }

    public function render()
    {
        return view('livewire.admin.exam-control', [
            'rounds'      => Round::withCount(['exams', 'questions', 'subjects'])->orderBy('order')->get(),
            'recentExams' => Exam::with(['user', 'round', 'subject'])->latest()->take(10)->get(),
            'students'    => User::where('role', 'student')->count(),
        ]);
    }
}
