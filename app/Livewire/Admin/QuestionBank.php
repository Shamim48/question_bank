<?php

namespace App\Livewire\Admin;

use App\Models\Group;
use App\Models\Option;
use App\Models\Question;
use App\Models\Round;
use App\Models\Subject;
use App\Traits\AuthorizesWriteAction;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class QuestionBank extends Component
{
    use WithFileUploads, WithPagination, AuthorizesWriteAction;

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

    protected function rules()
    {
        return [
            'subject_id' => 'required|exists:subjects,id',
            'group_id' => 'required|exists:groups,id',
            'type' => 'required|in:text,image,audio,video',
            'content' => 'required|string|min:5',
            'time_limit' => 'required|integer|min:5|max:3600',
            'points' => 'required|integer|min:1|max:100',
            'selectedRounds' => 'required|array|min:1',
            'correct_option' => 'required|integer|min:1|max:4',
            'options.*.text' => 'required|string|distinct',
            'media_url' => $this->type !== 'text' ? 'nullable|url' : 'nullable',
            'media_file' => $this->type === 'image' ? 'nullable|image|max:5120' : ($this->type !== 'text' ? 'nullable|file|max:20480' : 'nullable'),
        ];
    }

    protected $messages = [
        'subject_id.required' => 'Please select a subject domain.',
        'group_id.required' => 'A group policy must be selected.',
        'selectedRounds.required' => 'You must include at least one round.',
        'content.required' => 'The question content cannot be empty.',
        'content.min' => 'Question content is too short (min 5 chars).',
        'options.*.text.required' => 'All options must have text.',
        'options.*.text.distinct' => 'Option texts must be unique.',
        'media_url.url' => 'Please provide a valid URL for media.',
        'media_file.image' => 'The uploaded file must be an image.',
        'media_file.max' => 'The file size is too large (max 5MB for images, 20MB for others).',
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
        if (!$this->requireWrite($id ? 'questions-edit' : 'questions-create')) return;

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
        if (!$this->requireWrite($this->editingId ? 'questions-edit' : 'questions-create')) return;

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
        if (!$this->requireWrite('questions-delete')) return;

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
