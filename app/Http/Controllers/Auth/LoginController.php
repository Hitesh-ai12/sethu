<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCode;
use App\Mail\SendOTP;


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
            : ['phone' => $request->email, 'password' => $request->password];

        if (Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Login Successful',
                'redirect' => route('dashboard'),
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    //apis
    public function appLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = filter_var($request->email, FILTER_VALIDATE_EMAIL)
            ? ['email' => $request->email, 'password' => $request->password]
            : ['phone' => $request->email, 'password' => $request->password];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $otp = rand(1000, 9999);

            $user->update([
                'otp' => $otp,
                'otp_expires_at' => now()->addSeconds(45),
            ]);

            // Example: Mail::to($user->email)->send(new SendOTP($otp));

            return response()->json([
                'message' => 'OTP sent to your email/phone.',
                'user_id' => $user->id,
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }


    public function verifyOTP(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|digits:4',
        ]);

        $user = User::find($request->user_id);

        if (!$user || $user->otp != $request->otp || now()->greaterThan($user->otp_expires_at)) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        $token = $user->createToken('app_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }

}
