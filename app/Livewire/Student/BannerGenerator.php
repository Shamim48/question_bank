<?php

namespace App\Livewire\Student;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class BannerGenerator extends Component
{
    public function generate()
    {
        $student = auth()->user()->student;

        if (!$student) {
            session()->flash('error', 'No student profile found.');
            return;
        }

        app(\App\Services\BannerGenerator::class)->generate($student);
        session()->flash('success', 'Banner generated!');
    }

    public function render()
    {
        $student = auth()->user()->student;

        return view('livewire.student.banner-generator', [
            'student'   => $student,
            'bannerUrl' => $student?->banner_path ? Storage::url($student->banner_path) : null,
            'shareUrl'  => $student ? route('banner.share', $student) : null,
        ]);
    }
}
