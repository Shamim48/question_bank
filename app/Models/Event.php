<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'class_id',
        'category',
        'start_date',
        'end_date',
        'url',
        'season_id',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'status'     => 'boolean',
    ];

    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class, 'class_id');
    }

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id');
    }
}
