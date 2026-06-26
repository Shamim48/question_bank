<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'country_id',
        'name',
        'bn_name',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // public function country()
    // {
    //     return $this->belongsTo(Country::class);
    // }
}
