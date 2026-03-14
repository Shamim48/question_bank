<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Question;
use App\Models\Option;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionsImport;
use App\Exports\QuestionsExport;

class QuestionImportExport extends Component
{
    use WithFileUploads;

    public $file;
    public $export_round_id = '';
    public $export_subject_id = '';
    public $export_group_id = '';

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new QuestionsImport(), $this->file);
            session()->flash('message', 'Questions imported successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Import failed: ' . $e->getMessage());
        }

        $this->reset(['file']);
    }

    public function export()
    {
        return Excel::download(new QuestionsExport($this->export_round_id, $this->export_subject_id, $this->export_group_id), 'questions-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function render()
    {
        return view('livewire.admin.question-import-export', [
            'rounds' => \App\Models\Round::orderBy('order')->get(),
            'subjects' => \App\Models\Subject::with('round')->get(),
            'groups' => \App\Models\Group::all(),
        ]);
    }
}
