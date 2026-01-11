<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmployeePasskey;
use App\services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class TwoFASettingsController extends Controller
{
    public function showSettingsForm()
    {
        return view('livewire.auth.2fa-settings');
    }

    public function enable(Request $request)
    {
        $user = $request->user();
        $passkeys = EmployeePasskey::where('employee_id', Auth::id())->get();
        foreach ($passkeys as $pass) {
            $pass->delete();
        }
        auth()->user()->passkey = null;
        auth()->user()->verify = null;
        auth()->user()->save();
        $user->update([
            'is_2fa_enabled' => true
        ]);

        $otpService = app(services::class);
        $otpService->sendOTP($user);
        Alert::success('2FA has been enabled. Please verify your OTP.');

        return redirect()->route('2fa.verify')->with('status', '2FA has been enabled. Please verify your OTP.');
    }

    public function disable(Request $request)
    {
        $request->user()->update([
            'is_2fa_enabled' => false,
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        return back()->with('status', '2FA has been disabled.');
    }
}
