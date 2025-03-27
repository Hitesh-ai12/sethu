<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;

use App\Models\CommentInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentActionController extends Controller
{
    // Like a comment
    public function like(Request $request, $commentId)
    {
        $userId = auth()->id();

        $existingLike = CommentInteraction::where([
            'comment_id' => $commentId,
            'user_id' => $userId,
            'action' => 'like',
        ])->first();

        if ($existingLike) {
            $existingLike->delete();
            return response()->json(['message' => 'Like removed successfully.'], 200);
        }

        // Add a new like
        CommentInteraction::create([
            'comment_id' => $commentId,
            'user_id' => $userId,
            'action' => 'like',
        ]);

        return response()->json(['message' => 'Comment liked successfully.'], 201);
    }

    // Reply to a comment
    public function reply(Request $request, $commentId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $reply = CommentInteraction::create([
            'comment_id' => $commentId,
            'user_id' => auth()->id(),
            'action' => 'reply',
            'content' => $request->content,
        ]);

        return response()->json(['message' => 'Reply added successfully.', 'data' => $reply], 201);
    }

    // Fetch all replies for a comment
    public function fetchReplies($commentId)
    {
        $replies = CommentInteraction::where('comment_id', $commentId)
            ->where('action', 'reply')
            ->with('user:id,name')
            ->get();

        return response()->json(['message' => 'Replies fetched successfully.', 'data' => $replies], 200);
    }
}
