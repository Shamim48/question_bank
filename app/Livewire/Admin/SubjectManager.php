<?php

namespace App\Livewire\Admin;

use App\Models\Round;
use App\Models\Subject;
use App\Traits\AuthorizesWriteAction;
use Livewire\Component;

class SubjectManager extends Component
{
    use AuthorizesWriteAction;

    public $name = '';
    public $description = '';
    public $round_id = '';
    public $editingId = null;
    public $showForm = false;

    protected $rules = [
        'name'        => 'required|string|max:255',
        'description' => 'nullable|string',
        'round_id'    => 'required|exists:rounds,id',
    ];

    public function openForm($id = null)
    {
        if (!$this->requireWrite($id ? 'subjects-edit' : 'subjects-create')) return;

        $this->resetValidation();
        if ($id) {
            $subject           = Subject::findOrFail($id);
            $this->editingId   = $subject->id;
            $this->name        = $subject->name;
            $this->description = $subject->description ?? '';
            $this->round_id    = $subject->round_id;
        } else {
            $this->reset(['editingId', 'name', 'description', 'round_id']);
        }
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->reset(['editingId', 'name', 'description', 'round_id']);
    }

    public function save()
    {
        if (!$this->requireWrite($this->editingId ? 'subjects-edit' : 'subjects-create')) return;

        $this->validate();

        Subject::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name'        => $this->name,
                'description' => $this->description,
                'round_id'    => $this->round_id,
            ]
        );

        session()->flash('message', $this->editingId ? 'Subject updated!' : 'Subject created!');
        $this->closeForm();
    }

    public function delete($id)
    {
        if (!$this->requireWrite('subjects-delete')) return;

        Subject::findOrFail($id)->delete();
        session()->flash('message', 'Subject deleted!');
    }

    public function render()
    {
        return view('livewire.admin.subject-manager', [
            'subjects' => Subject::with('round')->get(),
            'rounds'   => Round::orderBy('order')->get(),
        ]);
    }
}
