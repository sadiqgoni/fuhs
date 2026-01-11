<?php
namespace App\Responses;

use Laravel\Fortify\Contracts\TwoFactorLoginResponse as FortifyTwoFactorLoginResponse;

class ForceTwoFactorResponse implements FortifyTwoFactorLoginResponse
{
    public function toResponse($request)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Two-factor authentication required',
                'requires_two_factor' => true,
                'redirect' => route('two-factor.login')
            ], 423);
        }

        return redirect()->intended(route('two-factor.login'));
    }
}
