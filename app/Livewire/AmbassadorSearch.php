<?php

namespace App\Livewire;

use App\Models\Ambassador;
use Livewire\Component;
use Livewire\WithPagination;

class AmbassadorSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $filterTeam = '';
    public $filterEvent = '';
    public $filterDivision = '';
    public $filterDistrict = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Ambassador::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        if ($this->filterTeam) {
            $query->where('team', $this->filterTeam);
        }
        if ($this->filterEvent) {
            $query->where('event', $this->filterEvent);
        }
        if ($this->filterDivision) {
            $query->where('division', $this->filterDivision);
        }
        if ($this->filterDistrict) {
            $query->where('district', $this->filterDistrict);
        }

        return view('livewire.ambassador-search', [
            'ambassadors' => $query->paginate(12),
            'teams' => Ambassador::distinct()->pluck('team')->filter(),
            'events' => Ambassador::distinct()->pluck('event')->filter(),
            'divisions' => Ambassador::distinct()->pluck('division')->filter(),
            'districts' => Ambassador::distinct()->pluck('district')->filter(),
        ]);
    }
}
