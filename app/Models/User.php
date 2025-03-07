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
        'nickname', 'gender', 'dob', 'profile_image', 'description',
        'mentorship', 'community', 'profile_photo', 'profile_video',
        'followers', 'following', 'blocked_users', 'reported_users', 'can_share_profile'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'dob' => 'date',
        'mentorship' => 'boolean',
        'community' => 'boolean',
        'can_share_profile' => 'boolean',
        'followers' => 'array',
        'following' => 'array',
        'blocked_users' => 'array',
        'reported_users' => 'array',
    ];

    public function getProfileImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    // Profile Video Accessor
    public function getProfileVideoAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function skills()
    {
        return $this->hasMany(UserSkill::class);
    }
}
