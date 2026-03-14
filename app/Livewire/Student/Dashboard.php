<?php

namespace App\Livewire\Student;

use App\Models\Exam;
use App\Models\Mark;
use App\Models\Round;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        return view('livewire.student.dashboard', [
            'activeRounds' => Round::where('is_active', true)->orderBy('order')->get(),
            'recentExams' => Exam::where('user_id', $user->id)->with(['round', 'subject'])->latest()->take(5)->get(),
            'marks' => Mark::where('user_id', $user->id)->with(['round', 'subject'])->get(),
            'totalScore' => Mark::where('user_id', $user->id)->sum('total_marks'),
        ]);
    }
}
