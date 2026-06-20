<?php

use App\Http\Controllers\CertificateController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/run-migrate-seed', function () {
    Artisan::call('migrate', ['--force' => true]);
    return "Migrations ran successfully! You can now use the new features.";
});

// Public
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
});

// Ambassador search (public)
Route::get('/ambassadors', function () {
    return view('ambassadors');
})->name('ambassadors');

// Fallback dashboard route (Breeze expects this)
Route::middleware(['auth'])->get('/dashboard', function () {
    return auth()->user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('student.dashboard');
})->name('dashboard');

// Logout route
Route::post('/logout', function () {
    auth()->guard('web')->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Auth routes (provided by Breeze)
require __DIR__ . '/auth.php';

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/rounds', function () {
        return view('admin.rounds');
    })->name('admin.rounds');

    Route::get('/subjects', function () {
        return view('admin.subjects');
    })->name('admin.subjects');

    Route::get('/groups', function () {
        return view('admin.groups');
    })->name('admin.groups');

    Route::get('/questions', function () {
        return view('admin.questions');
    })->name('admin.questions');

    Route::get('/exams', function () {
        return view('admin.exams');
    })->name('admin.exams');

    Route::get('/marks', function () {
        return view('admin.marks');
    })->name('admin.marks');

    Route::get('/offline-marks', function () {
        return view('admin.offline-marks');
    })->name('admin.offline-marks');

    Route::get('/certificates', function () {
        return view('admin.certificates');
    })->name('admin.certificates');

    Route::get('/pdf-books', function () {
        return view('admin.pdf-books');
    })->name('admin.pdf-books');

    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('admin.profile');
});

// Student routes
Route::middleware(['auth', 'student'])->prefix('student')->group(function () {
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');

    Route::get('/exams', function () {
        return view('student.exams');
    })->name('student.exams');

    Route::get('/exams/{exam}', function () {
        return view('student.exam-take');
    })->name('student.exam.take');

    Route::get('/results', function () {
        return view('student.results');
    })->name('student.results');

    Route::get('/certificates', function () {
        return view('student.certificates');
    })->name('student.certificates');

    Route::get('/pdf-books', function () {
        return view('student.pdf-books');
    })->name('student.pdf-books');

    Route::get('/profile', function () {
        return view('student.profile');
    })->name('student.profile');
});

// Certificate download (auth required)
Route::middleware(['auth'])->group(function () {
    Route::get('/certificate/{certificate}/download', [CertificateController::class, 'download'])
        ->name('certificate.download');
});
