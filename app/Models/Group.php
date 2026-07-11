<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'google_form_url', 'google_form_note'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
