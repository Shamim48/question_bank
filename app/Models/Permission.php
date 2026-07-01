<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'label', 'group'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    public static function allGrouped(): \Illuminate\Support\Collection
    {
        return static::orderBy('group')->orderBy('label')->get()->groupBy('group');
    }
}
