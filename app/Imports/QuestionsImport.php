<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\Option;
use App\Models\Round;
use App\Models\Subject;
use App\Models\Group;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToModel, WithHeadingRow
{
    public function __construct()
    {
    }

    public function model(array $row)
    {
        $content = $row['question'] ?? $row['content'] ?? null;
        if (empty($content)) return null;

        $round = Round::firstOrCreate(
            ['name' => $row['round'] ?? 'Default Round'],
            ['order' => Round::max('order') + 1 ?? 1]
        );

        $subject = Subject::firstOrCreate([
            'name' => $row['subject'] ?? 'Default Subject',
            'round_id' => $round->id
        ]);

        $group = null;
        if (!empty($row['group'])) {
            $group = Group::firstOrCreate(['name' => $row['group']]);
        }

        $question = Question::create([
            'subject_id' => $subject->id,
            'group_id' => $group ? $group->id : null,
            'type' => $row['type'] ?? 'text',
            'content' => $content,
            'media_url' => $row['media_url'] ?? null,
            'time_limit' => $row['time_limit'] ?? 30,
            'points' => $row['points'] ?? 1,
            'options_count' => 4,
        ]);

        $question->rounds()->sync([$round->id]);

        $correctOptionValue = $row['correct_option'] ?? 1;
        $correctOptionNumber = 1;

        // Try to match the provided text to one of the options
        for ($i = 1; $i <= 4; $i++) {
            $optionKey = 'option_' . $i;
            if (isset($row[$optionKey]) && $row[$optionKey] == $correctOptionValue) {
                $correctOptionNumber = $i;
                break;
            }
        }
        // Fallback: if it was provided as a number directly
        if (is_numeric($correctOptionValue) && $correctOptionValue >= 1 && $correctOptionValue <= 4) {
            $correctOptionNumber = (int)$correctOptionValue;
        }

        for ($i = 1; $i <= 4; $i++) {
            $optionKey = 'option_' . $i;
            if (isset($row[$optionKey])) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $row[$optionKey],
                    'option_number' => $i,
                    'is_correct' => $i === $correctOptionNumber,
                ]);
            }
        }

        return $question;
    }
}
