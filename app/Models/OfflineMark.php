<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflineMark extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'round_id',
        'subject_id',
        'judge_name',
        'marks',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
