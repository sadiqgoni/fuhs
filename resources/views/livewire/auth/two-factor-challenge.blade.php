@include('layouts.styles')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Two Factor Challenge') }}</div>

                    <div class="card-body">
                        {{ __('Please enter you authentication code to login.') }}

                        <form method="POST" action="{{route('two-factor.login.store')}}">
                            @csrf

                            <div class="row mb-3">
                                <label for="code" class="col-md-4 col-form-label text-md-end">{{ __('code') }}</label>

                                <div class="col-md-6">
                                    <input id="code" type="password" class="form-control @error('code') is-invalid @enderror" name="code" required autocomplete="current-password">

                                    @error('code')
                                    <span class="invalid-feedback is-invalid" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Submit') }}
                                    </button>


                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
