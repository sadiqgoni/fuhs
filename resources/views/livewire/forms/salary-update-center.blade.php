<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <style>
        svg {
            display: none !important;
        }

        /*.input-group>.custom-file, .input-group>.custom-select, .input-group>.form-control{*/
        /*    font-size: 14px !important;*/
        /*    padding: 2px 0 2px 3px;*/
        /*}*/
    </style>
    @if($record == true)
        <div class="row">
            <div class="col">
                <div wire:loading
                    style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                    <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                        <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                    </div>
                </div>
                <div class="d-flex flex-wrap align-items-center">
                    <div class="flex-grow-1">
                        <input type="text" class="form-control-sm mb-1 mb-md-0" wire:model.live="search"
                            placeholder="Search for Employee..">

                        <label for="" class="ml-2">Filter By:</label>
                        <select name="" id="" class="form-control-sm mr-1" wire:model.live="filter_type">
                            <option value="">Employee Type</option>
                            @foreach(\App\Models\EmploymentType::all() as $emp_type)
                                <option value="{{$emp_type->id}}">{{$emp_type->name}}</option>
                            @endforeach
                        </select>
                        <select name="" id="" class="form-control-sm mr-1" wire:model.live="filter_unit"
                            style="max-width: 110px;">
                            <option value="">Unit</option>
                            @foreach(\App\Models\Unit::get() as $unit_filter)
                                <option value="{{$unit_filter->id}}">{{$unit_filter->name}}</option>
                            @endforeach
                        </select>
                        <select name="" id="" class="form-control-sm mr-1" wire:model.live="filter_dept">
                            <option value="">Department</option>
                            @foreach($depts as $dept)
                                <option value="{{$dept->id}}">{{$dept->name}}</option>
                            @endforeach
                        </select>

                        <label for="" class="ml-2">Show record per-page</label>
                        <select name="" id="" class="form-control-sm" wire:model.live="perpage">
                            <option value="50"></option>
                            <option value="100">100</option>
                            <option value="250">250</option>
                            <option value="500">500</option>
                            <option value="1000">1000</option>
                        </select>
                    </div>

                    @can('can_save')
                        <div class="ml-auto mt-2 mt-md-0">
                            <button class="btn btn-sm reset_btn" type="button" wire:click.prevent="resetAllSalaries()">
                                Reset All Salaries
                            </button>
                        </div>
                    @endcan
                </div>


                <div class="table-responsive">
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
                            @forelse($employees as $employee)
                                <tr>
                                    <th>{{($employees->currentPage() - 1) * $employees->perpage() + $loop->index + 1}}</th>
                                    <td>{{$employee->staff_number}}</td>
                                    <td>{{$employee->payroll_number}}</td>
                                    <td>{{$employee->full_name}}</td>
                                    <td>{{unit_name($employee->unit)}}</td>
                                    <td>{{dept($employee->department)}}</td>

                                    <td><button class="btn btn-sm btn-info" wire:click.prevent="view({{$employee->id}})">View <i
                                                class="fa fa-eye"></i></button></td>
                                </tr>
                            @empty


                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5">{{$employees->links(data: ['scrollTo' => false])}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endif
    @if($record == false)

        <div class="row">
            <div class="col-12 text-center mb-2">
                <label for="">Search Employee</label> <input type="text" name="search" wire:model.live="search_employee"
                    wire:keydown.enter.prevent="searchEmployee" placeholder="Search with Payroll NO."
                    class="form-control-sm" style="max-width: 300px !important;">
            </div>
            <div class="col-12">
                <form class="p-0" wire:submit.prevent="confirm({{$salary->id}})">
                    <fieldset>
                        <div wire:loading
                            style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                            <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                                <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                            </div>
                        </div>
                        <legend class="py-0">
                            <div class="float-left">
                                @if($previous_id)
                                    <button type="button" class="btn btn-primary btn-sm"
                                        wire:click.prevent="view({{$previous_id}})">
                                        << Previous </button>
                                @endif
                                        @if($next_id)
                                            <button type="button" class="btn btn-primary btn-sm"
                                                wire:click.prevent="view({{$next_id}})">Next
                                                >></button>
                                        @endif
                            </div>
                            {{-- <div class="text-center">--}}

                                {{-- </div>--}}
                            <div class="float-right">
                                @if($employee_info->status == 1)

                                    @can('can_save')
                                        <button class="btn reset_btn" type="button" wire:click.prevent="resetSalary()">Reset Salary
                                            <i class="fa fa-refresh"></i></button>

                                        <button class="btn save_btn" type="submit">Save Changes <i class="fa fa-save"></i></button>
                                    @endcan
                                @endif


                                <button class="btn close_btn " type="button" wire:click.prevent="close()">Close <i
                                        class="fa fa-times"></i></button>

                            </div>

                        </legend>
                        <div class="row">

                            <div class="col-12 col-lg-3 col-md-6">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Name</span></div>
                                    <input class="form-control" {{$disabled}} value="{{$employee_info->full_name}}"
                                        type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 col-md-6">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Staff No</span></div>
                                    <input class="form-control" type="text" {{$disabled}}
                                        value="{{$employee_info->staff_number}}">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 col-md-6">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Payroll No</span></div>
                                    <input class="form-control" type="text" {{$disabled}}
                                        value="{{$employee_info->payroll_number}}">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 col-md-6">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Status</span></div>
                                    <input class="form-control" type="text" {{$disabled}}
                                        value="{{emp_status($employee_info->status)}}">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-3 col-md-6">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Emp Type</span></div>
                                    <input class="form-control" type="text" {{$disabled}}
                                        value="{{emp_type($employee_info->employment_type)}}">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 col-md-6">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Department</span></div>
                                    <input class="form-control" type="text" {{$disabled}}
                                        value="{{dept($employee_info->department)}}">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 col-md-6">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Staff Category</span>
                                    </div>
                                    <input class="form-control" {{$disabled}}
                                        value="{{staff_cat($employee_info->staff_category)}}" type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 col-md-6">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Salary Structure</span>
                                    </div>
                                    <input class="form-control" {{$disabled}}
                                        value="{{ss($employee_info->salary_structure)}}" type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-12 col-md-6 col-lg-2">

                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Grade Level</span></div>
                                    <input class="form-control" {{$disabled}} value="{{$employee_info->grade_level}}"
                                        type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">

                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Step</span></div>
                                    <input class="form-control" {{$disabled}} value="{{$employee_info->step}}" type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Account No</span></div>
                                    <input class="form-control" {{$disabled}} value="{{$employee_info->account_number}}"
                                        type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Bank Code</span></div>
                                    <input class="form-control" {{$disabled}} value="{{$employee_info->bank_code}}"
                                        type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Bank Name</span></div>
                                    <input class="form-control" {{$disabled}} value="{{$employee_info->bank_name}}"
                                        type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>


                        </div>


                    </fieldset>

                    {{-- //salary info--}}
                    <div class="row  my-2">

                        <div class="col-12 col-lg-10">
                            <fieldset>
                                <legend class="py-0">
                                    <h6>Salary Info.</h6>
                                </legend>
                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-6">

                                        <div class="input-group form-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Basic
                                                    Salary</span></div>
                                            <input value="{{number_format($salary->basic_salary, 2)}}"
                                                class="form-control @error('basic_salary') is-invalid @enderror"
                                                {{$disabled}} wire:model.blur="basic_salary" type="text">
                                            <div class="input-group-append"></div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-6">
                                        @error('salary_deduction')
                                            <strong class="text-danger">{{$message}}</strong>
                                        @enderror
                                        <div class="input-group form-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Salary
                                                    Deduction</span></div>
                                            <input class="form-control @error('salary_deduction') is-invalid @enderror"
                                                wire:model.blur="salary_deduction" type="text">
                                            <div class="input-group-append"><span class="input-group-text">%</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        @error('salary_arears')
                                            <strong class="text-danger">{{$message}}</strong>
                                        @enderror
                                        <div class="input-group form-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Salary
                                                    Areas</span></div>
                                            <input class="form-control @error('salary_arears') is-invalid @enderror"
                                                wire:model.blur="salary_arears" type="text">
                                            <div class="input-group-append"></div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-6">

                                        <div class="input-group form-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Total
                                                    Deduction</span></div>
                                            <input class="form-control @error('total_deduction') is-invalid @enderror"
                                                value="{{number_format($salary->total_deduction, 2)}}" {{$disabled}}
                                                type="text">
                                            <div class="input-group-append"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-4">
                                        @error('total_allowance')
                                            <strong class="text-danger">{{$message}}</strong>
                                        @enderror
                                        <div class="input-group form-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Total
                                                    Allowance</span></div>
                                            <input class="form-control"
                                                value="{{number_format($salary->total_allowance, 2)}}" {{$disabled}}
                                                type="text">
                                            <div class="input-group-append"></div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4">
                                        @error('gross_pay')
                                            <strong class="text-danger">{{$message}}</strong>
                                        @enderror
                                        <div class="input-group form-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Gross Pay</span>
                                            </div>
                                            <input class="form-control @error('gross_pay') is-invalid @enderror"
                                                value="{{number_format($salary->gross_pay, 2)}}" {{$disabled}} type="text">
                                            <div class="input-group-append"></div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        @error('net_pay')
                                            <strong class="text-danger">{{$message}}</strong>
                                        @enderror
                                        <div class="input-group form-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Net Pay</span>
                                            </div>
                                            <input class="form-control" value="{{number_format($salary->net_pay, 2)}}"
                                                {{$disabled}} type="text">
                                            <div class="input-group-append"></div>
                                        </div>
                                    </div>

                                </div>

                            </fieldset>
                        </div>

                        <div class="col-lg-2 text-center" style="margin: auto">
                            @php
                                $gross = $salary->gross_pay;
                                $td = $salary->total_deduction;
                                $total_sum = $td / $gross;
                                $total_sum = $total_sum * 100;
                                if ($total_sum <= 33) {

                                } else {

                                }
                            @endphp
                            {{round($total_sum)}}%
                            {{-- <progress id="file" value="{{$total_sum }}" max="{{$gross}}" --}} {{-- style="border:none;border-radius:25px;height: 50px;width: 100%;--}}
                            {{--                                      @if($total_sum <= 33)--}}
                            {{--                                background:greenyellow !important;--}}
                            {{--                            @else--}}
                            {{--                                background:rosybrown !important;--}}
                            {{--                            @endif--}}
                            {{--                                ">--}}
                                {{-- </progress>--}}
                            <div class="progress bg-success" style="height: 50px">
                                <div class="progress-bar bg-warning" role="progressbar"
                                    aria-valuenow="{{round($total_sum, 2)}}" aria-valuemin="0" aria-valuemax="100"
                                    style="width: {{round($total_sum, 2)}}%;height: 50px;">
                                    {{round($total_sum, 2)}}%
                                </div>
                            </div>
                            @if($total_sum <= 33)
                                Healthy
                            @else
                                Un Healthy
                            @endif
                        </div>
                    </div>

                    {{-- //allowance deduction--}}
                    {{-- @if ($errors->any())--}}
                    {{-- <div class="alert alert-danger">--}}
                        {{-- <ul>--}}
                            {{-- @foreach ($errors->all() as $error)--}}
                            {{-- <li>{{ $error }}</li>--}}
                            {{-- @endforeach--}}
                            {{-- </ul>--}}
                        {{-- </div>--}}
                    {{-- @endif--}}
                    <div class="row" style="">

                        <div class="col-12">
                            <div class="vertical-tas mt-2" style="overflow-y: auto !important;overflow-x: hidden">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a wire:ignore class="nav-link active" data-toggle="tab" href="#home-v" role="tab"
                                            aria-controls="home">Allowances</a>
                                    </li>
                                    <li class="nav-item">
                                        <a wire:ignore class="nav-link" data-toggle="tab" href="#profile-v" role="tab"
                                            aria-controls="profile">Deductions</a>
                                    </li>
                                    <li class="nav-item">
                                        <a wire:ignore class="nav-link" data-toggle="tab" href="#messages-v" role="tab"
                                            aria-controls="messages">More Deductions</a>
                                    </li>
                                    <li class="nav-item">
                                        <a wire:ignore class="nav-link" data-toggle="tab" href="#settings-v" role="tab"
                                            aria-controls="messages">More Deductions</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div wire:ignore.self class="tab-pane active" id="home-v" role="tabpanel">
                                        <div class="sv-tab-pane pt-3">
                                            <div class="row">

                                                @forelse ($allow as $allowance)
                                                    @php $field = 'A' . $allowance->id; @endphp
                                                    <div class="col-12 col-md-4 col-lg-3">
                                                        <div class="input-group form-group">
                                                            @error('inputs.' . $field)
                                                                <div class="text-danger text-sm">{{ $message }}</div>
                                                            @enderror
                                                            <div class="input-group-prepend"><span class="input-group-text"
                                                                    style="font-size: 12px;border: none">
                                                                    {{ $allowance->allowance_name }}</span>
                                                            </div>
                                                            <input type="text" wire:model.lazy="inputs.{{ $field }}"
                                                                class="form-control @error('inputs.' . $field) is-invalid  @enderror"
                                                                placeholder="Enter amount (e.g. 1234.56)">
                                                            <div class="input-group-append">
                                                                @if($allowance->allowance_type == 1)
                                                                    <Button wire:click.prevent="resetSingle({{$allowance->id}})"
                                                                        style="font-size: 10px" class="border-0 btn">Reset</Button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty

                                                @endforelse

                                            </div>


                                        </div>

                                    </div>

                                    <div wire:ignore.self class="tab-pane" id="profile-v" role="tabpanel">
                                        <div class="row py-4">

                                            @forelse($deduct->take(12) as $deduction)
                                                @php $field = 'D' . $deduction->id; @endphp
                                                <div class="col-12 col-md-4 col-lg-3 ">
                                                    @error('fields.' . $field)
                                                        <div class="text-danger text-sm">{{ $message }}</div>
                                                    @enderror
                                                    <div class="input-group form-group">
                                                        <div class="input-group-prepend"><span class="input-group-text"
                                                                style="font-size: 12px">{{$deduction->deduction_name}}</span>
                                                        </div>
                                                        <input @if($deduction->id == 5) {{$disabled}} @endif type="text"
                                                            wire:model.lazy="fields.{{ $field }}"
                                                            class="form-control @error('fields.' . $field) is-invalid  @enderror"
                                                            placeholder="Enter amount (e.g. 123.56)">
                                                        {{-- <div class="input-group-append">--}}
                                                            {{-- --}}{{-- @if($deduction->deduction_type==1)--}}
                                                            {{-- --}}{{-- <Button
                                                                wire:click.prevent="resetDeductSingle({{$deduction->id}})"
                                                                style="font-size: 10px" class="border-0 btn">Reset</Button>--}}
                                                            {{-- --}}{{-- @endif--}}
                                                            {{-- </div>--}}
                                                    </div>
                                                </div>
                                            @empty

                                            @endforelse

                                        </div>
                                    </div>
                                    <div wire:ignore.self class="tab-pane" id="messages-v" role="tabpanel">
                                        <div class="row py-4">
                                            @forelse($deduct->slice('12') as $deduction)
                                                @php $field = 'D' . $deduction->id; @endphp
                                                <div class="col-12 col-md-4 col-lg-3 ">
                                                    @error('fields.' . $field)
                                                        <div class="text-danger text-sm">{{ $message }}</div>
                                                    @enderror
                                                    <div class="input-group form-group">
                                                        <div class="input-group-prepend"><span class="input-group-text"
                                                                style="font-size: 12px">{{$deduction->deduction_name}}</span>
                                                        </div>
                                                        <input @if($deduction->id == 5) {{$disabled}} @endif type="text"
                                                            wire:model.lazy="fields.{{ $field }}"
                                                            class="form-control @error('fields.' . $field) is-invalid  @enderror"
                                                            placeholder="Enter amount (e.g. 123.56)">
                                                        {{-- <div class="input-group-append">--}}
                                                            {{-- --}}{{-- @if($deduction->deduction_type==1)--}}
                                                            {{-- --}}{{-- <Button
                                                                wire:click.prevent="resetDeductSingle({{$deduction->id}})"
                                                                style="font-size: 10px" class="border-0 btn">Reset</Button>--}}
                                                            {{-- --}}{{-- @endif--}}
                                                            {{-- </div>--}}
                                                    </div>
                                                </div>
                                            @empty

                                            @endforelse
                                        </div>
                                    </div>
                                    <div wire:ignore.self class="tab-pane" id="settings-v" role="tabpanel">
                                        <div class="row py-4">
                                            @forelse($deduct->slice('24') as $deduction)
                                                @php $field = 'D' . $deduction->id; @endphp
                                                <div class="col-12 col-md-4 col-lg-3 ">
                                                    @error('fields.' . $field)
                                                        <div class="text-danger text-sm">{{ $message }}</div>
                                                    @enderror
                                                    <div class="input-group form-group">
                                                        <div class="input-group-prepend"><span class="input-group-text"
                                                                style="font-size: 12px">{{$deduction->deduction_name}}</span>
                                                        </div>
                                                        <input @if($deduction->id == 5) {{$disabled}} @endif type="text"
                                                            wire:model.lazy="fields.{{ $field }}"
                                                            class="form-control @error('fields.' . $field) is-invalid  @enderror"
                                                            placeholder="Enter amount (e.g. 123.56)">
                                                        {{-- <div class="input-group-append">--}}
                                                            {{-- --}}{{-- @if($deduction->deduction_type==1)--}}
                                                            {{-- --}}{{-- <Button
                                                                wire:click.prevent="resetDeductSingle({{$deduction->id}})"
                                                                style="font-size: 10px" class="border-0 btn">Reset</Button>--}}
                                                            {{-- --}}{{-- @endif--}}
                                                            {{-- </div>--}}
                                                    </div>
                                                </div>
                                            @empty

                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>


                </form>
            </div>
        </div>
    @endif


    @section('title')
        Salary Update Center
    @endsection
    @section('page_title')
        Payroll Update/ Monthly Update
    @endsection
</div>