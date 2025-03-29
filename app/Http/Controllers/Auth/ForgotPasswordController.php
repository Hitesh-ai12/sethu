<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;

class ForgotPasswordController extends Controller
{
    public function sendResetOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_or_phone' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $user = User::where('email', $request->email_or_phone)
                    ->orWhere('mobile_number', $request->email_or_phone)
                    ->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $otp = mt_rand(1000, 9999);
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        if (filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL)) {
            Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Your Password Reset OTP');
            });
            return response()->json(['message' => 'OTP sent to your email'], 200);
        } else {
            $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

            try {
                $twilio->messages->create(
                    $user->mobile_number,
                    [
                        "from" => env('TWILIO_PHONE_NUMBER'),
                        "body" => "Your OTP is: $otp"
                    ]
                );

                return response()->json(['message' => 'OTP sent to your phone'], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Failed to send OTP', 'error' => $e->getMessage()], 500);
            }
        }
    }

    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_or_phone' => 'required',
            'otp' => 'required|digits:4'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $user = User::where(function ($query) use ($request) {
                        $query->where('email', $request->email_or_phone)
                              ->orWhere('mobile_number', $request->email_or_phone);
                    })
                    ->where('otp', $request->otp)
                    ->first();

        if (!$user || Carbon::now()->gt($user->otp_expires_at)) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        $token = $user->createToken('PasswordResetToken')->plainTextToken;

        return response()->json([
            'message' => 'OTP verified successfully',
            'token' => $token
        ], 200);
    }


    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_or_phone' => 'required',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        // Find the user by email or phone
        $user = User::where('email', $request->email_or_phone)
                    ->orWhere('mobile_number', $request->email_or_phone)
                    ->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password reset successfully'], 200);
    }

    // public function resetPassword(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'password' => 'required|string|min:8|confirmed'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['message' => $validator->errors()->first()], 422);
    //     }

    //     $user = auth()->user();

    //     if (!$user) {
    //         return response()->json(['message' => 'Unauthorized or session expired'], 401);
    //     }

    //     $user->password = Hash::make($request->password);
    //     $user->save();

    //     return response()->json(['message' => 'Password reset successfully'], 200);
    // }
}
