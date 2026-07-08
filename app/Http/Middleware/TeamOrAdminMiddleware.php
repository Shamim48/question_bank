<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamOrAdminMiddleware
{
    // Maps Laravel route names to module-action permission names
    private array $routePermissionMap = [
        'admin.dashboard'       => 'dashboard-access',
        'admin.profile'         => 'profile-access',
        'admin.rounds'          => 'rounds-list',
        'admin.subjects'        => 'subjects-list',
        'admin.groups'          => 'groups-list',
        'admin.class-levels'    => 'class-levels-list',
        'admin.participants'    => 'participants-list',
        'admin.questions'       => 'questions-list',
        'admin.exams'           => 'exams-list',
        'admin.marks'           => 'marks-list',
        'admin.offline-marks'   => 'offline-marks-list',
        'admin.certificates'    => 'certificates-list',
        'admin.pdf-books'       => 'pdf-books-list',
        'ambassadors'           => 'ambassadors-list',
        'admin.seasons.index'   => 'seasons-list',
        'admin.seasons.show'    => 'seasons-list',
        'admin.seasons.create'  => 'seasons-list',
        'admin.seasons.edit'    => 'seasons-list',
        'admin.seasons.store'   => 'seasons-list',
        'admin.seasons.update'  => 'seasons-list',
        'admin.seasons.destroy' => 'seasons-list',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->isAdmin()) {
            return $next($request);
        }

        if ($user->isTeam()) {
            $team = $user->team;

            if (!$team || !$team->isApproved()) {
                abort(403, 'Your account is pending approval. Please wait for admin to approve your account.');
            }

            $routeName  = $request->route()?->getName();
            $permission = $routeName ? ($this->routePermissionMap[$routeName] ?? null) : null;

            if ($permission && $user->hasPermission($permission)) {
                return $next($request);
            }

            abort(403, 'You do not have permission to access this page.');
        }

        abort(403, 'Access denied.');
    }
}
