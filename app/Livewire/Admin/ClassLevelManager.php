<?php

namespace App\Livewire\Admin;

use App\Models\ClassLevel;
use App\Models\Group;
use App\Traits\AuthorizesWriteAction;
use Livewire\Component;

class ClassLevelManager extends Component
{
    use AuthorizesWriteAction;

    public $name = '';
    public $group_id = '';
    public bool $status = true;
    public $editingId = null;
    public $showForm = false;

    protected $rules = [
        'name'     => 'required|string|max:255',
        'group_id' => 'nullable|exists:groups,id',
        'status'   => 'boolean',
    ];

    public function openForm($id = null)
    {
        if (!$this->requireWrite($id ? 'class-levels-edit' : 'class-levels-create')) return;

        $this->resetValidation();
        if ($id) {
            $classLevel      = ClassLevel::findOrFail($id);
            $this->editingId = $classLevel->id;
            $this->name      = $classLevel->name;
            $this->group_id  = $classLevel->group_id;
            $this->status    = (bool) $classLevel->status;
        } else {
            $this->reset(['editingId', 'name', 'group_id']);
            $this->status = true;
        }
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->reset(['editingId', 'name', 'group_id']);
        $this->status = true;
    }

    public function save()
    {
        if (!$this->requireWrite($this->editingId ? 'class-levels-edit' : 'class-levels-create')) return;

        $this->validate();

        ClassLevel::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name'     => $this->name,
                'group_id' => $this->group_id ?: null,
                'status'   => $this->status,
            ]
        );

        session()->flash('success', $this->editingId ? 'Class level updated!' : 'Class level created!');
        $this->closeForm();
    }

    public function toggleStatus($id)
    {
        if (!$this->requireWrite('class-levels-edit')) return;

        $classLevel = ClassLevel::findOrFail($id);
        $classLevel->update(['status' => $classLevel->status ? 0 : 1]);

        session()->flash('success', 'Class level status updated!');
    }

    public function delete($id)
    {
        if (!$this->requireWrite('class-levels-delete')) return;

        ClassLevel::findOrFail($id)->delete();
        session()->flash('success', 'Class level deleted!');
    }

    public function render()
    {
        return view('livewire.admin.class-level-manager', [
            'classLevels' => ClassLevel::with('group')->latest()->get(),
            'groups'      => Group::orderBy('name')->get(),
        ]);
    }
}
