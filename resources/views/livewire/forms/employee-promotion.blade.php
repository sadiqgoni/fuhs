<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <style>
        svg {
            display: none;
        }

        .upload-btn-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .upload-btn-wrapper .btn {
            border: 2px solid gray;
            color: gray;
            background-color: white;
            padding: 6px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
        }

        .upload-btn-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }

        .inport_x label {
            border: 2px solid gray;
            color: gray;
            background-color: white;
            padding: 6px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <div wire:loading
        style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
        </div>
    </div>
    @if($record == true)
        <div>
            <div class="row">
                <div class="col-12 col-md-4">
                    {{-- <label for="">Month/Year</label> <input wire:model.live="month" type="month"
                        class="form-control-sm">--}}
                    {{-- <label for="">Total no. of staff</label>--}}
                    {{-- <input wire:model.lazy="number_of_staff" style="max-width: 120px" class="form-control-sm"
                        readonly>--}}

                    <a href="{{url('assets/excel_sample/promotion.xlsx')}}" class="d-block text-danger">click to download
                        sample excel file</a>
                </div>
                <div class="col-12 col-md-8">

                </div>
            </div>

            <button class="btn save_btn float-right mx-3 create" wire:click="post_to_ledger()">Post to ledger</button>
            <button class="btn create float-right" wire:click="create_record()">Add Staff</button>
            <div class="mx-3 float-right">
                <form action="" wire:submit.prevent="uploadFile()" class="inport_x">

                    <input type="file" wire:model="importFile" id="actual-btn" hidden />

                    <label for="actual-btn" style="z-index: 999 !important;">@if($importFile)
                    {{$importFile->getClientOriginalName()}} @else Choose File @endif</label>
                    <button class="btn btn-sm btn-light"
                        style=" border: 2px solid gray;color: gray;padding: 6px 20px;border-radius: 8px;font-size: 14px;font-weight: bold;margin-left:-10px; margin-top: -2px"
                        type="submit">upload</button>
                </form>
                @if(!empty($upload_errors))
                    <table class="table-sm table table-bordered table-striped table-warning">
                        <tr>
                            <td>Payroll Number</td>
                            <td>Error Occurred</td>
                        </tr>
                        @foreach($upload_errors as $err)
                            <tr>
                                <td>{{$err[0]}}</td>
                                <td>{{$err[1]}}</td>
                            </tr>
                        @endforeach
                    </table>
                @endif

            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered mt-2">

                <thead>
                    <tr style="text-transform: uppercase;">
                        <th>S/N</th>
                        <th>Payroll ID</th>
                        <th>Salary Structure </th>
                        <th>Grade Level</th>
                        <th>Step</th>
                        <th>Arrears Months</th>
                        <th>status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $promotion)
                        <tr>
                            <td>{{($promotions->currentPage() - 1) * $promotions->perpage() + $loop->index + 1}}</td>
                            <td>{{$promotion->payroll_number}}</td>
                            <td>{{ss($promotion->salary_structure)}}</td>
                            <td>{{$promotion->level}}</td>
                            <td>{{$promotion->step}}</td>
                            <td>{{$promotion->arrears_months}}</td>
                            <td>
                                @if($promotion->status == 1)
                                    <span class="badge badge-success"><em>Done</em></span>
                                @else
                                    <span class="badge badge-warning"><em>Pending</em></span>

                                @endif
                            </td>
                            <td>
                                <button style="width: 55px !important;padding: 1px !important;"
                                    class="btn btn-danger float-right" wire:click="deleteId({{$promotion->id}})">Delete</button>
                                @if($promotion->status != 1)
                                    <button style="width: 55px !important;padding: 1px !important;" class="btn edit_btn float-right"
                                        wire:click="edit_record({{$promotion->id}})">Edit</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="color: red">Empty</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr style="border: 0 !important;">
                        @if($promotions->count() > 1)
                            <td colspan="6" style="border: 0 !important;"><Button wire:click.prevent="clear_record()"
                                    class="btn btn-danger float-right">Clear Record</Button></td>

                        @endif
                    </tr>
                    <tr>
                        <td colspan="6">{{$promotions->links()}}</td>
                    </tr>
                </tfoot>
            </table>

            @if(!empty($ledger_fails))
                <table class="table-sm table-bordered table-warning">
                    <tr>
                        <th colspan="2">The following records failed to update <button wire:click="close"
                                style="border: none;background: none;float: right;color: red;font-size: 20px">X</button></th>
                    </tr>
                    <tr>
                        <th>Payroll Number</th>
                        <th>Staff Name</th>
                        <th>Reason</th>
                    </tr>

                    @foreach($ledger_fails as $ledger)
                        @php
                            $emp = \App\Models\EmployeeProfile::find($ledger['id']);
                            $ss = \App\Models\SalaryStructure::find($ledger['ss'])
                        @endphp
                        <tr>
                            <td>{{$emp->payroll_number}}</td>
                            <td>{{$emp->full_name}}</td>
                            <td>{{$ss->name}} grade {{$ledger['l']}} is not define in salary template</td>
                        </tr>
                    @endforeach




                </table>
            @endif

        </div>
    @endif
    @if($create == true)
            <div class="row">
                <div class="col-12 col-md-10 offset-md-1">
                    <form wire:submit.prevent="store">
                        <fieldset>
                            <legend>
                                <h6 class="">Add Staff Promotion</h6>
                            </legend>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    @error('payroll_number')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                    <div class="input-group form-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><span
                                                    class="d-none d-md-inline">Payroll</span> Number</span></div>
                                        <input class="form-control @error('payroll_number') is-invalid @enderror"
                                            wire:model.lazy="payroll_number" type="text">
                                        <div class="input-group-append"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    @error('staff_number')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                    <div class="input-group form-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><span
                                                    class="d-none d-md-inline">Staff</span> Number</span></div>
                                        <input class="form-control @error('staff_number') is-invalid @enderror"
                                            wire:model.lazy="staff_number" type="text">
                                        <div class="input-group-append"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    @error('staff_name')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                    <div class="input-group form-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><span
                                                    class="d-none d-md-inline">Staff</span> Name</span></div>
                                        <input class="form-control @error('staff_name') is-invalid @enderror"
                                            wire:model.lazy="staff_name" disabled readonly type="text">
                                        <div class="input-group-append"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    @error('salary_structure')
                                        <strong class="text-danger d-block form-text">{{$message}}</strong>
                                    @enderror
                                    <div class="input-group form-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Salary Structure</span>
                                        </div>
                                        <select class="form-control @error('salary_structure') is-invalid @enderror"
                                            name="salary_structure" wire:model.blur="salary_structure">
                                            <option value="">Salary Structure</option>
                                            @foreach(\App\Models\SalaryStructure::where('status', 1)->get() as $salary)
                                                <option value="{{$salary->id}}">{{$salary->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    @error('level')
                                        <strong class="text-danger d-block form-text">{{$message}}</strong>
                                    @enderror
                                    @php
                                        if (!is_null($salary_structure)) {
                                            $salObj = \App\Models\SalaryStructureTemplate::where('salary_structure_id', $this->salary_structure)->select('grade_level')->get();

                                        }
                                    @endphp
                                    <div class="input-group form-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Grade Level From</span>
                                        </div>
                                        <select class="form-control @error('level') is-invalid @enderror" name="level"
                                            wire:model.blur="level" type="number">
                                            <option value="">Select Grade Level</option>
                                            @if($this->salary_structure != '')
                                                @foreach($salObj as $obj)
                                                    <option value="{{$obj->grade_level}}">Grade {{$obj->grade_level}}</option>

                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="input-group-append"></div>
                                    </div>
                                </div>


                                <div class="col-12 col-md-4">
                                    @error('step')
                                        <strong class="text-danger d-block form-text">{{$message}}</strong>
                                    @enderror

                                    <div class="input-group form-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Step</span></div>
                                        <select class="form-control @error('step') is-invalid @enderror" wire:model.blur="step">
                                            @if(!is_null($salObj) && !is_null($salary_structure))
                                                @php
                                                    $step_no = \App\Models\SalaryStructureTemplate::where('salary_structure_id', $salary_structure)
                                                        ->where('grade_level', $level)
                                                        ->first()
                                                @endphp
                                                <option value="">Select Step</option>
                                                @if(!is_null($step_no))
                                                    @for($i = 1; $i <= $step_no->no_of_grade_steps; $i++)
                                                        <option value="{{$i}}">Step {{$i}}</option>
                                                    @endfor
                                                @endif
                                            @endif



                                        </select>
                                        <div class="input-group-append"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                @error('arrears_months')
                                    <strong class="text-danger d-block form-text">{{$message}}</strong>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Arrears Months</span></div>
                                    <input class="form-control @error('arrears_months') is-invalid @enderror"
                                        wire:model.blur="arrears_months" type="number" min="0">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                </div>



                </fieldset>

                <div class="row mt-3">
                    <div class="col-12 col-md-8"><button class="btn save_btn" type="submit">Save</button>
                        <button class="btn close_btn mt-2 mt-md-0 " wire:click.prevent="close">Close</button>
                    </div>
                </div>
                </form>

            </div>
        </div>
    @endif
@if($edit == true)
    <div class="row">
        <div class="col-12 col-md-10 offset-md-1">
            <form wire:submit.prevent="update({{$ids}})">
                <fieldset>
                    <legend>
                        <h6 class="">Update Staff Promotion</h6>
                    </legend>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            @error('payroll_number')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text"><span
                                            class="d-none d-md-inline">Payroll</span> Number</span></div>
                                <input class="form-control @error('payroll_number') is-invalid @enderror"
                                    wire:model.lazy="payroll_number" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            @error('staff_number')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text"><span
                                            class="d-none d-md-inline">Staff</span> Number</span></div>
                                <input class="form-control @error('staff_number') is-invalid @enderror"
                                    wire:model.lazy="staff_number" readonly disabled type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            @error('staff_name')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text"><span
                                            class="d-none d-md-inline">Staff</span> Name</span></div>
                                <input class="form-control @error('staff_name') is-invalid @enderror"
                                    wire:model.lazy="staff_name" disabled readonly type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            @error('salary_structure')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Salary Structure</span>
                                </div>
                                <select class="form-control @error('salary_structure') is-invalid @enderror"
                                    name="salary_structure" wire:model.blur="salary_structure">
                                    <option value="">Salary Structure</option>
                                    @foreach(\App\Models\SalaryStructure::where('status', 1)->get() as $salary)
                                        <option value="{{$salary->id}}">{{$salary->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            @error('level')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            @php
                                if (!is_null($salary_structure)) {
                                    $salObj = \App\Models\SalaryStructureTemplate::where('salary_structure_id', $this->salary_structure)->select('grade_level')->get();

                                }
                            @endphp
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Grade Level From</span>
                                </div>
                                <select class="form-control @error('level') is-invalid @enderror" name="level"
                                    wire:model.blur="level" type="number">
                                    <option value="">Select Grade Level</option>
                                    @if($this->salary_structure != '')
                                        @foreach($salObj as $obj)
                                            <option value="{{$obj->grade_level}}">Grade {{$obj->grade_level}}</option>

                                        @endforeach
                                    @endif
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>


                        <div class="col-12 col-md-4">
                            @error('step')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Step</span></div>
                                <select class="form-control @error('step') is-invalid @enderror" wire:model.blur="step">
                                    @if(!is_null($salObj) && !is_null($salary_structure))
                                        @php
                                            $step_no = \App\Models\SalaryStructureTemplate::where('salary_structure_id', $salary_structure)
                                                ->where('grade_level', $level)
                                                ->first()
                                        @endphp
                                        <option value="">Select Step</option>
                                        @if(!is_null($step_no))
                                            @for($i = 1; $i <= $step_no->no_of_grade_steps; $i++)
                                                <option value="{{$i}}">Step {{$i}}</option>
                                            @endfor
                                        @endif
                                    @endif



                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            @error('arrears_months')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Arrears Months</span></div>
                                <input class="form-control @error('arrears_months') is-invalid @enderror"
                                    wire:model.blur="arrears_months" type="number" min="0">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>



                </fieldset>

                <div class="row mt-3">
                    <div class="col-12 col-md-8"><button class="btn save_btn" type="submit">Update</button>
                        <button class="btn close_btn mt-2 mt-md-0 " wire:click.prevent="close">Close</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

@endif

@section('title')
    Staff Promotion
@endsection
@section('page_title')
    Payroll Update / Staff Promotion
@endsection
</div>