@if($view == true)
    @php
        $employee=$employeeInfo
    @endphp
    <fieldset>
        <legend>
            <button class="btn btn-sm edit_btn" type="button" wire:click.prevent="edit_emp({{$employee->id}})">Edit <i class="fa fa-eye"></i></button>
            <a href="{{route('employee.profile')}}" class="btn btn-sm close_btn" type="button" >Close <i class="fa fa-times"></i></a>
            <input type="text" name="search" wire:model="search_employee" wire:keydown.enter="searchEmployee" placeholder="Search with Payroll NO." class="form-control-sm" style="margin-left: 15% !important;">

            @php
                $user=\App\Models\EmployeeProfile::get()->count();
                $next=$employee->id+1;
                $previous=$employee->id-1
            @endphp
            <button></button>
            @if($previous >= 1)
                <button class="btn btn-primary btn-sm"  wire:click.prevent="view_emp({{$previous}})" > << Previous </button>
            @endif
            @if($next <= $user)
                <button class="btn btn-primary btn-sm" wire:click.prevent="view_emp({{$next}})" >Next >></button>
            @endif
        </legend>
        <div class="row">
            <div class="col-12 col-lg-2">
                @if(!is_null($employee->profile_picture))
                    <img src="{{asset('storage/'.$employee->profile_picture)}}" class="d-block" style=" width: 75px;height:68px;border-radius: 50%;">

                @else
                    <img src="{{url('assets/img/user.jpg')}}" class="d-block" style=" width: 75px;height:68px;border-radius: 50%;">

                @endif
            </div>
{{--            <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">--}}
{{--                <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">--}}
{{--                    <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="col-12 col-lg-10">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="input-group form-group">

                            <div class="input-group-prepend"><span class="input-group-text">Staff Number</span></div>
                            <input class="form-control" value="{{$employee->staff_number}}" {{$disabled}} type="text">
                            <div class="input-group-append"></div>
                        </div>

                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="input-group form-group">

                            <div class="input-group-prepend"><span class="input-group-text">Payroll Number</span></div>
                            <input type="text" class="form-control"  value="{{$employee->payroll_number}}" {{$disabled}}>

                            <div class="input-group-append"></div>
                        </div>

                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="input-group form-group">

                            <div class="input-group-prepend"><span class="input-group-text">Full Name</span></div>
                            <input class="form-control" value="{{$employee->full_name}}" {{$disabled}} type="text">
                            <div class="input-group-append"></div>
                        </div>

                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Status</span></div>
                            <input type="text" class="form-control"  value="{{emp_status($employee->status)}}" {{$disabled}}>

                            <div class="input-group-append"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="vertical-tas mt-3">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a wire:ignore class="nav-link active" data-toggle="tab" href="#home-v" role="tab" aria-controls="home">Personal Data</a>
            </li>
            <li class="nav-item">
                <a wire:ignore class="nav-link" data-toggle="tab" href="#profile-v" role="tab" aria-controls="profile">Employment Data</a>
            </li>
            <li class="nav-item">
                <a wire:ignore class="nav-link" data-toggle="tab" href="#messages-v" role="tab" aria-controls="messages">Salary & Pension Data</a>
            </li>
            <li class="nav-item">
                <a wire:ignore class="nav-link" data-toggle="tab" href="#settings-v" role="tab" aria-controls="settings">Next of Kin</a>
            </li>
        </ul>
        <div class="tab-content">
            <div wire:ignore.self class="tab-pane active" id="home-v" role="tabpanel">
                <div class="sv-tab-panel">
                    <h3>Personal Data</h3>

                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Date of Birth</span></div>
                                <input type="date" class="form-control"  value="{{$employee->date_of_birth}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Gender</span></div>
                                <input type="text" class="form-control"  value="{{gender($employee->gender)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Tribe</span></div>
                                <input type="text" class="form-control"  value="{{tribe($employee->tribe)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Religion</span></div>
                                <input type="text" class="form-control"  value="{{religion($employee->religion)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Nationality</span></div>
                                <input type="text" class="form-control"  value="{{nationality($employee->nationality)}}" {{$disabled}}>


                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Marital Status</span></div>
                                <input type="text" class="form-control"  value="{{marital_status($employee->marital_status)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">State of Origin</span></div>
                                <input type="text" class="form-control"  value="{{state($employee->state_of_origin)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">LGA</span></div>
                                <input type="text" class="form-control"  value="{{lga($employee->local_government)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Phone Number</span></div>
                                <input type="text" class="form-control"  value="{{$employee->phone_number}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Email</span></div>
                                <input type="text" class="form-control"  value="{{$employee->email}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-12">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">WhatsApp Number</span></div>
                                <input type="text" class="form-control"  value="{{$employee->whatsapp_number}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div wire:ignore.self class="tab-pane" id="profile-v" role="tabpanel">
                <div class="sv-tab-panel">
                    <h3>Employment Data</h3>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Employment Type</span></div>
                                <input type="text" class="form-control"  value="{{emp_type($employee->employment_type)}}" {{$disabled}}>



                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Staff Category</span></div>
                                <input type="text" class="form-control"  value="{{staff_cat($employee->staff_category)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Unit</span></div>
                                <input type="text" class="form-control"  value="{{unit($employee->unit)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Department</span></div>
                                <input type="text" class="form-control"  value="{{dept($employee->department)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Rank</span></div>
                                <input type="text" class="form-control"  value="{{rank($employee->rank)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Post Held</span></div>
                                <input type="text" class="form-control"  value="{{$employee->post_held}}" {{$disabled}}>
                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Date of FA</span></div>
                                <input type="date" class="form-control"  value="{{$employee->date_of_first_appointment}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Date of LP</span></div>
                                <input type="date" class="form-control"  value="{{$employee->date_of_last_appointment}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Date of Retirement</span></div>
                                <input type="date" class="form-control"  value="{{$employee->date_of_retirement}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Contract Termination Date</span></div>
                                <input type="date" class="form-control"  value="{{$employee->contract_termination_date}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Staff Union</span></div>
                                <input type="text" class="form-control"  value="{{staff_union($employee->staff_union)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <div wire:ignore.self class="tab-pane" id="messages-v" role="tabpanel">
                <div class="sv-tab-panel">
                    <h3>Salary & Pension Data</h3>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Pension Pin</span></div>
                                <input type="text" class="form-control"  value="{{$employee->pension_pin}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">PFA Name</span></div>
                                <input type="text" class="form-control"  value="{{pfa($employee->pfa_name)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Salary Structure</span></div>
                                <input type="text" class="form-control"  value="{{ss($employee->salary_structure)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Grade Level</span></div>
                                <input type="text" class="form-control"  value="{{$employee->grade_level}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Step</span></div>
                                <input type="text" class="form-control"  value="{{$employee->step}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Account Number</span></div>
                                <input type="text" class="form-control"  value="{{$employee->account_number}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Bank Code</span></div>
                                <input type="text" class="form-control"  value="{{$employee->bank_code}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Bank Name</span></div>
                                <input type="text" class="form-control"  value="{{$employee->bank_name}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-6">
                            @error('bvn')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Bvn <sup></sup></span></div>
                                <input class="form-control @error('bvn') is-invalid @enderror" value="{{$employee->bvn}}" {{$disabled}}  type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('tax_id')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Tax Id <sup></sup></span></div>
                                <input class="form-control value="{{$employee->tax_id}}" {{$disabled}}  type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div wire:ignore.self class="tab-pane" id="settings-v" role="tabpanel">
                <div class="sv-tab-panel">
                    <h3>Next of Kin</h3>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Name</span></div>
                                <input type="text" class="form-control"  value="{{$employee->name_of_next_of_kin}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Phone Number</span></div>
                                <input type="text" class="form-control"  value="{{$employee->next_of_kin_phone_number}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Relationship</span></div>
                                <input type="text" class="form-control"  value="{{relationships($employee->relationship)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Address</span></div>
                                <input type="text" class="form-control"  value="{{$employee->address}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>


@endif
