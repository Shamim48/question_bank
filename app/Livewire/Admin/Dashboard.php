<?php

namespace App\Livewire\Admin;

use App\Models\Round;
use App\Models\Subject;
use App\Models\Question;
use App\Models\User;
use App\Models\Exam;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'totalRounds' => Round::count(),
            'totalSubjects' => Subject::count(),
            'totalQuestions' => Question::count(),
            'totalStudents' => User::where('role', 'student')->count(),
            'activeRounds' => Round::where('is_active', true)->count(),
            'totalExams' => Exam::count(),
            'completedExams' => Exam::where('status', 'completed')->count(),
            'recentExams' => Exam::with(['user', 'round', 'subject'])->latest()->take(5)->get(),
            'questionsByType' => Question::selectRaw('type, count(*) as count')->groupBy('type')->pluck('count', 'type'),
        ]);
    }
}
