<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostInteraction extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'user_id', 'action', 'content', 'parent_id'];


    // Relationships
    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function replies()
    {
        return $this->hasMany(CommentReply::class, 'comment_id');
    }

    public function likes()
    {
        return $this->hasMany(PostInteraction::class, 'parent_id')
            ->where('action', 'like');
    }

    public function parent()
    {
        return $this->belongsTo(PostInteraction::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(PostInteraction::class, 'parent_id');
    }
}
