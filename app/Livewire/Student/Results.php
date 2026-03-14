<?php

namespace App\Livewire\Student;

use App\Models\Mark;
use App\Models\Exam;
use Livewire\Component;

class Results extends Component
{
    public function render()
    {
        $user = auth()->user();

        return view('livewire.student.results', [
            'marks' => Mark::where('user_id', $user->id)->with(['round', 'subject'])->get(),
            'exams' => Exam::where('user_id', $user->id)->where('status', 'completed')->with(['round', 'subject'])->latest()->get(),
            'totalScore' => Mark::where('user_id', $user->id)->sum('total_marks'),
        ]);
    }
}
