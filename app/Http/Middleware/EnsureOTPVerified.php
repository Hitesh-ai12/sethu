<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class EnsureOTPVerified
{
    // public function handle(Request $request, Closure $next)
    // {
    //     \Log::info('Session Data:', Session::all());

    //     // ✅ Prevent infinite loop - If already on OTP page, don't redirect
    //     if ($request->routeIs('two-factor')) {
    //         return $next($request);
    //     }
    //     // Check if user is authenticated and OTP is verified
    //     if (!Session::has('otp_verified')) {
    //         return redirect()->route('two-factor')->with('error', 'Please verify OTP first.');
    //     }

    //     return $next($request);
    // }
}
