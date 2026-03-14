<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'is_active', 'is_final', 'order'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_final' => 'boolean',
    ];

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_round')
            ->withPivot('assigned_to')
            ->withTimestamps();
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
