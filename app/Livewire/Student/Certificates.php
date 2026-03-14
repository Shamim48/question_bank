<?php

namespace App\Livewire\Student;

use App\Models\Certificate;
use Livewire\Component;

class Certificates extends Component
{
    public function render()
    {
        return view('livewire.student.certificates', [
            'certificates' => Certificate::where('user_id', auth()->id())->with(['round', 'group'])->latest()->get(),
        ]);
    }
}
