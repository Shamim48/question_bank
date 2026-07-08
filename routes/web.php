<?php

use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/run-migrate-seed', function () {
    Artisan::call('migrate', ['--force' => true]);
    return "Migrations ran successfully! You can now use the new features.";
});

// Route::get('storage-link', function () {
//     Artisan::call('storage:link');
//     return 'storage link created successfully!';
// });

// Public
// Route::get('/', function () {
//     if (auth()->check()) {
//         return auth()->user()->isAdmin()
//             ? redirect()->route('admin.dashboard')
//             : redirect()->route('student.dashboard');
//     }
//     return redirect()->route('login');
// });

Route::get('/', function () {
    return redirect('/registration');
});

// User Auth (Website)
Route::get('/user/login', [WebsiteController::class, 'showLogin'])->name('user.login');
Route::post('/user/login', [WebsiteController::class, 'login'])->name('login.submit');
Route::post('/user/logout', [WebsiteController::class, 'logout'])->name('user.logout');

Route::get('registration', [WebsiteController::class, 'index'])->name('registration.index');
Route::get('registration/student', [WebsiteController::class, 'participantRegistrationForm'])->name('registration.participant');
Route::post('registration/student', [WebsiteController::class, 'studentRegister'])->name('registration.participant.submit');

Route::get('registration/core/team', [WebsiteController::class, 'teamRegistrationForm'])->name('registration.core.team');
Route::post('registration/team', [WebsiteController::class, 'teamRegister'])->name('registration.team.submit');

Route::get('/get-districts/{division}', [WebsiteController::class, 'getDistrict']);
Route::get('/get-thanas/{district}', [WebsiteController::class, 'getThana']);

// Ambassador search (public)
Route::get('/ambassadors', function () {
    return view('ambassadors');
})->name('ambassadors');

// Events (public)
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// Fallback dashboard route (Breeze expects this)
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();
    return ($user->isAdmin() || $user->isTeam())
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

// Admin-only routes (full admin access required)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/users/pending', [TeamController::class, 'pending'])->name('admin.users.pending');
    Route::post('/users/{team}/approve', [TeamController::class, 'approve'])->name('admin.users.approve');
    Route::get('/users/{team}/reject', [TeamController::class, 'reject'])->name('admin.users.reject');

    Route::get('/roles', function () {
        return view('admin.roles');
    })->name('admin.roles');
});

// Admin OR approved team member routes
Route::middleware(['auth', 'team.or.admin'])->prefix('admin')->group(function () {
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

    Route::get('/class-levels', function () {
        return view('admin.class-levels');
    })->name('admin.class-levels');

    Route::get('/events', function () {
        return view('admin.events');
    })->name('admin.events');

    Route::get('/participants', function () {
        return view('admin.participants');
    })->name('admin.participants');

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

    // Seasons CRUD
    Route::resource('seasons', SeasonController::class)->names([
        'index'   => 'admin.seasons.index',
        'create'  => 'admin.seasons.create',
        'store'   => 'admin.seasons.store',
        'edit'    => 'admin.seasons.edit',
        'update'  => 'admin.seasons.update',
        'destroy' => 'admin.seasons.destroy',
    ]);
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
