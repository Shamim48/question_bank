<?php

namespace App\Livewire\Admin;

use App\Models\Question;
use App\Models\Option;
use App\Models\Subject;
use App\Models\Round;
use App\Models\Group;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class QuestionBank extends Component
{
    use WithFileUploads, WithPagination;

    public $subject_id = '';
    public $group_id = '';
    public $type = 'text';
    public $content = '';
    public $media_url = '';
    public $media_file;
    public $time_limit = 30;
    public $points = 1;
    public $options = [];
    public $correct_option = 1;
    public $editingId = null;
    public $showForm = false;
    public $filterSubject = '';
    public $filterGroup = '';
    public $filterType = '';
    public $search = '';
    public $filterRound = '';
    public $selectedRounds = [];

    protected $rules = [
        'subject_id' => 'required|exists:subjects,id',
        'group_id' => 'required|exists:groups,id',
        'type' => 'required|in:text,image,audio,video',
        'content' => 'required|string',
        'time_limit' => 'required|integer|min:5',
        'points' => 'required|integer|min:1',
        'options.*.text' => 'required|string',
    ];

    public function mount()
    {
        $this->initOptions();
    }

    private function initOptions()
    {
        $this->options = [
            ['text' => '', 'number' => 1],
            ['text' => '', 'number' => 2],
            ['text' => '', 'number' => 3],
            ['text' => '', 'number' => 4],
        ];
    }

    public function openForm($id = null)
    {
        $this->resetValidation();
        if ($id) {
            $question = Question::with('options', 'rounds')->findOrFail($id);
            $this->editingId = $question->id;
            $this->subject_id = $question->subject_id;
            $this->group_id = $question->group_id;
            $this->type = $question->type;
            $this->content = $question->content;
            $this->media_url = $question->media_url ?? '';
            $this->time_limit = $question->time_limit;
            $this->points = $question->points;
            $this->selectedRounds = $question->rounds->pluck('id')->toArray();

            $this->options = [];
            foreach ($question->options as $opt) {
                $this->options[] = ['text' => $opt->option_text, 'number' => $opt->option_number];
                if ($opt->is_correct) {
                    $this->correct_option = $opt->option_number;
                }
            }
            while (count($this->options) < 4) {
                $this->options[] = ['text' => '', 'number' => count($this->options) + 1];
            }
        } else {
            $this->reset(['editingId', 'subject_id', 'group_id', 'type', 'content', 'media_url', 'media_file', 'time_limit', 'points', 'correct_option', 'selectedRounds']);
            $this->type = 'text';
            $this->time_limit = 30;
            $this->points = 1;
            $this->correct_option = 1;
            $this->initOptions();
        }
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
    }

    public function save()
    {
        $this->validate();

        // Handle media file upload
        $mediaUrl = $this->media_url;
        if ($this->media_file) {
            $mediaUrl = $this->media_file->store('questions', 'public');
        }

        $question = Question::updateOrCreate(
            ['id' => $this->editingId],
            [
                'subject_id' => $this->subject_id,
                'group_id' => $this->group_id,
                'type' => $this->type,
                'content' => $this->content,
                'media_url' => $mediaUrl ?: null,
                'time_limit' => $this->time_limit,
                'points' => $this->points,
                'options_count' => 4,
            ]
        );

        // Save options
        $question->options()->delete();
        foreach ($this->options as $opt) {
            $question->options()->create([
                'option_text' => $opt['text'],
                'option_number' => $opt['number'],
                'is_correct' => $opt['number'] == $this->correct_option,
            ]);
        }

        // Sync rounds
        if (!empty($this->selectedRounds)) {
            $question->rounds()->sync($this->selectedRounds);
        }

        session()->flash('message', $this->editingId ? 'Question updated!' : 'Question created!');
        $this->closeForm();
    }

    public function delete($id)
    {
        Question::findOrFail($id)->delete();
        session()->flash('message', 'Question deleted!');
    }

    public function render()
    {
        $query = Question::with(['subject.round', 'group', 'options', 'rounds']);

        if ($this->filterRound) {
            $query->whereHas('rounds', function ($q) {
                $q->where('rounds.id', $this->filterRound);
            });
        }

        if ($this->filterSubject) {
            $query->where('subject_id', $this->filterSubject);
        }
        if ($this->filterGroup) {
            $query->where('group_id', $this->filterGroup);
        }
        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }
        if ($this->search) {
            $query->where('content', 'like', '%' . $this->search . '%');
        }

        return view('livewire.admin.question-bank', [
            'questions' => $query->latest()->paginate(10),
            'subjects' => Subject::with('round')->get(),
            'rounds' => Round::orderBy('order')->get(),
            'groups' => Group::all(),
        ]);
    }
}
