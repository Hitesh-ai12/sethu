<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FollowController extends Controller
{
    public function followUser($id)
    {
        $user = Auth::user();
        $followedUser = User::find($id);

        if (!$followedUser) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        if ($user->id == $id) {
            return response()->json(['success' => false, 'message' => 'You cannot follow yourself'], 400);
        }

        if ($user->follow($id)) {
            return response()->json(['success' => true, 'message' => 'User followed successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Already following this user']);
    }

    public function unfollowUser($id)
    {
        $user = Auth::user();
        $unfollowedUser = User::find($id);

        if (!$unfollowedUser) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        if ($user->unfollow($id)) {
            return response()->json(['success' => true, 'message' => 'User unfollowed successfully']);
        }

        return response()->json(['success' => false, 'message' => 'You are not following this user']);
    }

    public function followBack($id)
    {
        $user = Auth::user();
        $followBackUser = User::find($id);

        if (!$followBackUser) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        if ($user->isFollowedBy($id)) {
            if ($user->follow($id)) {
                return response()->json(['success' => true, 'message' => 'You followed back successfully']);
            }
        }

        return response()->json(['success' => false, 'message' => 'User is not following you']);
    }

    public function getFollowers()
    {
        $user = Auth::user();
        $followers = User::whereIn('id', $user->followers ?? [])->get();
        return response()->json(['success' => true, 'followers' => $followers]);
    }

    public function getFollowing()
    {
        $user = Auth::user();
        $following = User::whereIn('id', $user->following ?? [])->get();
        return response()->json(['success' => true, 'following' => $following]);
    }
}
