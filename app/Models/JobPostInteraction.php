<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPostInteraction extends Model
{
    use HasFactory;

    protected $fillable = ['job_post_id', 'user_id', 'action', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
