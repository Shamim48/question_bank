<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Division;
use App\Models\Student;
use App\Models\Thana;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('website.auth.index');
    }

    public function participantRegistrationForm()
    {
        $divisions = Division::all();
        $districts = District::all();
        $upazillas = Thana::all();

        return view('website.auth.student', compact('divisions', 'districts', 'upazillas'));
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
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'phone'          => ['nullable', 'regex:/^(?:\+880|880|0)1[3-9]\d{8}$/'],
            'password'       => ['required', 'confirmed', Password::min(6)],
            'class'          => 'nullable|string|max:100',
            'group'          => 'nullable|string|max:100',
            'gender'         => 'nullable|string',
            'date_of_birth'  => 'nullable|date',
            'institute_name' => 'nullable|string|max:255',
            'known_from'     => 'nullable|string|max:100',
            'division_id'    => 'nullable|exists:divisions,id',
            'district_id'    => 'nullable|exists:districts,id',
            'upazilla_id'    => 'nullable|exists:thanas,id',
            'address'        => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
                'role'     => 'student',
                'class'    => $request->class,
                'group'    => $request->group,
            ]);

            Student::create([
                'user_id'        => $user->id,
                'student_id'     => Student::generateStudentId(),
                'known_from'     => $request->known_from,
                'institute_name' => $request->institute_name,
                'gender'         => $request->gender,
                'date_of_birth'  => $request->date_of_birth,
                'division_id'    => $request->division_id,
                'district_id'    => $request->district_id,
                'upazilla_id'    => $request->upazilla_id,
                'address'        => $request->address,
            ]);

            DB::commit();

            return redirect()->route('user.login')
                ->with('success', 'Registration successful! You can now login.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Registration failed! Please try again.');
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
