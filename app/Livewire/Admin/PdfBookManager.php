<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\PdfBook;
use App\Models\Round;
use App\Models\Group;
use Illuminate\Support\Str;

class PdfBookManager extends Component
{
    use WithFileUploads, WithPagination;

    public $title = '';
    public $round_id = '';
    public $group_id = '';
    public $file_path; // file object for upload
    public $editingId = null;
    public $showForm = false;
    
    public $searchTitle = '';
    public $filterRound = '';
    public $filterGroup = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'round_id' => 'required|exists:rounds,id',
        'group_id' => 'required|exists:groups,id',
    ];

    public function openForm($id = null)
    {
        $this->resetValidation();
        if ($id) {
            $book = PdfBook::findOrFail($id);
            $this->editingId = $book->id;
            $this->title = $book->title;
            $this->round_id = $book->round_id;
            $this->group_id = $book->group_id;
        } else {
            $this->reset(['editingId', 'title', 'round_id', 'group_id', 'file_path']);
        }
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
    }

    public function save()
    {
        $rules = $this->rules;
        if (!$this->editingId) {
            $rules['file_path'] = 'required|file|mimes:pdf|max:20480'; // Max 20MB
        } else {
            $rules['file_path'] = 'nullable|file|mimes:pdf|max:20480';
        }

        $this->validate($rules);

        $data = [
            'title' => $this->title,
            'round_id' => $this->round_id,
            'group_id' => $this->group_id,
        ];

        if ($this->file_path) {
            $extension = $this->file_path->getClientOriginalExtension();
            $originalName = pathinfo($this->file_path->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = Str::slug($originalName);
            $filename = time() . '_' . $safeName . '.' . $extension;
            
            $destinationPath = public_path('pdf-books');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            // Bypass move() completely to prevent Windows locking issues
            file_put_contents(
                $destinationPath . '/' . $filename, 
                file_get_contents($this->file_path->getRealPath())
            );
            
            $data['file_path'] = 'pdf-books/' . $filename;
        }

        PdfBook::updateOrCreate(['id' => $this->editingId], $data);

        session()->flash('message', $this->editingId ? 'PDF Book updated!' : 'PDF Book uploaded!');
        $this->closeForm();
        $this->reset('file_path');
    }

    public function delete($id)
    {
        $book = PdfBook::findOrFail($id);
        // Optionally delete file from storage here
        // \Illuminate\Support\Facades\Storage::disk('public')->delete($book->file_path);
        
        $book->delete();
        session()->flash('message', 'PDF Book deleted!');
    }

    public function render()
    {
        $query = PdfBook::with(['round', 'group']);

        if ($this->searchTitle) {
            $query->where('title', 'like', '%' . $this->searchTitle . '%');
        }
        if ($this->filterRound) {
            $query->where('round_id', $this->filterRound);
        }
        if ($this->filterGroup) {
            $query->where('group_id', $this->filterGroup);
        }

        return view('livewire.admin.pdf-book-manager', [
            'books' => $query->latest()->paginate(10),
            'rounds' => Round::orderBy('order')->get(),
            'groups' => Group::all(),
        ]);
    }
}
