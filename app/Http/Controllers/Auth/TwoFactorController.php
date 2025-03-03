<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session; // ✅ Correct Import
use App\Models\User;

class TwoFactorController extends Controller
{
    public function show2FAForm()
    {
        if (!Session::has('2fa_user_id')) {
            return redirect()->route('sign-in')->with('error', 'Unauthorized access!');
        }

        return view('auth.two-factor');
    }

    public function send2FACode(User $user)
    {
        $otp = rand(100000, 999999);

        Session::put('2fa_code', $otp);
        Session::put('2fa_user_id', $user->id);

        // ✅ Send OTP via Email
        Mail::raw("Your OTP Code is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your Two-Factor Authentication Code');
        });
    }

    public function verify2FA(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|numeric',
        ]);

        if ($request->two_factor_code == Session::get('2fa_code')) {
            $user = User::find(Session::get('2fa_user_id'));

            if (!$user) {
                return redirect()->route('sign-in')->with('error', 'User not found.');
            }

            // ✅ Log in user
            Auth::login($user);

            // ✅ Clear OTP session

            Session::forget(['2fa_code', '2fa_user_id']);
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['two_factor_code' => 'Invalid authentication code']);
    }
}
