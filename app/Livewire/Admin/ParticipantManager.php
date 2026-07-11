<?php

namespace App\Livewire\Admin;

use App\Models\ClassLevel;
use App\Models\District;
use App\Models\Division;
use App\Models\Group;
use App\Models\Round;
use App\Models\Season;
use App\Models\Student;
use App\Models\Thana;
use App\Traits\AuthorizesWriteAction;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ParticipantManager extends Component
{
    use WithPagination, WithFileUploads, AuthorizesWriteAction;

    public $search = '';
    public $filterRoundId = '';
    public $viewingId = null;
    public $editingId = null;

    // Identity
    public $editName = '';
    public $editStudentId = '';
    public $editEmail = '';
    public $editPhone = '';
    public $editGender = '';
    public $editDob = '';
    public $editNidBirth = '';
    public $editImage = null;

    // Academic
    public $editClassId = '';
    public $editGroupId = '';
    public $editSeasonId = '';
    public $editKnownFrom = '';

    // Institute
    public $editInstituteName = '';
    public $editBoard = '';
    public $editInstituteMobile = '';
    public $editInstituteEmail = '';

    // Guardian
    public $editFatherName = '';
    public $editMotherName = '';
    public $editGuardianPhone = '';

    // Personal
    public $editHobby = '';
    public $editAimInLife = '';
    public $editFavouriteQuote = '';
    public $editIdol = '';

    // Address
    public $editDivisionId = '';
    public $editDistrictId = '';
    public $editThanaId = '';
    public $editAddress = '';

    public bool $editStatus = true;

    protected function rules()
    {
        return [
            'editName'            => 'required|string|max:255',
            'editStudentId'       => 'nullable|string|unique:students,student_id,' . $this->editingId,
            'editEmail'           => 'nullable|email|unique:students,email,' . $this->editingId,
            'editPhone'           => 'nullable|string|max:20',
            'editGender'          => 'nullable|in:male,female,others',
            'editDob'             => 'nullable|date',
            'editNidBirth'        => 'nullable|string|max:50',
            'editImage'           => 'nullable|image|max:2048',
            'editClassId'         => 'nullable|exists:class_levels,id',
            'editGroupId'         => 'nullable|exists:groups,id',
            'editSeasonId'        => 'nullable|exists:seasons,id',
            'editKnownFrom'       => 'nullable|in:Facebook,Mentor,Ambassador',
            'editInstituteName'   => 'nullable|string|max:255',
            'editBoard'           => 'nullable|string|max:100',
            'editInstituteMobile' => 'nullable|string|max:20',
            'editInstituteEmail'  => 'nullable|email|max:255',
            'editFatherName'      => 'nullable|string|max:255',
            'editMotherName'      => 'nullable|string|max:255',
            'editGuardianPhone'   => 'nullable|string|max:20',
            'editHobby'           => 'nullable|in:Reading,Sports,Music,Travel,Art',
            'editAimInLife'       => 'nullable|in:Engineer,Doctor,Teacher,Entrepreneur,Other',
            'editFavouriteQuote'  => 'nullable|string|max:500',
            'editIdol'            => 'nullable|string|max:255',
            'editDivisionId'      => 'nullable|exists:divisions,id',
            'editDistrictId'      => 'nullable|exists:districts,id',
            'editThanaId'         => 'nullable|exists:thanas,id',
            'editAddress'         => 'nullable|string|max:500',
            'editStatus'          => 'required|boolean',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRoundId()
    {
        $this->resetPage();
    }

    public function promote($id)
    {
        if (!$this->requireWrite('participants-promote')) return;

        $student = Student::findOrFail($id);
        $currentOrder = $student->effectiveRoundOrder();

        $next = Round::where('is_active', true)->where('order', '>', $currentOrder)->orderBy('order')->first();

        if (!$next) {
            session()->flash('error', $student->name . ' is already at the final round.');
            return;
        }

        $student->update(['current_round_id' => $next->id]);
        session()->flash('success', $student->name . ' promoted to "' . $next->name . '".');
    }

    public function view($id)
    {
        $this->viewingId = $id;
    }

    public function closeView()
    {
        $this->viewingId = null;
    }

    public function updatedEditDivisionId()
    {
        $this->editDistrictId = '';
        $this->editThanaId    = '';
    }

    public function updatedEditDistrictId()
    {
        $this->editThanaId = '';
    }

    public function edit($id)
    {
        if (!$this->requireWrite('participants-edit')) return;

        $student = Student::findOrFail($id);

        $this->editingId           = $student->id;
        $this->editName            = $student->name;
        $this->editStudentId       = $student->student_id;
        $this->editEmail           = $student->email;
        $this->editPhone           = $student->phone;
        $this->editGender          = $student->gender;
        $this->editDob             = $student->dob ? \Illuminate\Support\Carbon::parse($student->dob)->format('Y-m-d') : '';
        $this->editNidBirth        = $student->nid_birth;
        $this->editImage           = null;
        $this->editClassId         = $student->class_id;
        $this->editGroupId         = $student->group_id;
        $this->editSeasonId        = $student->season_id;
        $this->editKnownFrom       = $student->known_from;
        $this->editInstituteName   = $student->institute_name;
        $this->editBoard           = $student->board;
        $this->editInstituteMobile = $student->institute_mobile;
        $this->editInstituteEmail  = $student->institute_email;
        $this->editFatherName      = $student->father_name;
        $this->editMotherName      = $student->mother_name;
        $this->editGuardianPhone   = $student->guardian_phone;
        $this->editHobby           = $student->hobby;
        $this->editAimInLife       = $student->aim_in_life;
        $this->editFavouriteQuote  = $student->favourite_quote;
        $this->editIdol            = $student->idol;
        $this->editDivisionId      = $student->division_id;
        $this->editDistrictId      = $student->district_id;
        $this->editThanaId         = $student->thana_id;
        $this->editAddress         = $student->address;
        $this->editStatus          = (bool) $student->status;
    }

    public function closeEdit()
    {
        $this->editingId = null;
        $this->resetValidation();
    }

    public function update()
    {
        if (!$this->requireWrite('participants-edit')) return;

        $this->validate();

        $student = Student::findOrFail($this->editingId);

        $data = [
            'name'             => $this->editName,
            'student_id'       => $this->editStudentId,
            'email'            => $this->editEmail,
            'phone'            => $this->editPhone,
            'gender'           => $this->editGender ?: null,
            'dob'              => $this->editDob ?: null,
            'nid_birth'        => $this->editNidBirth,
            'class_id'         => $this->editClassId ?: null,
            'group_id'         => $this->editGroupId ?: null,
            'season_id'        => $this->editSeasonId ?: null,
            'known_from'       => $this->editKnownFrom,
            'institute_name'   => $this->editInstituteName,
            'board'            => $this->editBoard,
            'institute_mobile' => $this->editInstituteMobile,
            'institute_email'  => $this->editInstituteEmail,
            'father_name'      => $this->editFatherName,
            'mother_name'      => $this->editMotherName,
            'guardian_phone'   => $this->editGuardianPhone,
            'hobby'            => $this->editHobby,
            'aim_in_life'      => $this->editAimInLife,
            'favourite_quote'  => $this->editFavouriteQuote,
            'idol'             => $this->editIdol,
            'division_id'      => $this->editDivisionId ?: null,
            'district_id'      => $this->editDistrictId ?: null,
            'thana_id'         => $this->editThanaId ?: null,
            'address'          => $this->editAddress,
            'status'           => $this->editStatus,
        ];

        if ($this->editImage) {
            $data['image'] = $this->editImage->store('uploads/students', 'public');
        }

        $student->update($data);

        $this->closeEdit();
        session()->flash('success', 'Participant updated successfully.');
    }

    public function delete($id)
    {
        if (!$this->requireWrite('participants-delete')) return;

        Student::findOrFail($id)->delete();

        session()->flash('success', 'Participant deleted successfully.');
    }

    public function render()
    {
        $query = Student::with(['classLevel', 'group', 'season', 'division', 'district', 'thana', 'currentRound']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('student_id', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterRoundId) {
            $query->where('current_round_id', $this->filterRoundId);
        }

        return view('livewire.admin.participant-manager', [
            'students'       => $query->latest()->paginate(15),
            'viewingStudent' => $this->viewingId ? Student::with(['classLevel', 'group', 'season', 'division', 'district', 'thana', 'currentRound'])->find($this->viewingId) : null,
            'classLevels'    => ClassLevel::orderBy('name')->get(),
            'groups'         => Group::orderBy('name')->get(),
            'seasons'        => Season::orderBy('name')->get(),
            'divisions'      => Division::orderBy('name')->get(),
            'districts'      => $this->editDivisionId ? District::where('division_id', $this->editDivisionId)->orderBy('name')->get() : collect(),
            'thanas'         => $this->editDistrictId ? Thana::where('district_id', $this->editDistrictId)->orderBy('name')->get() : collect(),
            'rounds'         => Round::orderBy('order')->get(),
        ]);
    }
}
