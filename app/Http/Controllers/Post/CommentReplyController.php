<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommentReply;

class CommentReplyController extends Controller
{
    // Add a Reply to a Comment
    public function addReply(Request $request, $commentId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $userId = auth()->id();

        $reply = CommentReply::create([
            'comment_id' => $commentId,
            'user_id' => $userId,
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Reply added successfully.',
            'data' => $reply,
        ], 201);
    }

    // Fetch Replies for a Comment
    public function fetchReplies($commentId)
    {
        $replies = CommentReply::where('comment_id', $commentId)
            ->with('user:id,name') // Include user details
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Replies fetched successfully.',
            'data' => $replies,
        ]);
    }

    // Delete a Reply
    public function deleteReply($replyId)
    {
        $userId = auth()->id();

        $reply = CommentReply::where([
            'id' => $replyId,
            'user_id' => $userId,
        ])->first();

        if (!$reply) {
            return response()->json([
                'message' => 'Reply not found or unauthorized.',
            ], 404);
        }

        $reply->delete();

        return response()->json([
            'message' => 'Reply deleted successfully.',
        ], 200);
    }
}
