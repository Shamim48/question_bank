<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'round_id',
        'subject_id',
        'online_marks',
        'manual_marks',
        'total_marks',
    ];

    protected $casts = [
        'online_marks' => 'decimal:2',
        'manual_marks' => 'decimal:2',
        'total_marks' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function updateTotal(): void
    {
        $this->total_marks = $this->online_marks + $this->manual_marks;
        $this->save();
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
