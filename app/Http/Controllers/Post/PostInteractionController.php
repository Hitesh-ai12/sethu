<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostInteraction;

class PostInteractionController extends Controller
{
    // Store Interaction (like, comment, save)
    public function store(Request $request, $postId)
    {
        $request->validate([
            'action' => 'required|string|in:like,comment,save',
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


    //share apis here
        public function share(Request $request, $postId) {
            $request->validate([
                'action' => 'required|string|in:share',
            ]);

            $userId = auth()->id();

            // Store the share action
            PostInteraction::create([
                'post_id' => $postId,
                'user_id' => $userId,
                'action' => 'share',
                'content' => null, // No content needed for sharing
            ]);

            return response()->json([
                'message' => 'Post link shared successfully',
            ], 201);
        }

        // Fetch Share Count for a Post
        public function getShareCount($postId) {
            $shareCount = PostInteraction::where('post_id', $postId)
                ->where('action', 'share')
                ->count();

            return response()->json([
                'post_id' => $postId,
                'share_count' => $shareCount,
            ]);
        }


        // comment api here

            // Add a Comment to a Post
    public function addComment(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $userId = auth()->id();

        $comment = PostInteraction::create([
            'post_id' => $postId,
            'user_id' => $userId,
            'action' => 'comment',
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Comment added successfully.',
            'data' => $comment,
        ], 201);
    }

    // Fetch All Comments for a Post
    public function fetchComments($postId)
    {
        $comments = PostInteraction::where('post_id', $postId)
            ->where('action', 'comment')
            ->with('user:id,name') // Include user data
            ->latest() // Order by most recent comments
            ->get();

        return response()->json([
            'message' => 'Comments fetched successfully.',
            'data' => $comments,
        ]);
    }

    // Delete a Comment
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
            'message' => 'Comment deleted successfully.',
        ], 200);
    }

    // Update a Comment
    public function updateComment(Request $request, $postId, $commentId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

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

        $comment->update([
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Comment updated successfully.',
            'data' => $comment,
        ], 200);
    }
}
