<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\UserSkill;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'school_college_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'mobile_number' => 'required|digits:10|unique:users,mobile_number',
            'full_address' => 'nullable|string|max:500',
            'role' => 'required|string|in:teacher,admin,student'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $hashedPassword = Hash::make($request->password);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $hashedPassword,
            'school_college_name' => $request->school_college_name,
            'city' => $request->city,
            'mobile_number' => $request->mobile_number,
            'full_address' => $request->full_address,
            'role' => $request->role,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully!',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'mobile_number' => $user->mobile_number,
                'school_college_name' => $user->school_college_name,
                'city' => $user->city,
                'full_address' => $user->full_address,
                'role' => $user->role,
            ]
        ], 201);
    }


    public function changeCity(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'city' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized or session expired'], 401);
        }

        $user->city = $request->city;
        $user->save();

        return response()->json(['message' => 'City updated successfully'], 200);
    }

    public function personalizeSkills(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'skills' => 'required|array',
                'skills.*' => 'string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 422);
            }

            $user = auth()->user();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized or session expired'], 401);
            }

            UserSkill::updateOrCreate(
                ['user_id' => $user->id],
                ['skill_name' => implode(',', $request->skills)]
            );

            return response()->json(['message' => 'Skills updated successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Error in personalizeSkills: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong', 'error' => $e->getMessage()], 500);
        }
    }


    public function updateProfile(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'gender' => 'nullable|string|in:male,female,other',
            'dob' => 'nullable|date',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized or session expired'], 401);
        }

        $updateFields = [];

        if ($request->has('name')) {
            $updateFields['name'] = $request->name;
        }
        if ($request->has('nickname')) {
            $updateFields['nickname'] = $request->nickname;
        }
        if ($request->has('gender')) {
            $updateFields['gender'] = $request->gender;
        }
        if ($request->has('dob')) {
            $updateFields['dob'] = $request->dob;
        }


        if ($request->hasFile('profile_image')) {

            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }


            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $updateFields['profile_image'] = $imagePath;
        }

        if (!empty($updateFields)) {
            $user->update($updateFields);
        }

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'nickname' => $user->nickname,
                'gender' => $user->gender,
                'dob' => $user->dob,
                'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
            ]
        ], 200);
    }

    public function getProfile(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized or session expired'], 401);
        }

        return response()->json([
            'message' => 'Profile fetched successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'nickname' => $user->nickname,
                'gender' => $user->gender,
                'dob' => $user->dob,
                'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
                'email' => $user->email,
                'mobile_number' => $user->mobile_number,
                'school_college_name' => $user->school_college_name,
                'city' => $user->city,
                'full_address' => $user->full_address,
                'role' => $user->role,
            ]
        ], 200);
    }

}
