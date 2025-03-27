<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'user_id',
        'action',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comment()
    {
        return $this->belongsTo(PostInteraction::class, 'comment_id');
    }
}

