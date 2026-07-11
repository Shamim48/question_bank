<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function image(Student $student)
    {
        abort_unless($student->banner_path && Storage::disk('public')->exists($student->banner_path), 404);

        return response()->file(Storage::disk('public')->path($student->banner_path));
    }

    public function share(Student $student)
    {
        abort_unless($student->banner_path, 404);

        return view('website.banner-share', compact('student'));
    }
}
