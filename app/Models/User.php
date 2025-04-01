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
        'followers', 'following', 'blocked_users', 'reported_users', 'can_share_profile', 'subject','status'
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


    public function getProfileVideoAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function skills()
    {
        return $this->hasMany(UserSkill::class);
    }

    public function follow($userId)
    {
        $followers = $this->followers ?? [];
        $following = $this->following ?? [];

        if (!in_array($userId, $following)) {
            $following[] = $userId;
            $this->following = $following;
            $this->save();

            $followedUser = User::find($userId);
            if ($followedUser) {
                $followersList = $followedUser->followers ?? [];
                $followersList[] = $this->id;
                $followedUser->followers = $followersList;
                $followedUser->save();
            }

            return true;
        }
        return false;
    }

    public function unfollow($userId)
    {
        $following = $this->following ?? [];
        if (($key = array_search($userId, $following)) !== false) {
            unset($following[$key]);
            $this->following = array_values($following);
            $this->save();

            $followedUser = User::find($userId);
            if ($followedUser) {
                $followersList = $followedUser->followers ?? [];
                if (($key2 = array_search($this->id, $followersList)) !== false) {
                    unset($followersList[$key2]);
                    $followedUser->followers = array_values($followersList);
                    $followedUser->save();
                }
            }
            return true;
        }
        return false;
    }

    public function isFollowing($userId)
    {
        return in_array($userId, $this->following ?? []);
    }

    public function isFollowedBy($userId)
    {
        return in_array($userId, $this->followers ?? []);
    }
    public function banners()
    {
        return $this->hasMany(Banner::class, 'user_id');
    }

}
