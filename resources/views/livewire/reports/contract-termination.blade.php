<div>
    {{-- Be like water. --}}
    <style>
        svg{
            display: none !important;
        }

    </style>
    <div class="row">
        <div class="col-lg-12 p-3">
            <form action="{{route('contract.termination.list')}}" method="post" id="myForm" target="_blank">@csrf
                <fieldset>
                    <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                        </div>
                    </div>
                    <legend>Employee Selection:</legend>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            @error('employment_type')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Employment Type</span></div>
                                <select class="form-control  @error('employment_type') is-invalid @enderror" wire:model.blur="employee_type" name="employee_type">
                                    <option value="">Employment Type</option>
                                    @foreach($types as $emp)
                                        <option value="{{$emp->id}}">{{$emp->name}}</option>

                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            @error('staff_category')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Staff Category</span></div>
                                <select class="form-control  @error('staff_category') is-invalid @enderror" wire:model.blur="staff_category" name="staff_category">
                                    <option value="">Staff Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>

                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Status</span></div>
                                <select class="form-control  @error('status') is-invalid @enderror" wire:model.live="status">
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="2">Suspended</option>
                                    <option value="3">Dismissed</option>
                                    <option value="4">Transferred</option>
                                    <option value="5">Retired</option>
                                    <option value="6">Leave of Absence</option>
                                    <option value="7">Secondment</option>
                                    {{--                                    <option value="8">Visiting Lecturers</option>--}}
                                    {{--                                    <option value="9">Part-timers</option>--}}
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            @error('unit')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Unit</span></div>
                                <select class="form-control @error('unit') is-invalid @enderror" wire:model.blur="unit" name="unit">
                                    <option value="">Select Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{$unit->id}}">{{$unit->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            @error('department')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Department</span></div>
                                <select class="form-control @error('department') is-invalid @enderror" wire:model.blur="department" name="department">
                                    <option value="">Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{$dept->id}}">{{$dept->name}}</option>
                                    @endforeach

                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            @error('salary_structure')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Salary Structure</span></div>
                                <select class="form-control @error('salary_structure') is-invalid @enderror" name="salary_structure" wire:model.blur="salary_structure" >
                                    <option value="">Salary Structure</option>
                                    @foreach($salary_structures as $salary)
                                        <option value="{{$salary->id}}">{{$salary->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            @error('grade_level_from')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            @php
                                $salObj=\App\Models\SalaryStructure::find($this->salary_structure);
                            @endphp
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Grade Level From</span></div>
                                <select  class="form-control @error('grade_level_from') is-invalid @enderror" name="grade_level_from" wire:model.blur="grade_level_from" type="number" >
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
                        <div class="col-12 col-md-4">
                            @error('grade_level')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            @php
                                $salObj=\App\Models\SalaryStructure::find($this->salary_structure);
                            @endphp
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Grade Level To</span></div>
                                <select  class="form-control @error('grade_level_to') is-invalid @enderror" name="grade_level_to" wire:model.blur="grade_level_to" type="number" >
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
                    </div>


                </fieldset>


                <div class="row mt-3">
                    <div class="col text-center">
                        <button class="btn my-1 my-md-0  generate" type="submit" wire:click.prevent="generate">Generate <span wire:loading><i class="fa-fa-spin fa-spinner"></i></span></button>
                        <button class="btn my-1 my-md-0  view" type="submit" >View <span wire:loading><i class="fa-fa-spin fa-spinner"></i></span></button>
                        {{--                        <button class="btn  btn-danger">Cancel</button>--}}
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{--    <form action="{{route('retired.staff.report')}}" method="post">--}}
    @csrf
{{--    @can('can_export')--}}
{{--        <button class="btn my-1 my-md-0 export float-right " wire:click.prevent="export">Export</button>--}}

{{--    @endcan--}}
    {{--    </form>--}}
    <div class="table-responsive">
        <table class="mt-3 table table-bordered">
            <thead>
            <tr>
                <th>S/N</th>
                <th>STAFF NO</th>
                <th>IP NO</th>
                <th>FULL NAME</th>
                <th>DEPARTMENT</th>
                <th>DFA</th>
                <th>TERMINATION DATE</th>
                <th>STATUS</th>
            </tr>
            </thead>
            <tbody>
            @foreach($employees as $employee)
                    <tr>
                        <th>{{ $loop->iteration}}</th>
                        <td>{{$employee->staff_number}}</td>
                        <td>{{$employee->payroll_number}}</td>
                        <td>{{$employee->full_name}}</td>
                        <td>{{dept($employee->department)}}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($employee->date_of_first_appointment)->format('d-F-Y')}}</td>
                        <td>{{\Illuminate\Support\Carbon::parse($employee->contract_termination_date)->format('d-F-Y')}}</td>
                        <td>
                            @if(\Illuminate\Support\Carbon::now()->diffInMonths($employee->contract_termination_date) == 0)
                                <em style="background: red;color:white;padding: 2px">Terminated</em>
                            @else
                                <em style="background: yellow;color:black;padding: 2px">
                                    {{\Illuminate\Support\Carbon::now()->diffInMonths($employee->contract_termination_date)}}
                                    Months Remaining
                                </em>
                            @endif

                        </td>
                    </tr>

            @endforeach
            </tbody>
        </table>

    </div>
{{--    @include('form_spin')--}}
    @section('title')
        Contract Termination List
    @endsection
    @section('page_title')
        Payroll Report / Contract Termination List
    @endsection
</div>
