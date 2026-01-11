<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div>
        <form wire:submit.prevent="update()">
            <fieldset>
                <legend><h6>Update Profile</h6></legend>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label for="">Name <small class="text-danger">@error('name') {{$message}} @enderror</small></label>
                        <div class="form-group">
                            <input type="text" class="form-control @error('name') is-inValid @enderror" wire:model.blur="name">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="">Email <small class="text-danger">@error('email') {{$message}} @enderror</small></label>
                        <div class="form-group">
                            <input type="text" class="form-control @error('email') is-inValid @enderror" wire:model.blur="email">
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="">Usename <small class="text-danger">@error('username') {{$message}} @enderror</small></label>
                        <div class="form-group">
                            <input type="text" class="form-control @error('username') is-inValid @enderror" wire:model.blur="username">
                        </div>
                    </div>
                </div>
            </fieldset>
            <div class="mt-3 text-right"><button class="btn save_btn">Update</button></div>
        </form>
    </div>
    <fieldset class="mt-4">
        @if(app_settings()->auth_type==2)
            @if(!\App\Models\EmployeePasskey::where('employee_id',\Illuminate\Support\Facades\Auth::id())->exists())
                <h5 class="text-dark">Add extra security layer to your account</h5>
            @else
                <h5 class="text-dark">Two factor authentication is on</h5>
            @endif
            <div class="text-center">
                @if(!\App\Models\EmployeePasskey::where('employee_id',\Illuminate\Support\Facades\Auth::id())->exists())
                    @if($view_passkey==false)
                        <button class="btn generate" wire:click.prevent="enter_password()" style="padding: 10px !important;width:250px !important;">Generate Secrete Keys</button>
                    @else
                        <form wire:submit.prevent="generate" style="margin: auto !important;text-align: center">
                            <label for="" >Enter your password to continue @error('password') <strong class="text-danger is-invalid">{{$message}}</strong>@enderror</label><br>
                            <input type="password" wire:model="password" style="padding: 10px !important;width:250px !important;" placeholder="Enter Password"><br>
                            <button class="btn save_btn mt-3">Continue</button>
                        </form>
                    @endif


                @else
                    @if($view_passkey==true)
                        <form wire:submit.prevent="turn_off()" style="margin: auto !important;text-align: center">
                            <label for="" >Enter your password to continue @error('password') <strong class="text-danger is-invalid">{{$message}}</strong>@enderror</label><br>
                            <input type="password" wire:model="password" style="padding: 10px !important;width:250px !important;" placeholder="Enter Password"><br>
                            <button class="btn save_btn mt-3">Continue</button>
                        </form>
                    @else
                        @if($view==false)
                            <button class="btn generate" wire:click.prevent="enter_password()" style="padding: 10px !important;width:250px !important;">Turn Off 2FA</button>

                        @endif
                    @endif
                @endif
                <ol>
                    @if($view==true)
                        <em class="text-danger">you turn on two factor authentication, copy or snap the generated keys for it will  be required anytime you try to login</em>
                        @foreach($passkeys as $pass)
                            <li style="list-style: none;font-size: 20px;" class="text-capitalize">{{$pass->rand_int}} - {{$pass->passkey}}</li>
                        @endforeach
                        {{--                    <button class="btn view">Print</button>--}}
                    @endif
                </ol>
            </div>
        @else
            <div class="text-center">
                <a href="{{ route('profile.2fa') }}" class="nav-link btn generate" style="padding: 10px;width: 250px !important;margin: auto!important;">
                    {{ __('2FA Authentication') }}
                </a>
            </div>
        @endif


    </fieldset>

    @section('title')
        Account Settings
    @endsection
    @section('page_title')
        Account Settings
    @endsection
</div>
