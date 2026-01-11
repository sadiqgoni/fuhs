@extends('components.layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Two-Factor Authentication Settings') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <p>Two-factor authentication (2FA) adds an additional layer of security to your account by requiring more than just a password to log in.</p>

                        @if(auth()->user()->is_2fa_enabled)
                            <div class="alert alert-success">
                                Two-factor authentication is currently <strong>enabled</strong> on your account.
                            </div>

                            <form method="POST" action="{{ route('2fa.disable') }}">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    {{ __('Disable 2FA') }}
                                </button>
                            </form>
                        @else
                            <div class="alert alert-warning">
                                Two-factor authentication is currently <strong>disabled</strong> on your account.
                            </div>

                            <form method="POST" action="{{ route('2fa.enable') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Enable 2FA') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
