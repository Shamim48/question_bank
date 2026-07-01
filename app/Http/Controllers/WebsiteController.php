<?php

namespace App\Http\Controllers;

use App\Models\ClassLevel;
use App\Models\District;
use App\Models\Division;
use App\Models\Group;
use App\Models\Season;
use App\Models\Student;
use App\Models\Thana;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('website.auth.index');
    }

    public function participantRegistrationForm()
    {
        $data['seasons'] = Season::where('status', 1)->get();
        $data['groups'] = Group::all();
        $data['classes'] = ClassLevel::where('status', 1)->get();

        return view('website.auth.student', $data);
    }

    public function getDistrict($division_id)
    {
        return District::where('division_id', $division_id)->get(['id', 'name']);
    }

    public function getThana($district_id)
    {
        return Thana::where('district_id', $district_id)->get(['id', 'name']);
    }

    public function studentRegister(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'nullable|string|max:255',
            'email'          => 'nullable|email|unique:users,email',
            'phone'          => ['nullable', 'regex:/^(?:\+880|880|0)1[3-9]\d{8}$/'],
            'season_id'      => 'nullable|exists:seasons,id',
            'class_id'       => 'nullable|exists:class_levels,id',
            'group_id'       => 'nullable|exists:groups,id',
            'institute_name' => 'nullable|string|max:255',
            'known_from'     => 'nullable|string|max:100',
            'password'       => ['required', 'confirmed', Password::min(6)],
        ]);

        try {
            DB::beginTransaction();

            $name = trim($request->first_name . ' ' . $request->last_name);

            $user = User::create([
                'name'     => $name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'class'    => ClassLevel::find($request->class_id)?->name,
                'group'    => Group::find($request->group_id)?->name,
                'password' => Hash::make($request->password),
                'role'     => 'student',
            ]);

            Student::create([
                'user_id'        => $user->id,
                'student_id'     => Student::generateStudentId(),
                'name'           => $name,
                'email'          => $request->email,
                'phone'          => $request->phone,
                'known_from'     => $request->known_from,
                'season_id'      => $request->season_id,
                'class_id'       => $request->class_id,
                'group_id'       => $request->group_id,
                'institute_name' => $request->institute_name,
            ]);

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Registration successful! You can now login.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Registration failed! Please try again.');
        }
    }

    public function teamRegistrationForm()
    {
        $roles     = ['Coordinator', 'Mentor', 'Volunteer', 'Ambassador', 'Moderator'];
        $seasons   = Season::where('status', 1)->get();
        $divisions = Division::all();
        $districts = District::all();
        $upazillas = Thana::all();

        return view('website.auth.team', compact('roles', 'seasons', 'divisions', 'districts', 'upazillas'));
    }

    public function teamRegister(Request $request)
    {
        $request->validate([
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'nullable|string|max:255',
            'image'            => 'required|image|max:2048',
            'email'            => 'required|email|unique:users,email',
            'phone'            => ['nullable', 'regex:/^(?:\+880|880|0)1[3-9]\d{8}$/'],
            'whatsapp'         => ['nullable', 'regex:/^(?:\+880|880|0)1[3-9]\d{8}$/'],
            'institute_mobile' => ['nullable', 'regex:/^(?:\+880|880|0)1[3-9]\d{8}$/'],
            'season_id'        => 'required',
            'password'         => ['required', 'confirmed', Password::min(6)],
            'role'             => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('uploads/team', 'public');
            }

            $name = trim($request->first_name . ' ' . $request->last_name);

            $user = User::create([
                'name'     => $name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
            ]);

            // Store extra team member info in student table (repurposed for pending team)
            Student::create([
                'user_id'        => $user->id,
                'student_id'     => 'TEAM' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT),
                'institute_name' => $request->institute_name,
                'division_id'    => $request->division_id,
                'district_id'    => $request->district_id,
                'upazilla_id'    => $request->upazilla_id,
                'address'        => $request->address,
                'status'         => 0,
            ]);

            DB::commit();

            return redirect()->route('user.login')
                ->with('success', 'Registration successful! Your account is pending approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Registration Failed! Please try again.');
        }
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->afterLogin();
        }

        return view('website.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required',
            'password' => 'required',
        ]);

        $input    = $request->login;
        $password = $request->password;
        $remember = $request->boolean('remember');

        $user = null;

        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $input)->first();
        }

        if (!$user) {
            $student = Student::where('student_id', $input)->first();
            $user    = $student ? User::find($student->user_id) : null;
        }

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $remember);
            $request->session()->regenerate();

            return $this->afterLogin();
        }

        return back()->withErrors([
            'login' => 'Invalid email / student ID or password.',
        ])->withInput();
    }

    private function afterLogin()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('student.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login')
            ->with('success', 'You have been logged out successfully.');
    }
}
