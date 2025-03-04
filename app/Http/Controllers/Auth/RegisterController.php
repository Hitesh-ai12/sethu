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
use Illuminate\Support\Facades\Log; // Import this

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
            'role' => 'user',
        ]);

        return response()->json([
            'message' => 'User registered successfully!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'mobile_number' => $user->mobile_number,
                'school_college_name' => $user->school_college_name,
                'city' => $user->city,
                'full_address' => $user->full_address,
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
            \Log::info('Request Data:', $request->all()); // Debugging ke liye log

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

            // Delete old skills and insert new ones as a single row
            UserSkill::updateOrCreate(
                ['user_id' => $user->id],
                ['skill_name' => implode(',', $request->skills)] // Store as comma-separated string
            );

            return response()->json(['message' => 'Skills updated successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Error in personalizeSkills: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong', 'error' => $e->getMessage()], 500);
        }
    }



}
