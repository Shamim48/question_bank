<?php

namespace App\Livewire\Admin;

use App\Models\Group;
use App\Traits\AuthorizesWriteAction;
use Livewire\Component;

class GroupManager extends Component
{
    use AuthorizesWriteAction;

    public $name = '';
    public $description = '';
    public $googleFormUrl = '';
    public $googleFormNote = '';
    public $editingId = null;
    public $showForm = false;

    protected $rules = [
        'name'            => 'required|string|max:255',
        'description'     => 'nullable|string',
        'googleFormUrl'   => 'nullable|url|max:500',
        'googleFormNote'  => 'nullable|string|max:255',
    ];

    public function openForm($id = null)
    {
        if (!$this->requireWrite($id ? 'groups-edit' : 'groups-create')) return;

        $this->resetValidation();
        if ($id) {
            $group                = Group::findOrFail($id);
            $this->editingId      = $group->id;
            $this->name           = $group->name;
            $this->description    = $group->description ?? '';
            $this->googleFormUrl  = $group->google_form_url ?? '';
            $this->googleFormNote = $group->google_form_note ?? '';
        } else {
            $this->reset(['editingId', 'name', 'description', 'googleFormUrl', 'googleFormNote']);
        }
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->reset(['editingId', 'name', 'description', 'googleFormUrl', 'googleFormNote']);
    }

    public function save()
    {
        if (!$this->requireWrite($this->editingId ? 'groups-edit' : 'groups-create')) return;

        $this->validate();

        Group::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name'             => $this->name,
                'description'      => $this->description,
                'google_form_url'  => $this->googleFormUrl ?: null,
                'google_form_note' => $this->googleFormNote ?: null,
            ]
        );

        session()->flash('success', $this->editingId ? 'Group updated!' : 'Group created!');
        $this->closeForm();
    }

    public function delete($id)
    {
        if (!$this->requireWrite('groups-delete')) return;

        Group::findOrFail($id)->delete();
        session()->flash('success', 'Group deleted!');
    }

    public function render()
    {
        return view('livewire.admin.group-manager', [
            'groups' => Group::withCount('questions')->get(),
        ]);
    }
}
