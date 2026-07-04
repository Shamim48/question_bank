<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'student')
            ->where(function ($query) {
                $query->where('role', 'admin')
                    ->orWhereHas('team', fn ($t) => $t->where('status', 1));
            })
            ->with('team')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::teamRoles();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => ['nullable', 'regex:/^(?:\+880|880|0)1[3-9]\d{8}$/'],
            'role'     => 'required|exists:roles,name',
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
            ]);

            Team::create([
                'user_id' => $user->id,
                'role'    => $request->role,
                'status'  => 1,
            ]);
        });

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }
}
