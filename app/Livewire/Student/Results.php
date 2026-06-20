<?php

namespace App\Livewire\Student;

use App\Models\Mark;
use App\Models\Exam;
use App\Models\Round;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Results extends Component
{
    public $filterRoundId = '';

    public function render()
    {
        $user = Auth::user();

        $marksQuery = Mark::where('user_id', $user->id)->with(['round', 'subject']);
        $examsQuery = Exam::where('user_id', $user->id)->where('status', 'completed')->with(['round', 'subject']);

        if ($this->filterRoundId) {
            $marksQuery->where('round_id', $this->filterRoundId);
            $examsQuery->where('round_id', $this->filterRoundId);
        }

        $rounds = Round::whereHas('marks', fn($q) => $q->where('user_id', $user->id))->orderBy('order')->get();

        return view('livewire.student.results', [
            'marks' => $marksQuery->get(),
            'exams' => $examsQuery->latest()->get(),
            'totalScore' => $marksQuery->sum('total_marks'),
            'rounds' => $rounds,
        ]);
    }
}
