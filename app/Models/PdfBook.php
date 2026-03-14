<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdfBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'round_id',
        'group_id',
        'file_path',
    ];

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
