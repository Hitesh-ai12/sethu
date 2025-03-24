<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostInteraction;

class CommentLikeController extends Controller
{
    // Like or Unlike a Comment
    public function toggleLike(Request $request, $commentId)
    {
        $userId = auth()->id();

        $existingLike = PostInteraction::where([
            'parent_id' => $commentId,
            'user_id' => $userId,
            'action' => 'like',
        ])->first();

        if ($existingLike) {
            $existingLike->delete();
            return response()->json(['message' => 'Like removed from comment.'], 200);
        }

        PostInteraction::create([
            'parent_id' => $commentId,
            'user_id' => $userId,
            'action' => 'like',
        ]);

        return response()->json(['message' => 'Comment liked successfully.'], 201);
    }
}
