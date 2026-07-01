<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'season_id', 'role', 'image',
        'whatsapp', 'telegram',
        'institute_name', 'designation', 'department',
        'institute_mobile', 'institute_email', 'eiin_no',
        'division_id', 'district_id', 'thana_id', 'address',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function thana()
    {
        return $this->belongsTo(Thana::class, 'thana_id');
    }

    public function isPending(): bool
    {
        return $this->status === 0;
    }

    public function isApproved(): bool
    {
        return $this->status === 1;
    }

    public function isRejected(): bool
    {
        return $this->status === 2;
    }
}
