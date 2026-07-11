<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = ['referrer_user_id', 'student_id', 'amount', 'status'];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_user_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
