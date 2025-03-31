<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPostInteraction;

class JobPostInteractionController extends Controller
{
    // Store Interaction (Like, Comment, Save)
    public function store(Request $request, $jobId)
    {
        $request->validate([
            'action' => 'required|string|in:like,comment,save',
            'content' => 'nullable|string|max:1000',
        ]);

        $userId = auth()->id();

        // Handle Like and Unlike
        if ($request->action === 'like') {
            $existingLike = JobPostInteraction::where([
                'job_post_id' => $jobId,
                'user_id' => $userId,
                'action' => 'like',
            ])->first();

            if ($existingLike) {
                $existingLike->delete(); // Unlike the job post
                return response()->json(['message' => 'Like removed successfully'], 200);
            }
        }

        // Handle Save and Unsave
        if ($request->action === 'save') {
            $existingSave = JobPostInteraction::where([
                'job_post_id' => $jobId,
                'user_id' => $userId,
                'action' => 'save',
            ])->first();

            if ($existingSave) {
                $existingSave->delete(); // Unsave the job post
                return response()->json(['message' => 'Job post unsaved successfully'], 200);
            }
        }

        // Store Interaction
        $interaction = JobPostInteraction::create([
            'job_post_id' => $jobId,
            'user_id' => $userId,
            'action' => $request->action,
            'content' => $request->action === 'comment' ? $request->content : null,
        ]);

        return response()->json([
            'message' => ucfirst($request->action) . ' stored successfully',
            'data' => $interaction,
        ], 201);
    }

    // Fetch All Comments for a Job Post
    public function fetchComments($jobId)
    {
        $comments = JobPostInteraction::where('job_post_id', $jobId)
            ->where('action', 'comment')
            ->with('user:id,name')
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Comments fetched successfully',
            'data' => $comments,
        ]);
    }

    // Delete a Comment
    public function deleteComment(Request $request, $jobId, $commentId)
    {
        $userId = auth()->id();

        $comment = JobPostInteraction::where([
            'id' => $commentId,
            'job_post_id' => $jobId,
            'user_id' => $userId,
            'action' => 'comment',
        ])->first();

        if (!$comment) {
            return response()->json(['message' => 'Comment not found or unauthorized'], 404);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }

    // Update a Comment
    public function updateComment(Request $request, $jobId, $commentId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $userId = auth()->id();

        $comment = JobPostInteraction::where([
            'id' => $commentId,
            'job_post_id' => $jobId,
            'user_id' => $userId,
            'action' => 'comment',
        ])->first();

        if (!$comment) {
            return response()->json(['message' => 'Comment not found or unauthorized'], 404);
        }

        $comment->update(['content' => $request->content]);

        return response()->json([
            'message' => 'Comment updated successfully',
            'data' => $comment,
        ], 200);
    }
}
