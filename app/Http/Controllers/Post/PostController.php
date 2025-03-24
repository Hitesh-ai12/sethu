<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Store a new post.
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'content' => 'nullable|string|max:1000',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,mp4,mov,avi,wmv|max:10240',
            'youtube_link' => 'nullable|url',
        ]);

        // Check if user is authenticated
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('image')) { // Corrected to 'image' instead of 'file'
            $filePath = $request->file('image')->store('post_files', 'public');
        }

        // Create the post
        $post = Post::create([
            'user_id' => $user->id,
            'content' => $request->content,
            'image' => $filePath, // Store the file path in the 'image' column
            'youtube_link' => $request->youtube_link,
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post,
            'file_url' => $filePath ? asset('storage/' . $filePath) : null, // Return the file URL
        ], 201);
    }


    /**
     * Get all posts.
     */
    public function getPosts()
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Fetch posts with user details
        $posts = Post::with('user:id,name,email')->orderBy('created_at', 'desc')->get();

        if ($posts->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No posts available',
                'posts' => [],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Posts fetched successfully',
            'posts' => $posts,
        ]);
    }
}
