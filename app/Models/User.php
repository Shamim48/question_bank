<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'referral_code',
        'class',
        'group',
        'phone',
        'division',
        'district',
        'permissions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'permissions'       => 'array',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isTeam(): bool
    {
        return !$this->isAdmin() && !$this->isStudent();
    }

    public function hasPermission(string $routeName): bool
    {
        if ($this->isAdmin()) return true;

        static $roleCache = [];

        if (!isset($roleCache[$this->role])) {
            $roleCache[$this->role] = Role::with('permissions')
                ->where('name', $this->role)
                ->first();
        }

        $role = $roleCache[$this->role];

        return $role && $role->hasPermission($routeName);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    public function team()
    {
        return $this->hasOne(\App\Models\Team::class, 'user_id');
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class, 'referrer_user_id');
    }

    public static function generateReferralCode(string $name): string
    {
        $base = Str::slug(Str::before($name, ' ')) ?: 'user';

        do {
            $code = strtoupper($base . '-' . Str::random(4));
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }
}
