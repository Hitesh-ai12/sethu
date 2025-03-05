<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'school_college_name',
        'city', 'mobile_number', 'full_address', 'role', 'otp_expires_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime', // ✅ Correct way to cast dates
    ];

    // ✅ Automatically hash password before saving to DB
    public function setPasswordAttribute($value)
    {
        // Check if password is already hashed
        if (!Hash::needsRehash($value)) {
            $this->attributes['password'] = $value;
        } else {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    // ✅ Relationship: A user can have multiple skills
    public function skills()
    {
        return $this->hasMany(UserSkill::class);
    }
}
