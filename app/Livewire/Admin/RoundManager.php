<?php

namespace App\Livewire\Admin;

use App\Models\Round;
use App\Traits\AuthorizesWriteAction;
use Livewire\Component;

class RoundManager extends Component
{
    use AuthorizesWriteAction;

    public $name = '';
    public $description = '';
    public $is_active = false;
    public $is_final = false;
    public $order = 0;
    public $editingId = null;
    public $showForm = false;

    protected $rules = [
        'name'        => 'required|string|max:255',
        'description' => 'nullable|string',
        'is_active'   => 'boolean',
        'is_final'    => 'boolean',
        'order'       => 'integer|min:0',
    ];

    public function openForm($id = null)
    {
        if (!$this->requireWrite($id ? 'rounds-edit' : 'rounds-create')) return;

        $this->resetValidation();
        if ($id) {
            $round             = Round::findOrFail($id);
            $this->editingId   = $round->id;
            $this->name        = $round->name;
            $this->description = $round->description ?? '';
            $this->is_active   = $round->is_active;
            $this->is_final    = $round->is_final;
            $this->order       = $round->order;
        } else {
            $this->reset(['editingId', 'name', 'description', 'is_active', 'is_final', 'order']);
        }
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->reset(['editingId', 'name', 'description', 'is_active', 'is_final', 'order']);
    }

    public function save()
    {
        if (!$this->requireWrite($this->editingId ? 'rounds-edit' : 'rounds-create')) return;

        $this->validate();

        Round::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name'        => $this->name,
                'description' => $this->description,
                'is_active'   => $this->is_active,
                'is_final'    => $this->is_final,
                'order'       => $this->order,
            ]
        );

        session()->flash('message', $this->editingId ? 'Round updated!' : 'Round created!');
        $this->closeForm();
    }

    public function toggleActive($id)
    {
        if (!$this->requireWrite('rounds-edit')) return;

        $round = Round::findOrFail($id);
        $round->update(['is_active' => !$round->is_active]);
    }

    public function delete($id)
    {
        if (!$this->requireWrite('rounds-delete')) return;

        Round::findOrFail($id)->delete();
        session()->flash('message', 'Round deleted!');
    }

    public function render()
    {
        return view('livewire.admin.round-manager', [
            'rounds' => Round::orderBy('order')->get(),
        ]);
    }
}
