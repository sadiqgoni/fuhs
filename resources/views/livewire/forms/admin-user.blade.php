<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <style>
        svg {
            display: none;
        }

        input[type='checkbox'] {
            width: 18px !important;
            height: 18px !important;
            border-radius: 50%;
        }
    </style>
    <div wire:loading
        style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
        </div>
    </div>
    @if($record == true)
        <div class="">
            <div>
                <label for="">Search</label>
                <input type="text" class="form-control-sm" wire:model.live="search" placeholder="search">
                <label for="">Show</label>
                <select name="" id="" class="form-control-sm" wire:model.live="perpage">
                    <option value="25" selected>25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="250">250</option>
                    <option value="500">500</option>
                </select>
                <label for="">Role</label>

                <select name="" id="" class="form-control-sm" wire:model.live="role">
                    <option value="0" selected>Admin</option>
                    <option value="1">Staff</option>

                </select>
                <button class="create btn float-right" wire:click.prevent="create_user()">Add User</button>
            </div>
            <div class="table-responsive">
                <table class="table mt-2 table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Sn</th>
                            <th>NAME</th>
                            <th>USERNAME</th>
                            <th>EMAIL</th>
                            {{-- <th>STATUS</th>--}}
                            <th>ROLE</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            @if($user->id != 1)
                                <tr>
                                    <th>{{$loop->iteration}}</th>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->username}}</td>
                                    <td>{{$user->email}}</td>
                                    {{-- <td>{{user_status($user->status)}}</td>--}}
                                    <td>@if($user->role == 1)
                                        Staff
                                    @else
                                            Admin
                                        @endif</td>
                                    <td><button class="btn btn-sm edit_btn"
                                            wire:click.prevent="edit_user({{$user->id}})">Edit</button></td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4">no record</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    @endif
    @if($create == true)
        <div class="">
            <form wire:submit.prevent="store()">
                <fieldset>

                    <legend>
                        <h6>Add Admin User</h6>
                    </legend>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="">Name <small class="text-danger">@error('name') {{$message}}
                                @enderror</small></label>
                                <input type="text" class="form-control" wire:model.defer="name" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="">Email <small class="text-danger">@error('email') {{$message}}
                                @enderror</small></label>
                                <input type="text" class="form-control" wire:model.defer="email" placeholder="Enter Email">
                            </div>
                        </div>
                        <div class="col-12 col-lg-12">
                            <div class="form-group">
                                <label for="">Username <small class="text-danger">@error('username') {{$message}}
                                @enderror</small></label>
                                <input type="text" class="form-control" wire:model.defer="username"
                                    placeholder="Enter Username">
                            </div>
                        </div>
                        <div class="col-12 col-lg-12">
                            <div class="form-group">
                                <hr>
                                <label for="">Permissions <small class="text-danger">@error('permissions') {{$message}}
                                @enderror</small></label>

                                <div class="row">
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="1">
                                        <label for="">Employee Settings</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="21">
                                        <label for="">Allowance</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="22">
                                        <label for="">Deduction</label>
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="23">
                                        <label for="">Salary Structure</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="24">
                                        <label for="">Allowance Template</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="25">
                                        <label for="">Deduction Template</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="26">
                                        <label for="">Salary Template</label>
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="31">
                                        <label for="">Monthly Update</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="32">
                                        <label for="">Group Update</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="33">
                                        <label for="">Annual Increment</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="34">
                                        <label for="">Loan Deduction</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="35">
                                        <label for="">Salary Posting</label>
                                    </div>


                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="41">
                                        <label for="">Group Report</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="42">
                                        <label for="">Individual Report</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="43">
                                        <label for="">Nominal Roll</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="44">
                                        <label for="">Annual Inc. History</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="45">
                                        <label for="">Loan Dedc. History</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="46">
                                        <label for="">Retirement List</label>
                                    </div>


                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="51">
                                        <label for="">Backup History</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="52">
                                        <label for="">Restor History</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="53">
                                        <label for="">Audit Log</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="54">
                                        <label for="">Analytics</label>
                                    </div>


                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="61">
                                        <label for="">Backup</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="62">
                                        <label for="">Restore</label>
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="63">
                                        <label for="">App Setting</label>
                                    </div>


                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="76">
                                        <label for="">Staff Promotion</label>
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="77">
                                        <label for="">Reports Repository</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="78">
                                        <label for="">Contract Termination List</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="79">
                                        <label for="">Auto Restore</label>
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="71">
                                        <label for="">Can Add</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="72">
                                        <label for="">Can Update</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="73">
                                        <label for="">Can Export</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="74">
                                        <label for="">Can send Email</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-auto">
                                        <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                            value="75">
                                        <label for="">Can Generate Report</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="mt-3 text-center">
                    <button class="save_btn my-1 my-md-0 btn" type="submit">Submit</button>
                    <button class="close_btn my-1 my-md-0 btn" wire:click.prevent="close">Close</button>
                </div>
            </form>
        </div>
    @endif
    @if($edit == true)
        <div class="">
            <form wire:submit.prevent="update({{$ids}})">
                <fieldset>
                    <legend>
                        <h6>Add Admin User</h6>
                    </legend>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="">Name <small class="text-danger">@error('name') {{$message}}
                                @enderror</small></label>
                                <input type="text" class="form-control" wire:model.defer="name" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="">Email <small class="text-danger">@error('email') {{$message}}
                                @enderror</small></label>
                                <input type="text" class="form-control" wire:model.defer="email" placeholder="Enter Email">
                            </div>
                        </div>
                        <div class="col-12 col-lg-12">
                            <div class="form-group">
                                <label for="">Username <small class="text-danger">@error('username') {{$message}}
                                @enderror</small></label>
                                <input type="text" class="form-control" wire:model.defer="username"
                                    placeholder="Enter Username">
                            </div>
                        </div>
                        @if($userInfo->role != 1)
                            <div class="col-12 col-lg-12">
                                <div class="form-group">
                                    <hr>
                                    <label for="">Permissions <small class="text-danger">@error('permissions') {{$message}}
                                    @enderror</small></label>

                                    <div class="row">
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="1">
                                            <label for="">Employee Settings</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="21">
                                            <label for="">Allowance</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="22">
                                            <label for="">Deduction</label>
                                        </div>

                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="23">
                                            <label for="">Salary Structure</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="24">
                                            <label for="">Allowance Template</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="25">
                                            <label for="">Deduction Template</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="26">
                                            <label for="">Salary Template</label>
                                        </div>

                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="31">
                                            <label for="">Monthly Update</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="32">
                                            <label for="">Group Update</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="33">
                                            <label for="">Annual Increment</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="34">
                                            <label for="">Loan Deduction</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="35">
                                            <label for="">Salary Posting</label>
                                        </div>


                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="41">
                                            <label for="">Group Report</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="42">
                                            <label for="">Individual Report</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="43">
                                            <label for="">Nominal Roll</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="44">
                                            <label for="">Annual Inc. History</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="45">
                                            <label for="">Loan Dedc. History</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="46">
                                            <label for="">Retirement List</label>
                                        </div>


                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="51">
                                            <label for="">Backup History</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="52">
                                            <label for="">Restore History</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="53">
                                            <label for="">Audit Log</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="54">
                                            <label for="">Analytics</label>
                                        </div>


                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="61">
                                            <label for="">Backup</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="62">
                                            <label for="">Restore</label>
                                        </div>

                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="63">
                                            <label for="">App Setting</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="76">
                                            <label for="">Staff Promotion</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="77">
                                            <label for="">Reports Repository</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="78">
                                            <label for="">Contract Termination List</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="79">
                                            <label for="">Auto Restore</label>
                                        </div>

                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="71">
                                            <label for="">Can Add</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="72">
                                            <label for="">Can Update</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="73">
                                            <label for="">Can Export</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="74">
                                            <label for="">Can send Email</label>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-auto">
                                            <input type="checkbox" class="form-control-sm" wire:model.defer="permissions"
                                                value="75">
                                            <label for="">Can Generate Report</label>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        @endif
                    </div>
                </fieldset>
                <div class="mt-3 text-center">
                    <button class="save_btn my-1 my-md-0 btn" type="submit">Update</button>
                    <button class="reset_btn my-1 my-md-0 btn" wire:click.prevent="password()">Reset Password</button>
                    <button class="close_btn my-1 my-md-0 btn" wire:click.prevent="close">Close</button>
                </div>
            </form>
        </div>

    @endif

    @if($reset_password_mode == true)
        <div class="card mt-4">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">Reset Password for {{ $name }} ({{ $username }})</h5>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="saveNewPassword">
                    <div class="form-group">
                        <label for="new_password">New Password <small class="text-danger">@error('new_password')
                        {{ $message }} @enderror</small></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password_input"
                                wire:model.defer="new_password" placeholder="Enter new password">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="const p = document.getElementById('new_password_input'); const i = this.querySelector('i'); if(p.type==='password'){p.type='text';i.classList.remove('fa-eye');i.classList.add('fa-eye-slash');}else{p.type='password';i.classList.remove('fa-eye-slash');i.classList.add('fa-eye');}">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">Click the eye icon to view the password.</small>
                    </div>

                    <div class="mt-3 text-center">
                        <button class="save_btn my-1 my-md-0 btn" type="submit">Update Password</button>
                        <button class="close_btn my-1 my-md-0 btn" wire:click.prevent="closePasswordReset">Close</button>
                    </div>
                </form>
            </div>
        </div>
    @endif


    @section('title')
        Manage Admin User
    @endsection
    @section('page_title')
        Security / User Account
    @endsection
</div>