<?php

namespace App\Exports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuestionsExport implements FromCollection, WithHeadings
{
    protected $round_id;
    protected $subject_id;
    protected $group_id;

    public function __construct($round_id = null, $subject_id = null, $group_id = null)
    {
        $this->round_id = $round_id;
        $this->subject_id = $subject_id;
        $this->group_id = $group_id;
    }
    public function headings(): array
    {
        return [
            'Round',
            'Subject',
            'Group',
            'Type',
            'Question',
            'Option 1',
            'Option 2',
            'Option 3',
            'Option 4',
            'Correct Option',
            'Time Limit',
            'Points',
            'Media URL',
        ];
    }

    public function collection()
    {
        $query = Question::with(['subject.round', 'group', 'options', 'rounds']);

        if ($this->round_id) {
            $query->whereHas('rounds', function($q) {
                $q->where('rounds.id', $this->round_id);
            });
        }
        if ($this->subject_id) {
            $query->where('subject_id', $this->subject_id);
        }
        if ($this->group_id) {
            $query->where('group_id', $this->group_id);
        }

        return $query->get()->map(function ($q) {
            $options = $q->options->sortBy('option_number');
            $correctOption = $options->firstWhere('is_correct', true);

            return [
                'round' => $q->rounds->pluck('name')->implode(', ') ?: ($q->subject->round->name ?? 'N/A'),
                'subject' => $q->subject->name ?? 'N/A',
                'group' => $q->group->name ?? '',
                'type' => $q->type,
                'question' => $q->content,
                'option_1' => $options->firstWhere('option_number', 1)->option_text ?? '',
                'option_2' => $options->firstWhere('option_number', 2)->option_text ?? '',
                'option_3' => $options->firstWhere('option_number', 3)->option_text ?? '',
                'option_4' => $options->firstWhere('option_number', 4)->option_text ?? '',
                'correct_option' => $correctOption ? $correctOption->option_text : '',
                'time_limit' => $q->time_limit,
                'points' => $q->points,
                'media_url' => $q->media_url ?? '',
            ];
        });
    }
}
