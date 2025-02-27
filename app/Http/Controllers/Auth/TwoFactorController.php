<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    public function show2FAForm()
    {
        return view('auth.two-factor-authentication');
    }

    public function verify2FA(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric',
        ]);

        if ($request->code == session('2fa_code')) {
            session()->forget('2fa_code');
            return redirect('/dashboard');
        }

        return back()->withErrors(['code' => 'Invalid authentication code']);
    }
}
