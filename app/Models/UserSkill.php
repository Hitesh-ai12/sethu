<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSkill extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'skill_name'];

    // Relationship: A user can have multiple skills
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
