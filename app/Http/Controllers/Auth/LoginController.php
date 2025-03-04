<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCode;
use App\Mail\SendOTP;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout successful',
            'redirect' => route('sign-in')
        ], 200);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = filter_var($request->email, FILTER_VALIDATE_EMAIL)
            ? ['email' => $request->email, 'password' => $request->password]
            : ['mobile_number' => $request->email, 'password' => $request->password]; // ✅ "phone" ki jagah "mobile_number"

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // ❌ Sirf admin login kar sake
            if ($user->role !== 'admin') {
                Auth::logout(); // ✅ Non-admin user ko logout karo
                return response()->json(['message' => 'Only admins can log in'], 403);
            }

            return response()->json([
                'message' => 'Login Successful',
                'redirect' => route('dashboard'),
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }


    public function apilogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_or_phone' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $loginField = filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile_number';
        $user = User::where($loginField, strtolower($request->email_or_phone))->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->mobile_number,
                'school_college_name' => $user->school_college_name,
                'city' => $user->city,
                'full_address' => $user->full_address,
            ]
        ], 200);
    }


}
