<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use App\Models\UserSkill;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'school_college_name',
        'city', 'mobile_number', 'full_address', 'role', 'otp_expires_at',
        'nickname', 'gender', 'dob', 'profile_image'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'dob' => 'date',
    ];

    // ✅ Password Hashing Fix
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    // ✅ Profile Image Full URL
    public function getProfileImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    // ✅ User Skills Relationship
    public function skills()
    {
        return $this->hasMany(UserSkill::class);
    }
}
