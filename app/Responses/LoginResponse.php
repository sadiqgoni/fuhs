<?php
namespace App\Responses;

use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements TwoFactorLoginResponseContract
{
/**
* Create an HTTP response that represents the object.
*
* @param  \Illuminate\Http\Request  $request
* @return \Symfony\Component\HttpFoundation\Response
*/
public function toResponse($request): Response
{
// Customize the redirect after 2FA is triggered
// By default, Fortify will redirect to the 2FA challenge page
// You can add custom logic here if needed
if ($request->wantsJson()) {
return response()->json([
'message' => 'Two-factor authentication required',
'redirect' => url('/two-factor-challenge')
], 423);
}


// For web requests, redirect to the 2FA challenge page
return redirect("/two-factor-challenge");
}
}
