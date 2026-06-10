<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\PdfBook;
use Illuminate\Support\Facades\Auth;

class PdfBooksList extends Component
{
    public function render()
    {
        $user = Auth::user();
        // Determine group id: try direct match by name, or null for global
        $groupId = null;
        if (!empty($user->group)) {
            $group = \App\Models\Group::where('name', $user->group)->first();
            $groupId = $group ? $group->id : null;
        }

        // Try to infer a relevant round for the student (e.g., from their latest exam)
        $roundId = null;
        if (method_exists($user, 'exams')) {
            $lastExam = $user->exams()->latest('created_at')->first();
            if ($lastExam && isset($lastExam->round_id)) {
                $roundId = $lastExam->round_id;
            }
        }

        $query = PdfBook::with(['round', 'group']);

        // Show books that are either global (no group) or match the student's group
        $query->where(function ($q) use ($groupId) {
            if ($groupId) {
                $q->where('group_id', $groupId)->orWhereNull('group_id');
            } else {
                $q->whereNull('group_id');
            }
        });

        // If we inferred a round, show round-specific or global books
        if ($roundId) {
            $query->where(function ($q) use ($roundId) {
                $q->where('round_id', $roundId)->orWhereNull('round_id');
            });
        }

        $books = $query->latest()->get();

        return view('livewire.student.pdf-books-list', compact('books'));
    }
}
