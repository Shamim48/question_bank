<?php

namespace App\Models;

use App\Models\ClassLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

  protected $fillable = [
    'student_id',
    'user_id',
    'name',
    'email',
    'phone',
    'class_id',
    'group_id',
    'season_id',
    'referrer_id',
    'known_from',
    'institute_name',
    'board',
    'gender',
    'dob',
    'nid_birth',
    'institute_mobile',
    'institute_email',
    'father_name',
    'mother_name',
    'guardian_phone',
    'hobby',
    'aim_in_life',
    'favourite_quote',
    'idol',
    'image',
    'thana_id',
    'district_id',
    'division_id',
    'address',
    'status',
  ];



  // Relation to Admin (Referrer)
//   public function referrer()
//   {
//     return $this->belongsTo(Admin::class, 'referrer_id');
//   }

  // Optionally, relation to Event
//   public function event()
//   {
//     return $this->belongsTo(Event::class);
//   }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public static function generateStudentId()
  {
    $randomNumber = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    return 'STU' . $randomNumber;
  }

  // Division relationship
  public function division()
  {
    return $this->belongsTo(Division::class, 'division_id');
  }

  // District relationship
  public function district()
  {
    return $this->belongsTo(District::class, 'district_id');
  }

  // Thana / Upazilla relationship
  public function thana()
  {
    return $this->belongsTo(Thana::class, 'thana_id');
  }

  public function classLevel()
  {
    return $this->belongsTo(ClassLevel::class, 'class_id');
  }

  public function group()
  {
    return $this->belongsTo(\App\Models\Group::class, 'group_id');
  }

  public function season()
  {
    return $this->belongsTo(\App\Models\Season::class, 'season_id');
  }

//   public function classModel()
//   {
//     return $this->belongsTo(ClassModel::class, 'class_id');
//   }

//   public function season()
//   {
//     return $this->belongsTo(Season::class, 'season_id');
//   }
}
