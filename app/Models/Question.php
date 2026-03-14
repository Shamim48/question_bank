<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'group_id',
        'type',
        'content',
        'media_url',
        'time_limit',
        'points',
        'options_count',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class)->orderBy('option_number');
    }

    public function correctOption()
    {
        return $this->hasOne(Option::class)->where('is_correct', true);
    }

    public function rounds()
    {
        return $this->belongsToMany(Round::class, 'question_round')
            ->withPivot('assigned_to')
            ->withTimestamps();
    }

    public function examAnswers()
    {
        return $this->hasMany(ExamAnswer::class);
    }
}
