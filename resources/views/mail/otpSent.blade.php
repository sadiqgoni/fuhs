<x-mail::message>
<h1>Your OTP Code</h1>
<p>Your one-time password is: <strong>{{ $otp }}</strong></p>
<p>This code will expire in 5 minutes.</p>

Thanks,<br>
{{ app_settings()->name }}
</x-mail::message>
