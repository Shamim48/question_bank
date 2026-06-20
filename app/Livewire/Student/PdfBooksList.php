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

        $query = PdfBook::with(['round', 'group']);

        if ($user->group) {
            // Case-insensitive match on group name
            $groupMatch = \App\Models\Group::whereRaw('LOWER(name) = ?', [strtolower(trim($user->group))])->first();

            if ($groupMatch) {
                $query->where('group_id', $groupMatch->id);
            }
            // If no matching group found, show no books (student is in an unrecognized group)
            // rather than showing all books
            else {
                $query->whereRaw('1 = 0');
            }
        }

        return view('livewire.student.pdf-books-list', [
            'books' => $query->latest()->get(),
            'studentGroup' => $user->group,
        ]);
    }
}
