<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostInteraction;

class PostInteractionController extends Controller
{
    // Store Interaction (like, comment, save, share)
    public function store(Request $request, $postId)
    {
        $request->validate([
            'action' => 'required|string|in:like,comment,save,share',
            'content' => 'nullable|string|max:1000',
        ]);

        $userId = auth()->id();

        // Handle Like and Unlike
        if ($request->action === 'like') {
            $existingLike = PostInteraction::where([
                'post_id' => $postId,
                'user_id' => $userId,
                'action' => 'like',
            ])->first();

            if ($existingLike) {
                $existingLike->delete(); // Unlike the post
                return response()->json([
                    'message' => 'Like removed successfully',
                ], 200);
            }
        }

        // Handle Save and Unsave
        if ($request->action === 'save') {
            $existingSave = PostInteraction::where([
                'post_id' => $postId,
                'user_id' => $userId,
                'action' => 'save',
            ])->first();

            if ($existingSave) {
                $existingSave->delete(); // Unsave the post
                return response()->json([
                    'message' => 'Post unsaved successfully',
                ], 200);
            }
        }

        // Store Interaction
        $interaction = PostInteraction::create([
            'post_id' => $postId,
            'user_id' => $userId,
            'action' => $request->action,
            'content' => $request->action === 'comment' ? $request->content : null,
        ]);

        return response()->json([
            'message' => ucfirst($request->action) . ' stored successfully',
            'data' => $interaction,
        ], 201);
    }

    // Fetch All Interactions for a Post
    public function index($postId)
    {
        $interactions = PostInteraction::where('post_id', $postId)
            ->with('user:id,name') // Include user data
            ->get();

        return response()->json([
            'data' => $interactions,
        ]);
    }

    // Delete Comment
    public function deleteComment(Request $request, $postId, $commentId)
    {
        $userId = auth()->id();

        $comment = PostInteraction::where([
            'id' => $commentId,
            'post_id' => $postId,
            'user_id' => $userId,
            'action' => 'comment',
        ])->first();

        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found or unauthorized.',
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully',
        ], 200);
    }

    // Count Saved Posts by Logged-In User
    public function savedCount()
    {
        $userId = auth()->id();
        $savedCount = PostInteraction::where('user_id', $userId)
            ->where('action', 'save')
            ->count();

        return response()->json([
            'saved_count' => $savedCount,
        ]);
    }
}
