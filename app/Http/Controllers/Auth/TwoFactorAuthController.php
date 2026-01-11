<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\services;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TwoFactorAuthController extends Controller
{
    public function showVerifyForm()
    {
        return view('livewire.auth.2fa-verify');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|string']);

        $user = $request->user();
        $otpService = app(services::class);

        if ($otpService->verifyOTP($user, $request->otp)) {
            session(['2fa_verified' => true]);
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        Alert::warning('Invalid or expired OTP');
        return back()->withErrors(['otp' => 'Invalid or expired OTP']);
    }

    public function resend(Request $request)
    {
        $user = $request->user();
        $otpService = app(services::class);
        $otpService->sendOTP($user);
        Alert::success('A new OTP has been sent to your email.');

        return back()->with('status', 'A new OTP has been sent to your email.');
    }
}
