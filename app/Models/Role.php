<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'display_name', 'status', 'commission_amount'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function hasPermission(string $routeName): bool
    {
        return $this->permissions->contains('name', $routeName);
    }

    public static function teamRoles()
    {
        return static::whereNotIn('name', ['admin', 'student'])
            ->where('status', 1)
            ->get();
    }
}
