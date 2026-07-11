<?php

namespace App\Support;

use App\Models\Setting;
use App\Models\User;

class LoginGuard
{
    /**
     * Returns an error message if the user should be blocked from logging in
     * (or from staying logged in), or null if login is allowed.
     */
    public static function check(User $user): ?string
    {
        if ($user->isAdmin()) {
            return null;
        }

        if ((bool) Setting::get('login_locked', false)) {
            return 'Login is temporarily disabled. Please try again later.';
        }

        if ($user->isStudent() && $user->student && (int) $user->student->status === 0) {
            return 'Your account has been disabled. Please contact support.';
        }

        if ($user->isTeam() && !$user->team?->isApproved()) {
            return 'Your account is pending admin approval. Please wait.';
        }

        return null;
    }
}
