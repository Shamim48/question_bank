<?php

namespace App\Livewire\Admin;

use App\Models\Group;
use Livewire\Component;

class GroupManager extends Component
{
    public $name = '';
    public $description = '';
    public $editingId = null;
    public $showForm = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    public function openForm($id = null)
    {
        $this->resetValidation();
        if ($id) {
            $group = Group::findOrFail($id);
            $this->editingId = $group->id;
            $this->name = $group->name;
            $this->description = $group->description ?? '';
        } else {
            $this->reset(['editingId', 'name', 'description']);
        }
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->reset(['editingId', 'name', 'description']);
    }

    public function save()
    {
        $this->validate();

        Group::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $this->name,
                'description' => $this->description,
            ]
        );

        session()->flash('success', $this->editingId ? 'Group updated!' : 'Group created!');
        $this->closeForm();
    }

    public function delete($id)
    {
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
