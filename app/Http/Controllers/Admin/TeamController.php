<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Team;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with('user', 'season', 'division', 'district')
            ->latest()
            ->get()
            ->groupBy('status');

        $pending  = $teams->get(0, collect());
        $approved = $teams->get(1, collect());
        $rejected = $teams->get(2, collect());

        return view('admin.teams.index', compact('pending', 'approved', 'rejected'));
    }

    public function approve(Team $team)
    {
        $team->update(['status' => 1]);

        return back()->with('success', "{$team->user->name} approved. They will inherit permissions from the \"{$team->role}\" role.");
    }

    public function reject(Team $team)
    {
        $team->update(['status' => 2]);

        return back()->with('success', "{$team->user->name} has been rejected.");
    }
}
