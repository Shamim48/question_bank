<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassLevel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'group_id', 'status'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
