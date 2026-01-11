<?php

namespace App;

use App\Mail\OTPSent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use PragmaRX\Google2FA\Google2FA;

class services
{
    protected $google2fa;
    protected $otpExpiresIn = 5; // minutes

    public function __construct(Google2FA $google2fa)
    {
        $this->google2fa = $google2fa;
    }

    public function generateOTP()
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function sendOTP($user)
    {
        $otp = $this->generateOTP();
        $expiresAt = Carbon::now()->addMinutes($this->otpExpiresIn);

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => $expiresAt,
        ]);

        // Send email with OTP
        Mail::to($user->email)->send(new OTPSent($otp));
    }

    public function verifyOTP($user, $otp)
    {
        if ($user->otp !== $otp) {
            return false;
        }

        if (Carbon::now()->gt($user->otp_expires_at)) {
            return false;
        }

        // Clear OTP after verification
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        return true;
    }

}
