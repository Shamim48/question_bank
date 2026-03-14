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
        
        // Find books that match the student's group, or we might need to match round as well
        // Assuming the student has a group (from the string field or related ID)
        // Adjust the matching logic depending on how student group/round is stored.
        // For now, based on instructions: "Students can download PDF books according to their Student Group and Round"
        
        // Since User model has 'group' and possibly 'class' strings, and PdfBook has group_id and round_id
        // We need to match the user's string group against the Group model, or 
        // fetch all books if the student's exact matching is complex without a direct foreign key.
        // Let's try to match by Group Name if $user->group is a string name:
        
        $groupMatch = \App\Models\Group::where('name', $user->group)->first();
        $groupId = $groupMatch ? $groupMatch->id : null;

        // If 'round' is also tied to the student, we filter by that. If not, maybe they see all rounds for their group.
        $query = PdfBook::with(['round', 'group']);
        
        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        return view('livewire.student.pdf-books-list', [
            'books' => $query->latest()->get()
        ]);
    }
}
