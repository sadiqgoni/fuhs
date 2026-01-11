<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin | Login | Page</title>
    <style>
        .container {
            width: 100%;
            max-width: 400px;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input {
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s ease-in-out;
            outline: none;
            color: #333;
        }

        input:focus {
            border-color: #555;
        }

        button {
            background-color: #3498db;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        button:hover {
            background-color: #2980b9;
        }



    </style>
    @include('layouts.styles')
</head>
<body style="overflow-x: hidden">
@include('layouts.partials.header')
@include('sweetalert::alert')

    <div class="container" style="margin: 100px auto">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Two-Factor Authentication') }}</div>

                    <div class="card-body">
                        <p>We've sent a one-time password (OTP) to your email address. Please enter it below to complete login.</p>

                        <form method="POST" action="{{ route('2fa.verify.submit') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="otp" class="col-md-4 col-form-label text-md-end">{{ __('OTP Code') }}</label>

                                <div class="col-md-6">
                                    <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror" name="otp" required autofocus>

                                    @error('otp')
                                    <span class="invalid-feedback" role="alert">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Verify') }}
                                    </button>


                                </div>
                            </div>
                        </form>
                        <form   action="{{ route('2fa.resend') }}" method="post" style="width: 100% !important">
                            @csrf
                            <button type="submit" class="btn  btn-link" style="width: fit-content !important;position: absolute;right: 10px;bottom: 30px">  {{ __('Resend OTP') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('layouts.partials.footer')
</body>
</html>
