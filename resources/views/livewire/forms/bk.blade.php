<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    @push('styles')
        <style>
            svg:not(:root){
                display: none !important;
            }
            label {
                /*background-color: indigo;*/

            }
            .custom-file-input::-webkit-file-upload-button {
                visibility: hidden;
            }
            .custom-file-input::before {
                content: 'Select some files';
                display: inline-block;
                background: linear-gradient(top, #f9f9f9, #e3e3e3);
                border: 1px solid #999;
                border-radius: 3px;
                padding: 5px 8px;
                outline: none;
                white-space: nowrap;
                -webkit-user-select: none;
                cursor: pointer;
                text-shadow: 1px 1px #fff;
                font-weight: 700;
                font-size: 10pt;
            }
            .custom-file-input:hover::before {
                border-color: black;
            }
            .custom-file-input:active::before {
                background: -webkit-linear-gradient(top, #e3e3e3, #f9f9f9);
            }
            button{
                font-size: 12px !important;
            }

        </style>

    @endpush

    <div class="row">
        <div class="col-12">
            @if($record==true)
                <div class="row">
                    <div class="col">
                        <div>
                            <input type="text" class="form-control-sm" wire:model.live="search" placeholder="Search for Employee..">

                            <label for="">Filter By:</label>
                            <select name="" id="" class="form-control-sm" wire:model.live="filter_type">
                                <option value="">Employee Type</option>
                                @foreach(\App\Models\EmploymentType::all() as $emp_type)
                                    <option value="{{$emp_type->id}}">{{$emp_type->name}}</option>
                                @endforeach
                            </select>
                            <select name="" id="" class="form-control-sm" wire:model.live="filter_unit">
                                <option value="">Unit</option>
                                @foreach(\App\Models\Unit::get() as $unit_filter)
                                    <option value="{{$unit_filter->id}}">{{$unit_filter->name}}</option>
                                @endforeach
                            </select>
                            <select name="" id="" class="form-control-sm" wire:model.live="filter_dept">
                                <option value="">Department</option>
                                @foreach($depts as $dept)
                                    <option value="{{$dept->id}}">{{$dept->name}}</option>
                                @endforeach
                            </select>


                            <label for="">Show record per-page</label>
                            <select name="" id="" class="form-control-sm" wire:model.live="perpage">
                                <option value="50"></option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                                <option value="500">500</option>
                                <option value="1000">1000</option>
                            </select>
                            <button class="btn create btn-sm float-right" wire:click.prevent="create_emp()"><i class="fa fa-plus"></i> Add Employee </button>

                            {{--                            <form wire:submit="import" style="position: absolute;right: 25px;margin-top: -77px">--}}

                            {{--                                <input id="upload{{ $iteration }}"   type="file" class="  @error('importFile') is-invalid @enderror" wire:model.live="importFile">--}}
                            {{--                                <div class="input-group-append">--}}
                            {{--                                                <button type="submit" class="input-group-text btn btn-sm btn-info " id="">Import Excell<i class="fa fa-file-excel-o"></i></button>--}}
                            {{--                                            </div>--}}
                            {{--                                            @error('importFile')--}}
                            {{--                                            <div id="validationServer03Feedback" class="is-invalid text-danger">{{$message}}</div>--}}
                            {{--                                            @enderror--}}

                            {{--                                    @if($importing && !$importFinished)--}}
                            {{--                                        <div wire:poll="updateImportProgress" class="text-danger">Importing...please wait.</div>--}}
                            {{--                                    @endif--}}
                            {{--                                    @if($importFinished)--}}
                            {{--                                        <em class="text-success font-weight-bold">Finished Importing</em>--}}
                            {{--                                    @endif--}}


                            {{--                            </form>--}}
                        </div>


                        <table class="table table-bordered table-stripped">
                            <thead class="thead-light">
                            <tr>
                                <th>S/N</th>
                                <th>PF NUMBER</th>
                                <th>IPP NUMBER</th>
                                <th>STAFF NAME</th>
                                <th>UNIT</th>
                                <th>DEPARTMENT</th>
                                <th>ACTION</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($employees  as $employee)
                                <tr>
                                    <th>{{($employees->currentPage() - 1) * $employees->perpage() + $loop->index+1}}</th>
                                    <td>{{$employee->staff_number}}</td>
                                    <td>{{$employee->payroll_number}}</td>
                                    <td>{{$employee->full_name}}</td>
                                    <td>{{unit_name($employee->unit)}}</td>
                                    <td>{{dept($employee->department)}}</td>

                                    <td><button class="btn btn-sm btn-info" wire:click.prevent="view_emp({{$employee->id}})">View <i class="fa fa-eye"></i></button></td>
                                </tr>
                            @empty

                            @endforelse
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="5">{{$employees->links()}}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif
            @if($view == true)
                @php
                    $employee=$employeeInfo
                @endphp

                <div  class="p-3" style="">
                    <div class="row">
                        <div class="col-2 text-left">

                            @if(is_null($employee->profile_picture))
                                <img src="{{asset('storage/'.$employee->profile_picture)}}" class="d-block" style=" width: 75px;height:68px;border-radius: 50%;position: absolute;left: 0;top:-35px">

                            @else
                                <img src="{{url('assets/img/user.jpg')}}" class="d-block" style=" width: 75px;height:68px;border-radius: 50%;position: absolute;left: 0;top:-35px">

                            @endif

                        </div>

                        <div class="col-4 text-right mb-1">


                            <button class="btn btn-sm edit_btn" type="button" wire:click.prevent="edit_emp({{$employee->id}})">Edit <i class="fa fa-eye"></i></button>
                            <button class="btn btn-sm close_btn" type="button" wire:click.prevent="close">Close <i class="fa fa-times"></i></button>

                        </div>
                        <div class="col-6">
                            <input type="text" name="search" wire:model="search_employee" wire:keydown.enter="searchEmployee" placeholder="Search with IP NO." class="form-control-sm" style="margin-left: 15% !important;">

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

                        </div>

                    </div>
                    <div class="row">
                        <div class="col-4">

                            <div class="input-group form-group">

                                <div class="input-group-prepend"><span class="input-group-text">Full Name</span></div>
                                <input class="form-control" value="{{$employee->full_name}}" {{$disabled}} type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-4">

                            <div class="input-group form-group">

                                <div class="input-group-prepend"><span class="input-group-text">Staff Number</span></div>
                                <input class="form-control" value="{{$employee->staff_number}}" {{$disabled}} type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-4">

                            <div class="input-group form-group">

                                <div class="input-group-prepend"><span class="input-group-text">Payroll Number</span></div>
                                <input type="text" class="form-control"  value="{{$employee->payroll_number}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Employment Type</span></div>
                                <input type="text" class="form-control"  value="{{emp_type($employee->employment_type)}}" {{$disabled}}>



                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">

                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Staff Category</span></div>
                                <input type="text" class="form-control"  value="{{staff_cat($employee->staff_category)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">

                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Status</span></div>
                                <input type="text" class="form-control"  value="{{emp_status($employee->status)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">PFA Name</span></div>
                                <input type="text" class="form-control"  value="{{pfa($employee->pfa_name)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>

                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Pension Pin</span></div>
                                <input type="text" class="form-control"  value="{{$employee->pension_pin}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Unit</span></div>
                                <input type="text" class="form-control"  value="{{unit($employee->unit)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">

                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Department</span></div>
                                <input type="text" class="form-control"  value="{{dept($employee->department)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">

                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Rank</span></div>
                                <input type="text" class="form-control"  value="{{rank($employee->rank)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Date of FA</span></div>
                                <input type="date" class="form-control"  value="{{$employee->date_of_first_appointment}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">

                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Date of LP</span></div>
                                <input type="date" class="form-control"  value="{{$employee->date_of_last_appointment}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">

                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Post Held</span></div>
                                <input type="text" class="form-control"  value="{{$employee->post_held}}" {{$disabled}}>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Salary Structure</span></div>
                                <input type="text" class="form-control-sm"  value="{{ss($employee->salary_structure)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">

                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Grade Level</span></div>
                                <input type="text" class="form-control"  value="{{$employee->grade_level}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">

                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Step</span></div>
                                <input type="text" class="form-control"  value="{{$employee->step}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">

                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Bank Code</span></div>
                                <input type="text" class="form-control"  value="{{$employee->bank_code}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>

                        <div class="col">

                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Bank Name</span></div>
                                <input type="text" class="form-control"  value="{{$employee->bank_name}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>

                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Account Number</span></div>
                                <input type="text" class="form-control"  value="{{$employee->account_number}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Gender</span></div>
                                <input type="text" class="form-control"  value="{{gender($employee->gender)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Tribe</span></div>
                                <input type="text" class="form-control"  value="{{tribe($employee->tribe)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Religion</span></div>
                                <input type="text" class="form-control"  value="{{religion($employee->religion)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Phone Number</span></div>
                                <input type="text" class="form-control"  value="{{$employee->phone_number}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">WhatsApp Number</span></div>
                                <input type="text" class="form-control"  value="{{$employee->whatsapp_number}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Email</span></div>
                                <input type="text" class="form-control"  value="{{$employee->email}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Nationality</span></div>
                                <input type="text" class="form-control"  value="{{nationality($employee->nationality)}}" {{$disabled}}>


                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">State of Origin</span></div>
                                <input type="text" class="form-control"  value="{{state($employee->state_of_origin)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">LGA</span></div>
                                <input type="text" class="form-control"  value="{{lga($employee->local_government)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Date of Birth</span></div>
                                <input type="date" class="form-control"  value="{{$employee->date_of_birth}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Marital Status</span></div>
                                <input type="text" class="form-control"  value="{{marital_status($employee->marital_status)}}" {{$disabled}}>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <fieldset>
                        <legend><h6>Next of Kin</h6></legend>
                        <div class="row">
                            <div class="col">

                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Name</span></div>
                                    <input type="text" class="form-control"  value="{{$employee->name_of_next_of_kin}}" {{$disabled}}>

                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col">

                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Phone Number</span></div>
                                    <input type="text" class="form-control"  value="{{$employee->next_of_kin_phone_number}}" {{$disabled}}>

                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">

                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Relationship</span></div>
                                    <input type="text" class="form-control"  value="{{relationships($employee->relationship)}}" {{$disabled}}>

                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col">

                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Address</span></div>
                                    <input type="text" class="form-control"  value="{{$employee->address}}" {{$disabled}}>

                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

            @endif
            @if($create==true)
                    <form wire:submit="store()">
                        <fieldset>
                            <legend></legend>
                            <div class="row">
                                <div class="col-12 col-lg-2">
                                    @if ($profile_picture)
                                        <img src="{{ $profile_picture->temporaryUrl() }}" class="d-block" style="margin:auto !important; width: 78px;">
                                    @endif
                                    <input type="file" class="form-control-sm @error('profile_picture') is-invalid @enderror" wire:model.live="profile_picture" style="max-width: 150px">
                                    @error('profile_picture') <strong class="text-danger">{{$message}}</strong> @enderror
                                </div>
                                <div class="col-12 col-lg-10">
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            @error('staff_number')
                                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                                            @enderror
                                            <div class="input-group form-group">

                                                <div class="input-group-prepend"><span class="input-group-text">Staff Number</span></div>
                                                <input class="form-control @error('staff_number') is-invalid @enderror" wire:model.blur="staff_number" type="text">
                                                <div class="input-group-append"></div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            @error('payroll_number')
                                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                                            @enderror
                                            <div class="input-group form-group">

                                                <div class="input-group-prepend"><span class="input-group-text">Payroll Number</span></div>
                                                <input class="form-control  @error('payroll_number') is-invalid @enderror" wire:model.blur="payroll_number" type="text">
                                                <div class="input-group-append"></div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            @error('full_name')
                                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                                            @enderror
                                            <div class="input-group form-group">

                                                <div class="input-group-prepend"><span class="input-group-text">Full Name</span></div>
                                                <input class="form-control @error('full_name') is-invalid @enderror" wire:model.blur="full_name" type="text">
                                                <div class="input-group-append"></div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            @error('status')
                                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                                            @enderror
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">Status</span></div>
                                                <select class="form-control  @error('status') is-invalid @enderror" wire:model.blur="status">
                                                    <option value="">Select Status</option>
                                                    <option value="1">Active</option>
                                                    <option value="2">Suspended</option>
                                                    <option value="3">Dismissed</option>
                                                    <option value="4">Transferred</option>
                                                    <option value="5">Retired</option>
                                                    <option value="6">Leave of Absence</option>
                                                    <option value="7">Secondment</option>
                                                    <option value="8">Visiting Lecturers</option>
                                                    <option value="9">Part-timers</option>
                                                </select>
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
                                    <a class="nav-link active" data-toggle="tab" href="#home-v" role="tab" aria-controls="home">Personal Data</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#profile-v" role="tab" aria-controls="profile">Employment Data</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#messages-v" role="tab" aria-controls="messages">Salary & Pension Data</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#settings-v" role="tab" aria-controls="settings">Next of Kin</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="home-v" role="tabpanel">
                                    <div class="sv-tab-panel">
                                        <h3>Personal Data</h3>

                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                @error('date_of_birth')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Date of Birth</span></div>
                                                    <input class="form-control @error('date_of_birth') is-invalid @enderror" wire:model.blur="date_of_birth" type="date">
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('gender')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Gender</span></div>
                                                    <select class="form-control @error('gender') is-invalid @enderror" wire:model.blur="gender" >
                                                        <option value="">Gender</option>
                                                        @foreach(\App\Models\Gender::all() as $gender)
                                                            <option value="{{$gender->id}}">{{$gender->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('tribe')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Tribe</span></div>
                                                    <select class="form-control @error('tribe') is-invalid @enderror" wire:model.blur="tribe" >
                                                        <option value="">Select Tribe</option>
                                                        @foreach(\App\Models\Tribe::all() as $tribee)
                                                            <option value="{{$tribee->id}}">{{$tribee->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('religion')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Religion</span></div>
                                                    <select class="form-control @error('religion') is-invalid @enderror" wire:model.blur="religion">
                                                        <option value="">Religion</option>
                                                        @foreach(\App\Models\Religion::all() as $religion)
                                                            <option value="{{$religion->id}}">{{$religion->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('nationality')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Nationality</span></div>
                                                    <select class="form-control @error('nationality') is-invalid @enderror" wire:model.blur="nationality">
                                                        <option value="">Nationality</option>
                                                        <option value="1">Nigeria</option>
                                                    </select>

                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('marital_status')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Marital Status</span></div>
                                                    <select class="form-control @error('marital_status') is-invalid @enderror" wire:model.blur="marital_status">
                                                        <option value="">Marital Status</option>
                                                        @foreach(\App\Models\MaritalStatus::all() as $marital_status)
                                                            <option value="{{$marital_status->id}}">{{$marital_status->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('state_of_origin')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">State of Origin</span></div>
                                                    <select class="form-control @error('state_of_origin') is-invalid @enderror" wire:model.blur="state_of_origin">
                                                        <option value="" class="">Select State</option>
                                                        @forelse($states as $state)
                                                            <option value="{{$state->id}}">{{$state->name}}</option>
                                                        @empty

                                                        @endforelse
                                                    </select>
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('local_government')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">LGA</span></div>
                                                    <select class="form-control @error('local_government') is-invalid @enderror" wire:model.blur="local_government" >
                                                        <option value="">Select LGA</option>
                                                        @forelse($lgas as $lga)
                                                            <option value="{{$lga->id}}">{{$lga->name}}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('phone_number')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Phone Number</span></div>
                                                    <input class="form-control @error('phone_number') is-invalid @enderror" wire:model.blur="phone_number" type="text">
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('email')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Email</span></div>
                                                    <input class="form-control @error('email') is-invalid @enderror" wire:model.blur="email" type="text">
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-12">
                                                @error('whatsapp_number')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">WhatsApp Number</span></div>
                                                    <input class="form-control @error('whatsapp_number') is-invalid @enderror" wire:model.blur="whatsapp_number" type="text">
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane" id="profile-v" role="tabpanel">
                                    <div class="sv-tab-panel">
                                        <h3>Employment Data</h3>
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                @error('employment_type')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Employment Type</span></div>
                                                    <select class="form-control  @error('employment_type') is-invalid @enderror" wire:model.blur="employment_type">
                                                        <option value="">Employment Type</option>
                                                        @foreach($employments as $emp)
                                                            <option value="{{$emp->id}}">{{$emp->name}}</option>

                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('staff_category')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Staff Category</span></div>
                                                    <select class="form-control  @error('staff_category') is-invalid @enderror" wire:model.blur="staff_category">
                                                        <option value="">Staff Category</option>
                                                        @foreach($categories as $cat)
                                                            <option value="{{$cat->id}}">{{$cat->name}}</option>

                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('unit')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Unit</span></div>
                                                    <select class="form-control @error('unit') is-invalid @enderror" wire:model.blur="unit">
                                                        <option value="">Select Unit</option>
                                                        @foreach($units as $unit)
                                                            <option value="{{$unit->id}}">{{$unit->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('department')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Department</span></div>
                                                    <select class="form-control @error('department') is-invalid @enderror" wire:model.blur="department">
                                                        <option value="">Department</option>
                                                        @foreach($departments as $dept)
                                                            <option value="{{$dept->id}}">{{$dept->name}}</option>
                                                        @endforeach

                                                    </select>
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('rank')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Rank</span></div>
                                                    <select class="form-control @error('rank') is-invalid @enderror" wire:model.blur="rank" >
                                                        <option value="">Select Rank</option>
                                                        @foreach($ranks as $rank)
                                                            <option value="{{$rank->id}}">{{$rank->name}}</option>
                                                        @endforeach

                                                    </select>
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('post_held')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Post Held</span></div>
                                                    <input class="form-control  @error('post_held') is-invalid @enderror" wire:model.blur="post_held" type="text">
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('date_of_first_appointment')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Date of FA</span></div>
                                                    <input class="form-control  @error('date_of_first_appointment') is-invalid @enderror" wire:model.blur="date_of_first_appointment" type="date">
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('date_of_last_appointment')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Date of LP</span></div>
                                                    <input class="form-control  @error('date_of_last_appointment') is-invalid @enderror" wire:model.blur="date_of_last_appointment" type="date">
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="messages-v" role="tabpanel">
                                    <div class="sv-tab-panel">
                                        <h3>Salary & Pension Data</h3>
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                @error('pension_pin')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Pension Pin</span></div>
                                                    <input class="form-control  @error('pension_pin') is-invalid @enderror" wire:model.blur="pension_pin" type="text">
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('pfa_name')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">PFA Name</span></div>
                                                    <select class="form-control  @error('pfa_name') is-invalid @enderror" wire:model.blur="pfa_name">
                                                        <option value="">PF Name</option>
                                                        @foreach($pfas as $pfa)
                                                            <option value="{{$pfa->id}}">{{$pfa->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('salary_structure')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Salary Structure</span></div>
                                                    <select class="form-control @error('salary_structure') is-invalid @enderror" wire:model.blur="salary_structure" >
                                                        <option value="">Salary Structure</option>
                                                        @foreach($salary_structures as $salary)
                                                            <option value="{{$salary->id}}">{{$salary->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('grade_level')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                @php
                                                    $salObj=\App\Models\SalaryStructure::find($this->salary_structure);
                                                @endphp
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Grade Level</span></div>
                                                    <select  class="form-control @error('grade_level') is-invalid @enderror" wire:model.blur="grade_level" type="number" >
                                                        <option value="">Select Grade Level</option>
                                                        @if($this->salary_structure != '')
                                                            @for($i=1; $i <= $salObj->no_of_grade; $i++)
                                                                <option value="{{$i}}">Grade {{$i}}</option>

                                                            @endfor
                                                        @endif
                                                    </select>
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('step')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror

                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Step</span></div>
                                                    <select class="form-control @error('step') is-invalid @enderror" wire:model.blur="step" >
                                                        @if(!is_null($salObj))
                                                            @php
                                                                $step_no=\App\Models\SalaryStructureTemplate::where('salary_structure_id',$salObj->id)
                                                                        ->where('grade_level',$grade_level)
                                                                        ->first()
                                                            @endphp
                                                            <option value="">Select Step</option>
                                                            @if(!is_null($step_no))
                                                                @for($i=1; $i <= $step_no->no_of_grade_steps; $i++)
                                                                    <option value="{{$i}}">Step {{$i}}</option>
                                                                @endfor
                                                            @endif
                                                        @endif



                                                    </select>
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('account_number')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Account Number</span></div>
                                                    <input class="form-control @error('account_number') is-invalid @enderror" wire:model.blur="account_number"  type="text">
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('bank_code')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Bank Code</span></div>
                                                    <select class="form-control @error('bank_code') is-invalid @enderror" wire:model.blur="bank_code">
                                                        <option value="">Bank Code</option>
                                                        @foreach($banks as $bank)
                                                            <option value="{{$bank->id}}">{{$bank->bank_code}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                @error('bank_name')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Bank Name</span></div>
                                                    <select class="form-control @error('bank_name') is-invalid @enderror" wire:model.blur="bank_name" >
                                                        <option value="">Bank Name</option>
                                                        @foreach($banks as $bank)
                                                            <option value="{{$bank->bank_name}}">{{$bank->bank_name}}</option>
                                                        @endforeach
                                                    </select>


                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="settings-v" role="tabpanel">
                                    <div class="sv-tab-panel">
                                        <h3>Next of Kin</h3>
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                @error('name_of_next_of_kin')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Name</span></div>
                                                    <input class="form-control @error('name_of_next_of_kin') is-invalid @enderror" wire:model.blur="name_of_next_of_kin" type="text">
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-lg-6">
                                                @error('next_of_kin_phone_number')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Phone Number</span></div>
                                                    <input class="form-control @error('next_of_kin_phone_number') is-invalid @enderror" wire:model.blur="next_of_kin_phone_number" type="text">
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-lg-6">
                                                @error('relationship')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Relationship</span></div>
                                                    <select class="form-control @error('relationship') is-invalid @enderror" wire:model.blur="relationship" >
                                                        <option value="">Relationship</option>
                                                        @foreach(\App\Models\Relationship::all() as $ralationship)
                                                            <option value="{{$ralationship->id}}">{{$ralationship->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-lg-6">
                                                @error('address')
                                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                                @enderror
                                                <div class="input-group form-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Address</span></div>
                                                    <input class="form-control @error('address') is-invalid @enderror" wire:model.blur="address" type="text">
                                                    <div class="input-group-append"></div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row mt-1">

                                            <div class="col-12 text-left">
                                                <button class="btn save_btn" type="submit">Save</button>
                                                <button  class="btn reset_btn" type="reset">Clear</button>
                                                <button class="btn close_btn float-right" type="button" wire:click="close()">Close</button>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

            @endif
            @if($edit==true)
                <form action="" wire:submit="update({{$ids}})" class="" style="">
                    <div class="row">
                        <div class="col text-center">
                            @if ($profile_picture)
                                <img src="{{ $profile_picture->temporaryUrl() }}" class="d-block" style="margin:auto !important; width: 150px;">
                            @endif
                            <input type="file" class="form-control-sm @error('profile_picture') is-invalid @enderror" wire:model.live="profile_picture" style="max-width: 150px">
                            @error('profile_picture') <strong class="text-danger">{{$message}}</strong> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            @error('full_name')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">

                                <div class="input-group-prepend"><span class="input-group-text">Full Name</span></div>
                                <input class="form-control @error('full_name') is-invalid @enderror" wire:model.blur="full_name" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-4">
                            @error('staff_number')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">

                                <div class="input-group-prepend"><span class="input-group-text">Staff Number</span></div>
                                <input class="form-control @error('staff_number') is-invalid @enderror" wire:model.blur="staff_number" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-4">
                            @error('payroll_number')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">

                                <div class="input-group-prepend"><span class="input-group-text">Payroll Number</span></div>
                                <input class="form-control  @error('payroll_number') is-invalid @enderror" wire:model.blur="payroll_number" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            @error('employment_type')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Employment Type</span></div>
                                <select class="form-control  @error('employment_type') is-invalid @enderror" wire:model.blur="employment_type">
                                    <option value="">Employment Type</option>
                                    @foreach($employments as $emp)
                                        <option value="{{$emp->id}}">{{$emp->name}}</option>

                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">
                            @error('staff_category')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Staff Category</span></div>
                                <select class="form-control  @error('staff_category') is-invalid @enderror" wire:model.blur="staff_category">
                                    <option value="">Staff Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>

                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">
                            @error('status')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Status</span></div>
                                <select class="form-control  @error('status') is-invalid @enderror" wire:model.blur="status">
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="2">Suspended</option>
                                    <option value="3">Dismissed</option>
                                    <option value="4">Transferred</option>
                                    <option value="5">Retired</option>
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            @error('pfa_name')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">PFA Name</span></div>
                                <select class="form-control  @error('pfa_name') is-invalid @enderror" wire:model.blur="pfa_name">
                                    <option value="">PF Name</option>
                                    @foreach($pfas as $pfa)
                                        <option value="{{$pfa->id}}">{{$pfa->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">
                            @error('pension_pin')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Pension Pin</span></div>
                                <input class="form-control  @error('pension_pin') is-invalid @enderror" wire:model.blur="pension_pin" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            @error('unit')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Unit</span></div>
                                <select class="form-control @error('unit') is-invalid @enderror" wire:model.blur="unit">
                                    <option value="">Select Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{$unit->id}}">{{$unit->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">
                            @error('department')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Department</span></div>
                                <select class="form-control @error('department') is-invalid @enderror" wire:model.blur="department">
                                    <option value="">Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{$dept->id}}">{{$dept->name}}</option>
                                    @endforeach

                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">
                            @error('rank')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Rank</span></div>
                                <select class="form-control @error('rank') is-invalid @enderror" wire:model.blur="rank" >
                                    <option value="">Select Rank</option>
                                    @foreach($ranks as $rank)
                                        <option value="{{$rank->id}}">{{$rank->name}}</option>
                                    @endforeach

                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            @error('date_of_first_appointment')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Date of FA</span></div>
                                <input class="form-control  @error('date_of_first_appointment') is-invalid @enderror" wire:model.blur="date_of_first_appointment" type="date">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">
                            @error('date_of_last_appointment')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Date of LP</span></div>
                                <input class="form-control  @error('date_of_last_appointment') is-invalid @enderror" wire:model.blur="date_of_last_appointment" type="date">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">
                            @error('post_held')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Post Held</span></div>
                                <input class="form-control  @error('post_held') is-invalid @enderror" wire:model.blur="post_held" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            @error('salary_structure')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Salary Structure</span></div>
                                <select class="form-control @error('salary_structure') is-invalid @enderror" wire:model.blur="salary_structure" >
                                    <option value="">Salary Structure</option>
                                    @foreach($salary_structures as $salary)
                                        <option value="{{$salary->id}}">{{$salary->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">
                            @error('grade_level')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            @php
                                $salObj=\App\Models\SalaryStructure::find($this->salary_structure);
                            @endphp
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Grade Level</span></div>
                                <select  class="form-control @error('grade_level') is-invalid @enderror" wire:model.blur="grade_level" type="number" >
                                    <option value="">Select Grade Level</option>
                                    @if($salary_structure != null)
                                        @for($i=1; $i <= $salObj->no_of_grade; $i++)
                                            <option value="{{$i}}">Grade {{$i}}</option>

                                        @endfor
                                    @endif
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">
                            @error('step')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Step</span></div>
                                <select class="form-control @error('step') is-invalid @enderror" wire:model.blur="step" >
                                    <option value="">Select Step</option>
                                    @if(!is_null($salObj))
                                        @php
                                            $step_no=\App\Models\SalaryStructureTemplate::where('salary_structure_id',$salObj->id)
                                                    ->where('grade_level',$grade_level)
                                                    ->first()
                                        @endphp
                                        <option value="">Select Step</option>
                                        @if(!is_null($step_no))
                                            @for($i=1; $i <= $step_no->no_of_grade_steps; $i++)
                                                <option value="{{$i}}">Step {{$i}}</option>
                                            @endfor
                                        @endif
                                    @endif
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            @error('bank_code')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Bank Code</span></div>
                                <select class="form-control @error('bank_code') is-invalid @enderror" wire:model.blur="bank_code">
                                    <option value="">Bank Code</option>
                                    @foreach($banks as $bank)
                                        <option value="{{$bank->id}}">{{$bank->bank_code}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>

                        <div class="col">
                            @error('bank_name')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Bank Name</span></div>
                                <select class="form-control @error('bank_name') is-invalid @enderror" wire:model.blur="bank_name" >
                                    <option value="">Bank Name</option>
                                    @foreach($banks as $bank)
                                        <option value="{{$bank->bank_name}}">{{$bank->bank_name}}</option>
                                    @endforeach
                                </select>


                                <div class="input-group-append"></div>
                            </div>
                        </div>

                        <div class="col">
                            @error('account_number')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Account Number</span></div>
                                <input class="form-control @error('account_number') is-invalid @enderror" wire:model.blur="account_number"  type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            @error('gender')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Gender</span></div>
                                <select class="form-control @error('gender') is-invalid @enderror" wire:model.blur="gender" >
                                    <option value="">Gender</option>
                                    @foreach(\App\Models\Gender::all() as $gender)
                                        <option value="{{$gender->id}}">{{$gender->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">
                            @error('tribe')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Tribe</span></div>
                                <select class="form-control @error('tribe') is-invalid @enderror" wire:model.blur="tribe"  >
                                    <option value="">Select Tribe</option>
                                    @foreach(\App\Models\Tribe::all() as $tribee)
                                        <option value="{{$tribee->id}}">{{$tribee->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">
                            @error('religion')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Religion</span></div>
                                <select class="form-control @error('religion') is-invalid @enderror" wire:model.blur="religion">
                                    <option value="">Religion</option>

                                    @foreach(\App\Models\Religion::all() as $religion)
                                        <option value="{{$religion->id}}">{{$religion->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            @error('phone_number')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Phone Number</span></div>
                                <input class="form-control @error('phone_number') is-invalid @enderror" wire:model.blur="phone_number" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">
                            @error('whatsapp_number')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">WhatsApp Number</span></div>
                                <input class="form-control @error('whatsapp_number') is-invalid @enderror" wire:model.blur="whatsapp_number" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">
                            @error('email')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Email</span></div>
                                <input class="form-control @error('email') is-invalid @enderror" wire:model.blur="email" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            @error('nationality')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Nationality</span></div>
                                <select class="form-control @error('nationality') is-invalid @enderror" wire:model.blur="nationality">
                                    <option value="">Nationality</option>
                                    <option value="1">Nigeria</option>
                                </select>

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">
                            @error('state_of_origin')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">State of Origin</span></div>
                                <select class="form-control @error('state_of_origin') is-invalid @enderror" wire:model.blur="state_of_origin">
                                    <option value="" class="">Select State</option>
                                    @forelse($states as $state)
                                        <option value="{{$state->id}}">{{$state->name}}</option>
                                    @empty

                                    @endforelse
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">
                            @error('local_government')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">LGA</span></div>
                                <select class="form-control @error('local_government') is-invalid @enderror" wire:model.blur="local_government" >
                                    <option value="">Select LGA</option>
                                    @forelse($lgas as $lga)
                                        <option value="{{$lga->id}}">{{$lga->name}}</option>
                                    @empty
                                    @endforelse
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            @error('date_of_birth')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Date of Birth</span></div>
                                <input class="form-control @error('date_of_birth') is-invalid @enderror" wire:model.blur="date_of_birth" type="date">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col">
                            @error('marital_status')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Marital Status</span></div>
                                <select class="form-control @error('marital_status') is-invalid @enderror" wire:model.blur="marital_status">
                                    <option value="">Marital Status</option>

                                    @foreach(\App\Models\MaritalStatus::all() as $marital_status)
                                        <option value="{{$marital_status->id}}">{{$marital_status->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>

                    <fieldset>
                        <legend><h6>Next of Kin</h6></legend>
                        <div class="row">
                            <div class="col">
                                @error('name_of_next_of_kin')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Name</span></div>
                                    <input class="form-control @error('name_of_next_of_kin') is-invalid @enderror" wire:model.blur="name_of_next_of_kin" type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col">
                                @error('next_of_kin_phone_number')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Phone Number</span></div>
                                    <input class="form-control @error('next_of_kin_phone_number') is-invalid @enderror" wire:model.blur="next_of_kin_phone_number" type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                @error('relationship')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Relationship</span></div>
                                    <select class="form-control @error('relationship') is-invalid @enderror" wire:model.blur="relationship" >
                                        <option value="">Relationship</option>

                                        @foreach(\App\Models\Relationship::all() as $ralationship)
                                            <option value="{{$ralationship->id}}">{{$ralationship->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col">
                                @error('address')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Address</span></div>
                                    <input class="form-control @error('address') is-invalid @enderror" wire:model.blur="address" type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="row">
                        <div class="col">

                            <div class="row my-2">
                                <div class="col text-right">

                                    <button class="btn save_btn" type="submit">Update Record</button>
                                    <button class="btn close_btn" type="button" wire:click="close()">Close</button>

                                </div>

                            </div>

                        </div>
                    </div>
                </form>
            @endif

        </div>
    </div>
    <script>
        const actualBtn = document.getElementById('actual-btn');

        const fileChosen = document.getElementById('file-chosen');

        actualBtn.addEventListener('change', function(){
            fileChosen.textContent = this.files[0].name
        })
    </script>
    @section('title')
        Employee Profile
    @endsection
    @section('page_title')
        Employee Profile
    @endsection
</div>
