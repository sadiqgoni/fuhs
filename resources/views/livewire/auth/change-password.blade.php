<div>
    {{-- Be like water. --}}
    <div class="row">
        <div class="col-12 col-lg-6 offset-lg-3">
            <div class="container">
                <div class="card" style="padding: 20px">

                    <form wire:submit="store" >
                        @csrf

                        <div class="form-group">
                            @error('old_password')
                            <strong class="text-danger">{{$message}}</strong>
                            @enderror
                            <div class="input-group">
                                <input type="password" id="old_password" class="form-control p-2 @error('old_password') is-invalid @enderror" name="username" wire:model="old_password" value="{{old('old_password')}}" placeholder="Enter current password" >
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('old_password', this)">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            @error('password')
                            <strong class="text-danger">{{$message}}</strong>
                            @enderror
                            <div class="input-group">
                                <input type="password" wire:model="password" class="form-control py-2 @error('password') is-invalid @enderror" id="new_password" name="password" placeholder="Enter new password" >
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password', this)">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            @error('confirm_password')
                            <strong class="text-danger">{{$message}}</strong>
                            @enderror
                            <div class="input-group">
                                <input type="password" class="form-control py-2 @error('confirm_password') is-invalid @enderror" id="confirm_password" name="confirm_password" wire:model="confirm_password" placeholder="Confirm Password" >
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password', this)">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <script>
                            function togglePassword(inputId, btn) {
                                const input = document.getElementById(inputId);
                                const icon = btn.querySelector('i');
                                if (input.type === "password") {
                                    input.type = "text";
                                    icon.classList.remove('fa-eye');
                                    icon.classList.add('fa-eye-slash');
                                } else {
                                    input.type = "password";
                                    icon.classList.remove('fa-eye-slash');
                                    icon.classList.add('fa-eye');
                                }
                            }
                        </script>
                        <button type="submit" class="btn save_btn float-right" wire:loading.attr="disabled">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @section('title')
        Change Password
    @endsection
    @section('page_title')
        Change Password
    @endsection
</div>
