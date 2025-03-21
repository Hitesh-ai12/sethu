<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'content' => 'nullable|string|max:1000',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,mp4,mov,avi,wmv|max:10240',
            'youtube_link' => 'nullable|url',
        ]);

        $user = auth()->user();

        $filePath = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('post_files', 'public');
        }

        $post = Post::create([
            'user_id' => $user->id,
            'content' => $request->content,
            'image' => $filePath,
            'youtube_link' => $request->youtube_link,
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post
        ], 201);
    }


    public function getPosts()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $posts = Post::orderBy('created_at', 'desc')->get();

        if ($posts->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No posts available',
                'posts' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Posts fetched successfully',
            'posts' => $posts
        ]);
    }


}
