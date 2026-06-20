<?php

namespace App\Livewire\Student;

use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\Question;
use App\Models\Mark;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ExamTake extends Component
{
    public $exam;
    public $currentQuestionIndex = 0;
    public $questions = [];
    public $selectedOption = null;
    public $timeRemaining = 0;
    public $examCompleted = false;
    public $reviewAnswers = [];

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
            $this->loadReviewAnswers();
            return;
        }

        $isResume = $this->exam->status === 'in_progress';

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
            $assignedQuestionIds = DB::table('question_round')
                ->where('round_id', $round->id)
                ->whereNotNull('assigned_to')
                ->where('assigned_to', '!=', auth()->id())
                ->pluck('question_id');

            $query->whereNotIn('id', $assignedQuestionIds);
        }

        $this->questions = $query->with('options')->get()->toArray();

        // Create exam answers for all questions
        foreach ($this->questions as $q) {
            ExamAnswer::firstOrCreate(
                ['exam_id' => $this->exam->id, 'question_id' => $q['id']],
                ['is_viewed' => false, 'is_correct' => false]
            );
        }

        if (count($this->questions) === 0) return;

        if ($isResume) {
            // Resuming after page refresh: find the first unanswered question
            $answeredIds = ExamAnswer::where('exam_id', $this->exam->id)
                ->whereNotNull('selected_option_id')
                ->where('answered_at', '!=', null)
                ->pluck('question_id');

            $this->currentQuestionIndex = 0;
            foreach ($this->questions as $index => $q) {
                if (!$answeredIds->contains($q['id'])) {
                    $this->currentQuestionIndex = $index;
                    break;
                }
            }

            $currentQ = $this->questions[$this->currentQuestionIndex];
            $timeLimit = $currentQ['time_limit'] ?? 30;

            $currentAnswer = ExamAnswer::where('exam_id', $this->exam->id)
                ->where('question_id', $currentQ['id'])
                ->first();

            if ($currentAnswer && $currentAnswer->is_viewed) {
                // Restore timer from when question was first shown (updated_at set by markViewed)
                $elapsed = (int) now()->diffInSeconds($currentAnswer->updated_at);
                $this->timeRemaining = max(1, $timeLimit - $elapsed);

                // Restore any auto-saved option selection
                if ($currentAnswer->selected_option_id) {
                    $this->selectedOption = $currentAnswer->selected_option_id;
                }
            } else {
                $this->timeRemaining = $timeLimit;
                $this->markViewed();
            }
        } else {
            // Fresh exam start
            $this->timeRemaining = $this->questions[0]['time_limit'] ?? 30;
            $this->markViewed();
        }
    }

    public function markViewed()
    {
        if (isset($this->questions[$this->currentQuestionIndex])) {
            // updated_at here serves as "question start time" for timer persistence
            ExamAnswer::where('exam_id', $this->exam->id)
                ->where('question_id', $this->questions[$this->currentQuestionIndex]['id'])
                ->update(['is_viewed' => true]);
        }
    }

    public function selectOption($optionId)
    {
        $this->selectedOption = $optionId;

        // Auto-save without updating timestamps (updated_at is used as the question start time)
        if (isset($this->questions[$this->currentQuestionIndex])) {
            DB::table('exam_answers')
                ->where('exam_id', $this->exam->id)
                ->where('question_id', $this->questions[$this->currentQuestionIndex]['id'])
                ->update(['selected_option_id' => $optionId]);
        }
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
        $this->loadReviewAnswers();
    }

    private function loadReviewAnswers()
    {
        $this->reviewAnswers = ExamAnswer::where('exam_id', $this->exam->id)
            ->with(['question.options', 'selectedOption'])
            ->get()
            ->map(function ($answer) {
                $correctOption = $answer->question->options->firstWhere('is_correct', true);
                return [
                    'question_content' => $answer->question->content,
                    'selected_option_text' => $answer->selectedOption?->option_text ?? null,
                    'correct_option_text' => $correctOption?->option_text ?? null,
                    'is_correct' => (bool) $answer->is_correct,
                    'answered' => !is_null($answer->selected_option_id),
                ];
            })
            ->toArray();
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
