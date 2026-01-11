@extends('components.layouts.app')
@section('body')
    <div>
        {{-- Be like water. --}}
        <div class="row">
            <div class="col-12 col-lg-6 offset-lg-3">
                <div class="container">
                    @if(auth()->user()->password_changed == null)
                        <div>
                            <fieldset style="border: none !important;background: none">
                                <legend style="background: none;text-align: center"><i class="fa fa-check-circle fa-3x text-success"></i> </legend>
                                Your email has been verified successfully.
                                Please change your password to proceed
                            </fieldset>

                        </div>
                    @endif

                    <div class="card" style="padding: 20px">

                        <form method="post" action="{{route('staff.password')}}">
                            @csrf

                            <div class="form-group">
                                @error('old_password')
                                <strong class="text-danger">{{$message}}</strong>
                                @enderror
                                <input type="password" id="password" class="form-control p-2 @error('old_password') is-invalid @enderror" name="old_password" wire:model="old_password" value="{{old('old_password')}}"  placeholder="Enter current password" >
                            </div>

                            <div class="form-group">
                                @error('password')
                                <strong class="text-danger">{{$message}}</strong>
                                @enderror
                                <input type="password" wire:model="password" class="form-control py-2 @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter new password"  >
                            </div>
                            <div class="form-group">
                                @error('confirm_password')
                                <strong class="text-danger">{{$message}}</strong>
                                @enderror
                                <input type="password" class="form-control py-2 @error('confirm_password') is-invalid @enderror" id="confirm_password" name="confirm_password" wire:model="confirm_password"  placeholder="Confirm Password" >
                            </div>
                            <button type="submit" class="btn save_btn float-right" wire:loading.attr="disabled">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



    </div>

@endsection
@section('title')
    Change Password
@endsection
@section('page_title')
    Change Password
@endsection
