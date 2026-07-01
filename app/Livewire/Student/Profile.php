<?php

namespace App\Livewire\Student;

use App\Models\ClassLevel;
use App\Models\District;
use App\Models\Division;
use App\Models\Group;
use App\Models\Thana;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    // Section edit flags
    public bool $editingPersonal  = false;
    public bool $editingInstitute = false;
    public bool $editingAddress   = false;
    public bool $editingFamily    = false;
    public bool $editingExtra     = false;
    public bool $editingPhoto     = false;
    public bool $changingPassword = false;

    // Personal
    public string $name      = '';
    public string $phone     = '';
    public string $gender    = '';
    public string $dob       = '';
    public string $nid_birth = '';

    // Institute
    public string $institute_name   = '';
    public string $institute_email  = '';
    public string $institute_mobile = '';
    public string $board            = '';
    public $class_id = null;
    public $group_id = null;

    // Address
    public $division_id = null;
    public $district_id = null;
    public $thana_id    = null;
    public string $address = '';

    // Family
    public string $father_name    = '';
    public string $mother_name    = '';
    public string $guardian_phone = '';

    // Extra
    public string $known_from      = '';
    public string $hobby           = '';
    public string $aim_in_life     = '';
    public string $favourite_quote = '';
    public string $idol            = '';

    // Photo
    public $photo = null;

    // Password
    public string $currentPassword         = '';
    public string $newPassword             = '';
    public string $newPasswordConfirmation = '';

    // Messages
    public string $personalMessage  = '';
    public string $instituteMessage = '';
    public string $addressMessage   = '';
    public string $familyMessage    = '';
    public string $extraMessage     = '';
    public string $photoMessage     = '';
    public string $passwordMessage  = '';

    public function mount()
    {
        $user    = Auth::user();
        $student = $user->student;

        $this->name  = $user->name;
        $this->phone = $user->phone ?? '';

        if ($student) {
            $this->gender           = $student->gender ?? '';
            $this->dob              = $student->dob ?? '';
            $this->nid_birth        = $student->nid_birth ?? '';
            $this->institute_name   = $student->institute_name ?? '';
            $this->institute_email  = $student->institute_email ?? '';
            $this->institute_mobile = $student->institute_mobile ?? '';
            $this->board            = $student->board ?? '';
            $this->class_id         = $student->class_id;
            $this->group_id         = $student->group_id;
            $this->division_id      = $student->division_id;
            $this->district_id      = $student->district_id;
            $this->thana_id         = $student->thana_id;
            $this->address          = $student->address ?? '';
            $this->father_name      = $student->father_name ?? '';
            $this->mother_name      = $student->mother_name ?? '';
            $this->guardian_phone   = $student->guardian_phone ?? '';
            $this->known_from       = $student->known_from ?? '';
            $this->hobby            = $student->hobby ?? '';
            $this->aim_in_life      = $student->aim_in_life ?? '';
            $this->favourite_quote  = $student->favourite_quote ?? '';
            $this->idol             = $student->idol ?? '';
        }
    }

    public function updatedDivisionId()
    {
        $this->district_id = null;
        $this->thana_id    = null;
    }

    public function updatedDistrictId()
    {
        $this->thana_id = null;
    }

    public function savePersonal()
    {
        $this->validate([
            'name'      => ['required', 'string', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'gender'    => ['nullable', 'in:male,female,others'],
            'dob'       => ['nullable', 'date'],
            'nid_birth' => ['nullable', 'string', 'max:50'],
        ]);

        $user = Auth::user();
        $user->update([
            'name'  => trim($this->name),
            'phone' => trim($this->phone) ?: null,
        ]);

        $user->student?->update([
            'name'      => trim($this->name),
            'phone'     => trim($this->phone) ?: null,
            'gender'    => $this->gender ?: null,
            'dob'       => $this->dob ?: null,
            'nid_birth' => $this->nid_birth ?: null,
        ]);

        $this->editingPersonal = false;
        $this->personalMessage = 'Personal information updated successfully.';
    }

    public function saveInstitute()
    {
        $this->validate([
            'institute_name'   => ['nullable', 'string', 'max:255'],
            'institute_email'  => ['nullable', 'email', 'max:100'],
            'institute_mobile' => ['nullable', 'string', 'max:20'],
            'board'            => ['nullable', 'string', 'max:100'],
            'class_id'         => ['nullable', 'exists:class_levels,id'],
            'group_id'         => ['nullable', 'exists:groups,id'],
        ]);

        $user    = Auth::user();
        $student = $user->student;

        $student?->update([
            'institute_name'   => $this->institute_name ?: null,
            'institute_email'  => $this->institute_email ?: null,
            'institute_mobile' => $this->institute_mobile ?: null,
            'board'            => $this->board ?: null,
            'class_id'         => $this->class_id ?: null,
            'group_id'         => $this->group_id ?: null,
        ]);

        $user->update([
            'class' => $this->class_id ? ClassLevel::find($this->class_id)?->name : null,
            'group' => $this->group_id ? Group::find($this->group_id)?->description : null,
        ]);

        $this->editingInstitute = false;
        $this->instituteMessage = 'Institute information updated successfully.';
    }

    public function saveAddress()
    {
        $this->validate([
            'division_id' => ['nullable', 'exists:divisions,id'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'thana_id'    => ['nullable', 'exists:thanas,id'],
            'address'     => ['nullable', 'string', 'max:500'],
        ]);

        Auth::user()->student?->update([
            'division_id' => $this->division_id ?: null,
            'district_id' => $this->district_id ?: null,
            'thana_id'    => $this->thana_id ?: null,
            'address'     => $this->address ?: null,
        ]);

        $this->editingAddress = false;
        $this->addressMessage = 'Address updated successfully.';
    }

    public function saveFamily()
    {
        $this->validate([
            'father_name'    => ['nullable', 'string', 'max:255'],
            'mother_name'    => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:20'],
        ]);

        Auth::user()->student?->update([
            'father_name'    => $this->father_name ?: null,
            'mother_name'    => $this->mother_name ?: null,
            'guardian_phone' => $this->guardian_phone ?: null,
        ]);

        $this->editingFamily = false;
        $this->familyMessage = 'Family information updated successfully.';
    }

    public function saveExtra()
    {
        $this->validate([
            'known_from'     => ['nullable', 'in:Facebook,Mentor,Ambassador'],
            'hobby'          => ['nullable', 'in:Reading,Sports,Music,Travel,Art'],
            'aim_in_life'    => ['nullable', 'in:Engineer,Doctor,Teacher,Entrepreneur,Other'],
            'favourite_quote'=> ['nullable', 'string', 'max:255'],
            'idol'           => ['nullable', 'string', 'max:100'],
        ]);

        Auth::user()->student?->update([
            'known_from'      => $this->known_from ?: null,
            'hobby'           => $this->hobby ?: null,
            'aim_in_life'     => $this->aim_in_life ?: null,
            'favourite_quote' => $this->favourite_quote ?: null,
            'idol'            => $this->idol ?: null,
        ]);

        $this->editingExtra = false;
        $this->extraMessage = 'Additional information updated successfully.';
    }

    public function savePhoto()
    {
        $this->validate([
            'photo' => ['required', 'image', 'max:2048'],
        ]);

        $path = $this->photo->store('uploads/students', 'public');

        Auth::user()->student?->update(['image' => $path]);

        $this->photo        = null;
        $this->editingPhoto = false;
        $this->photoMessage = 'Profile photo updated successfully.';
    }

    public function savePassword()
    {
        $this->validate([
            'currentPassword'         => ['required', 'string'],
            'newPassword'             => ['required', 'confirmed', Password::min(6)],
            'newPasswordConfirmation' => ['required'],
        ]);

        if (!Hash::check($this->currentPassword, Auth::user()->password)) {
            $this->addError('currentPassword', 'Current password is incorrect.');
            return;
        }

        Auth::user()->update(['password' => Hash::make($this->newPassword)]);

        $this->currentPassword         = '';
        $this->newPassword             = '';
        $this->newPasswordConfirmation = '';
        $this->changingPassword        = false;
        $this->passwordMessage         = 'Password changed successfully.';
    }

    public function render()
    {
        $user    = Auth::user();
        $student = $user->student;

        return view('livewire.student.profile', [
            'user'      => $user,
            'student'   => $student,
            'divisions' => Division::all(),
            'districts' => $this->division_id ? District::where('division_id', $this->division_id)->get() : collect(),
            'thanas'    => $this->district_id ? Thana::where('district_id', $this->district_id)->get() : collect(),
            'classes'   => ClassLevel::where('status', 1)->get(),
            'groups'    => Group::all(),
        ]);
    }
}
