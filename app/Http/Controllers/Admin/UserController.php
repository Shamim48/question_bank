<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use App\Models\Role;
use App\Models\Season;
use App\Models\Team;
use App\Models\Thana;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
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
                'name'          => $request->name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'password'      => Hash::make($request->password),
                'role'          => $request->role,
                'referral_code' => User::generateReferralCode($request->name),
            ]);

            Team::create([
                'user_id' => $user->id,
                'role'    => $request->role,
                'status'  => 1,
            ]);
        });

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::where('status', 1)->orWhere('name', $user->role)->get();
        $team  = $user->team;

        $divisions = Division::orderBy('name')->get();
        $districts = $team?->division_id ? District::where('division_id', $team->division_id)->orderBy('name')->get() : collect();
        $thanas    = $team?->district_id ? Thana::where('district_id', $team->district_id)->orderBy('name')->get() : collect();
        $seasons   = Season::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles', 'team', 'divisions', 'districts', 'thanas', 'seasons'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $user->id,
            'phone'             => ['nullable', 'regex:/^(?:\+880|880|0)1[3-9]\d{8}$/'],
            'role'              => 'required|exists:roles,name',
            'password'          => ['nullable', 'confirmed', Password::min(6)],
            'image'             => 'nullable|image|max:2048',
            'whatsapp'          => 'nullable|string|max:20',
            'telegram'          => 'nullable|string|max:20',
            'institute_name'    => 'nullable|string|max:255',
            'designation'       => 'nullable|string|max:255',
            'department'        => 'nullable|string|max:255',
            'institute_mobile'  => 'nullable|string|max:20',
            'institute_email'   => 'nullable|email|max:255',
            'eiin_no'           => 'nullable|string|max:50',
            'season_id'         => 'nullable|exists:seasons,id',
            'division_id'       => 'nullable|exists:divisions,id',
            'district_id'       => 'nullable|exists:districts,id',
            'thana_id'          => 'nullable|exists:thanas,id',
            'address'           => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $user) {
            $data = [
                'name'  => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role'  => $request->role,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            $teamData = [
                'role'             => $request->role,
                'whatsapp'         => $request->whatsapp,
                'telegram'         => $request->telegram,
                'institute_name'   => $request->institute_name,
                'designation'      => $request->designation,
                'department'       => $request->department,
                'institute_mobile' => $request->institute_mobile,
                'institute_email'  => $request->institute_email,
                'eiin_no'          => $request->eiin_no,
                'season_id'        => $request->season_id ?: null,
                'division_id'      => $request->division_id ?: null,
                'district_id'      => $request->district_id ?: null,
                'thana_id'         => $request->thana_id ?: null,
                'address'          => $request->address,
            ];

            if ($request->hasFile('image')) {
                $teamData['image'] = $request->file('image')->store('uploads/team', 'public');
            }

            $user->team()->updateOrCreate([], $teamData);
        });

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->role === 'student') {
            return back()->with('error', 'Student accounts cannot be deleted from here.');
        }

        $user->team?->delete();
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
