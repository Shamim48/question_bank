<?php

namespace App\Livewire\Admin;

use App\Models\ClassLevel;
use App\Models\Event;
use App\Models\Season;
use App\Traits\AuthorizesWriteAction;
use Livewire\Component;

class EventManager extends Component
{
    use AuthorizesWriteAction;

    public $name = '';
    public $class_id = '';
    public $category = '';
    public $start_date = '';
    public $end_date = '';
    public $url = '';
    public $season_id = '';
    public bool $status = true;
    public $editingId = null;
    public $showForm = false;

    protected $rules = [
        'name'       => 'required|string|max:255',
        'class_id'   => 'nullable|exists:class_levels,id',
        'category'   => 'nullable|string|max:255',
        'start_date' => 'nullable|date',
        'end_date'   => 'nullable|date|after_or_equal:start_date',
        'url'        => 'nullable|url|max:255',
        'season_id'  => 'nullable|exists:seasons,id',
        'status'     => 'boolean',
    ];

    public function openForm($id = null)
    {
        if (!$this->requireWrite($id ? 'events-edit' : 'events-create')) return;

        $this->resetValidation();
        if ($id) {
            $event            = Event::findOrFail($id);
            $this->editingId  = $event->id;
            $this->name       = $event->name;
            $this->class_id   = $event->class_id;
            $this->category   = $event->category;
            $this->start_date = optional($event->start_date)->format('Y-m-d\TH:i');
            $this->end_date   = optional($event->end_date)->format('Y-m-d\TH:i');
            $this->url        = $event->url;
            $this->season_id  = $event->season_id;
            $this->status     = (bool) $event->status;
        } else {
            $this->reset(['editingId', 'name', 'class_id', 'category', 'start_date', 'end_date', 'url', 'season_id']);
            $this->status = true;
        }
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->reset(['editingId', 'name', 'class_id', 'category', 'start_date', 'end_date', 'url', 'season_id']);
        $this->status = true;
    }

    public function save()
    {
        if (!$this->requireWrite($this->editingId ? 'events-edit' : 'events-create')) return;

        $this->validate();

        Event::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name'       => $this->name,
                'class_id'   => $this->class_id ?: null,
                'category'   => $this->category,
                'start_date' => $this->start_date ?: null,
                'end_date'   => $this->end_date ?: null,
                'url'        => $this->url,
                'season_id'  => $this->season_id ?: null,
                'status'     => $this->status,
            ]
        );

        session()->flash('success', $this->editingId ? 'Event updated!' : 'Event created!');
        $this->closeForm();
    }

    public function toggleStatus($id)
    {
        if (!$this->requireWrite('events-edit')) return;

        $event = Event::findOrFail($id);
        $event->update(['status' => $event->status ? 0 : 1]);

        session()->flash('success', 'Event status updated!');
    }

    public function delete($id)
    {
        if (!$this->requireWrite('events-delete')) return;

        Event::findOrFail($id)->delete();
        session()->flash('success', 'Event deleted!');
    }

    public function render()
    {
        return view('livewire.admin.event-manager', [
            'events'     => Event::with(['classLevel', 'season'])->latest()->get(),
            'classLevels' => ClassLevel::orderBy('name')->get(),
            'seasons'    => Season::orderBy('name')->get(),
        ]);
    }
}
