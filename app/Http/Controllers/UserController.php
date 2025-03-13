<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('user-management', compact('users'));
    }

    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'phone' => 'required|string|max:15|unique:users',
    //         'password' => 'required|string|min:6|confirmed',
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     return response()->json([
    //         'message' => 'User created successfully',
    //         'user' => $user
    //     ], 201);
    // }


    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Sirf teacher ya student delete honge
        if (!in_array($user->role, ['teacher', 'student'])) {
            return response()->json(['success' => false, 'message' => 'You cannot delete this user.'], 403);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully!']);
    }

    public function changeStatus(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $user->status = $request->status;
        $user->save();

        return response()->json(['success' => true, 'message' => 'User status updated successfully!']);
    }


}
