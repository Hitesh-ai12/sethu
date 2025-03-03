<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle(Request $request, Closure $next): Response
    {

        //  Check if user is logged in
        if (!Auth::check()) {
            // 🔹 If not logged in, redirect to login page

            return redirect()->route('sign-in')->with('error', 'Please login to access this page.');
        }
        return $next($request);
    }
}
