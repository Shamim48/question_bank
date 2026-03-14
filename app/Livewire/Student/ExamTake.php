<?php

namespace App\Livewire\Student;

use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\Question;
use App\Models\Mark;
use Livewire\Component;

class ExamTake extends Component
{
    public $exam;
    public $currentQuestionIndex = 0;
    public $questions = [];
    public $selectedOption = null;
    public $timeRemaining = 0;
    public $examCompleted = false;

    public function mount($exam = null)
    {
        if ($exam) {
            $this->exam = Exam::findOrFail($exam);
        }

        if ($this->exam->user_id !== auth()->id()) {
            abort(403);
        }

        if ($this->exam->status === 'completed') {
            $this->examCompleted = true;
            return;
        }

        // Start the exam
        if ($this->exam->status === 'pending') {
            $this->exam->update([
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }

        // Get questions for this exam
        $round = $this->exam->round;
        $query = Question::where('subject_id', $this->exam->subject_id);

        // For final round, assign unique questions
        if ($round->is_final) {
            $assignedQuestionIds = \DB::table('question_round')
                ->where('round_id', $round->id)
                ->whereNotNull('assigned_to')
                ->where('assigned_to', '!=', auth()->id())
                ->pluck('question_id');

            $query->whereNotIn('id', $assignedQuestionIds);
        }

        $this->questions = $query->with('options')->get()->toArray();

        // Create exam answers for unviewed questions
        foreach ($this->questions as $q) {
            ExamAnswer::firstOrCreate(
                ['exam_id' => $this->exam->id, 'question_id' => $q['id']],
                ['is_viewed' => false, 'is_correct' => false]
            );
        }

        // Set timer for current question
        if (count($this->questions) > 0) {
            $this->timeRemaining = $this->questions[$this->currentQuestionIndex]['time_limit'] ?? 30;
        }
    }

    public function markViewed()
    {
        if (isset($this->questions[$this->currentQuestionIndex])) {
            ExamAnswer::where('exam_id', $this->exam->id)
                ->where('question_id', $this->questions[$this->currentQuestionIndex]['id'])
                ->update(['is_viewed' => true]);
        }
    }

    public function selectOption($optionId)
    {
        $this->selectedOption = $optionId;
    }

    public function submitAnswer()
    {
        if (!isset($this->questions[$this->currentQuestionIndex]))
            return;

        $question = $this->questions[$this->currentQuestionIndex];
        $isCorrect = false;

        if ($this->selectedOption) {
            $correctOption = collect($question['options'])->firstWhere('is_correct', true);
            $isCorrect = $correctOption && $correctOption['id'] == $this->selectedOption;
        }

        ExamAnswer::where('exam_id', $this->exam->id)
            ->where('question_id', $question['id'])
            ->update([
                'selected_option_id' => $this->selectedOption,
                'is_correct' => $isCorrect,
                'answered_at' => now(),
                'is_viewed' => true,
            ]);

        $this->selectedOption = null;

        // Move to next question or finish
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
            $this->timeRemaining = $this->questions[$this->currentQuestionIndex]['time_limit'] ?? 30;
            $this->markViewed();
        } else {
            $this->completeExam();
        }
    }

    public function timeUp()
    {
        $this->submitAnswer();
    }

    public function completeExam()
    {
        $correctCount = ExamAnswer::where('exam_id', $this->exam->id)
            ->where('is_correct', true)
            ->count();

        $totalPoints = 0;
        $correctAnswers = ExamAnswer::where('exam_id', $this->exam->id)
            ->where('is_correct', true)
            ->with('question')
            ->get();

        foreach ($correctAnswers as $answer) {
            $totalPoints += $answer->question->points ?? 1;
        }

        $this->exam->update([
            'status' => 'completed',
            'completed_at' => now(),
            'total_score' => $totalPoints,
        ]);

        // Update online marks
        Mark::updateOrCreate(
            [
                'user_id' => $this->exam->user_id,
                'round_id' => $this->exam->round_id,
                'subject_id' => $this->exam->subject_id,
            ],
            [
                'online_marks' => $totalPoints,
            ]
        );

        // Recalculate total
        $mark = Mark::where('user_id', $this->exam->user_id)
            ->where('round_id', $this->exam->round_id)
            ->where('subject_id', $this->exam->subject_id)
            ->first();

        if ($mark) {
            $mark->total_marks = $mark->online_marks + $mark->manual_marks;
            $mark->save();
        }

        $this->examCompleted = true;
    }

    public function render()
    {
        return view('livewire.student.exam-take', [
            'currentQuestion' => $this->questions[$this->currentQuestionIndex] ?? null,
            'totalQuestions' => count($this->questions),
            'answeredCount' => ExamAnswer::where('exam_id', $this->exam->id)->whereNotNull('selected_option_id')->count(),
        ]);
    }
}
