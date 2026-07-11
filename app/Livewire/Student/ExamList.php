<?php

namespace App\Livewire\Student;

use App\Models\Exam;
use App\Models\Round;
use App\Models\Subject;
use App\Models\Question;
use Livewire\Component;

class ExamList extends Component
{
    public function startExam($roundId, $subjectId)
    {
        $user = auth()->user();
        $round = Round::findOrFail($roundId);

        if (!$round->is_active) {
            session()->flash('error', 'This round is not active.');
            return;
        }

        // Check if exam already exists
        $exam = Exam::where('user_id', $user->id)
            ->where('round_id', $roundId)
            ->where('subject_id', $subjectId)
            ->first();

        if ($exam && $exam->status === 'completed') {
            session()->flash('error', 'You have already completed this exam.');
            return;
        }

        if (!$exam) {
            $exam = Exam::create([
                'user_id' => $user->id,
                'round_id' => $roundId,
                'subject_id' => $subjectId,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('student.exam.take', $exam);
    }

    public function render()
    {
        $user = auth()->user();
        $allowedOrder = $user->student?->effectiveRoundOrder() ?? 0;
        $activeRounds = Round::where('is_active', true)->where('order', '<=', $allowedOrder)->with('subjects')->orderBy('order')->get();
        $completedExams = Exam::where('user_id', $user->id)->where('status', 'completed')->get();

        return view('livewire.student.exam-list', [
            'activeRounds' => $activeRounds,
            'completedExams' => $completedExams,
        ]);
    }
}
