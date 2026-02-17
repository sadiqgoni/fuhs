<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <style>
        svg {
            display: none;
        }
    </style>
    @if($record == true)
        <div class="">
            <div class="text-right">
                <select name="" wire:model.live="filter_allow" id="" class="form-control-sm float-left">
                    <option value="">Salary Structure</option>
                    @foreach(\App\Models\SalaryStructure::all() as $allow)
                        <option value="{{$allow->id}}">{{$allow->name}}</option>
                    @endforeach
                </select>
                <select name="" id="" class="form-control-sm float-left ml-3" wire:model.live="perpage">
                    <option value="">show</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                @can('can_save')
                    <button class="btn mt-2 create" wire:click.prevent="create_allowance()">Add</button>
                    <button class="btn mt-2 btn-outline-primary ml-2"
                            wire:click="$set('show_step_matrix', true)">
                        Import Step Matrix
                    </button>
                @endcan

            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered mt-2">
                    <thead>
                        <tr class="text-uppercase">
                            <th>SN</th>
                            <th>Salary Structure</th>
                            <th>Grade Level From</th>
                            <th>Grade Level To</th>
                            <th>Allowance</th>
                            <th>Allowance Type</th>
                            <th>Value</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allowances as $allowance)
                            <tr>
                                <th>{{($allowances->currentpage() - 1) * $allowances->perPage() + $loop->iteration}}</th>
                                <td>{{ss($allowance->salary_structure_id)}}</td>
                                <td>{{$allowance->grade_level_from}}</td>
                                <td>{{$allowance->grade_level_to}}</td>
                                <td>{{allowance_name($allowance->allowance_id)}}</td>
                                <td>{{allowance_type($allowance->allowance_type)}}</td>
                                <td>@if($allowance->allowance_type == 1)
                                    {{$allowance->value}}%
                                @else
                                        {{number_format($allowance->value, 2)}}
                                    @endif
                                </td>
                                <td>
                                    @can('can_edit')
                                        <button class="btn edit_btn" wire:click.prevent="edit_allowance({{$allowance->id}})"
                                            style="width:fit-content !important;padding: 5px !important;">Edit</button>
                                        <button class="btn btn-sm btn-danger" wire:click.prevent="deleteId({{$allowance->id}})"
                                            style="width:fit-content !important;padding: 5px !important;">Delete</button>
                                    @endcan
                                </td>

                            </tr>
                        @empty
                            no record
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">{{$allowances->links()}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        {{-- Step-based allowance matrix import (only in list view) --}}
        @can('can_save')
            @if($show_step_matrix)
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">Step Matrix Import (Grade &amp; Step Specific)</h6>
                        <small class="text-muted">
                            Use this to import Excel/CSV tables like the CONHESS/CONMESS Call Duty by step.
                            Select the Salary Structure and Allowance, then upload the CSV you converted from Excel.
                        </small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="">Salary Structure</label>
                                    <select class="form-control form-control-sm" wire:model.live="step_structure_id">
                                        <option value="">Select Salary Structure</option>
                                        @foreach(\App\Models\SalaryStructure::all() as $ss)
                                            <option value="{{$ss->id}}">{{$ss->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="">Allowance</label>
                                    <select class="form-control form-control-sm" wire:model.live="step_allowance_id">
                                        <option value="">Select Allowance</option>
                                        @foreach(\App\Models\Allowance::where('status', 1)->get() as $al)
                                            <option value="{{$al->id}}">{{$al->allowance_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="">Excel / CSV File</label>
                                    <input type="file" class="form-control form-control-sm" wire:model="step_matrix_file" accept=".xlsx,.xls,.csv,.txt">
                                  
                                </div>
                            </div>
                        </div>
                        <div class="text-right mb-2">
                            <button type="button" class="btn btn-sm btn-primary" wire:click.prevent="importStepMatrix">
                                Import Step Matrix
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary ml-2"
                                    wire:click="$set('show_step_matrix', false)">
                                Close
                            </button>
                        </div>

                        @if(isset($stepPreview) && $stepPreview->count())
                            <div class="table-responsive mt-2">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Grade</th>
                                        <th>Step</th>
                                        <th>Amount (₦)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($stepPreview as $row)
                                        <tr>
                                            <td>{{$row->grade_level}}</td>
                                            <td>{{$row->step}}</td>
                                            <td>{{number_format($row->value, 2)}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <small class="text-muted">
                                    Showing {{ $stepPreview->count() }} entries for this structure & allowance.
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endcan
    @endif
    @if($edit == true)
        @can('can_edit')
            <div>
                <form wire:submit.prevent="update({{$ids}})">
                    <fieldset>
                        <legend>
                            <h6>Update Allowance Template</h6>
                        </legend>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <label for="">Salary Structure @error('salary_structure') <small
                                class="text-danger">{{$message}}</small> @enderror</label>
                                <select name="" id="" class="form-control" wire:model.live="salary_structure" disabled
                                    readonly="">
                                    <option value="">Select Salary Structure</option>
                                    @foreach(\App\Models\SalaryStructure::all() as $salary_structure)
                                        <option value="{{$salary_structure->id}}">{{$salary_structure->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @php
                                $salObj = \App\Models\SalaryStructure::find($this->salary_structure);
                                if (!is_null($salary_structure)) {
                                    $salObjs = \App\Models\SalaryStructureTemplate::where('salary_structure_id', $this->salary_structure)->select('grade_level')->distinct()->orderBy('grade_level')->get();

                                }
                            @endphp
                            <div class="col-12 col-md-4">
                                <label for="">Grade Level From @error('grade_level_from') <small
                                class="text-danger">{{$message}}</small> @enderror</label>
                                <select class="form-control @error('grade_level_from') is-invalid @enderror"
                                    wire:model.blur="grade_level_from" type="number">
                                    <option value="">Select Grade Level</option>
                                    @if($this->salary_structure != '')
                                        @foreach($salObjs as $obj)
                                            <option value="{{$obj->grade_level}}">Grade {{$obj->grade_level}}</option>

                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-12 col-md-4">

                                @php
                                    $salObj = \App\Models\SalaryStructure::find($this->salary_structure);
                                    if (!is_null($salary_structure)) {
                                        $salObjs = \App\Models\SalaryStructureTemplate::where('salary_structure_id', $this->salary_structure)->select('grade_level')->distinct()->orderBy('grade_level')->get();

                                    }
                                @endphp
                                <label for="">Grade Level To @error('grade_level_to')<small
                                class="text-danger">{{$message}}</small>@enderror</label>
                                <select class="form-control @error('grade_level_to') is-invalid @enderror"
                                    wire:model.blur="grade_level_to" type="number">
                                    <option value="">Select Grade Level</option>
                                    @if($this->salary_structure != '')
                                        @foreach($salObjs as $obj)
                                            <option value="{{$obj->grade_level}}">Grade {{$obj->grade_level}}</option>

                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="">Allowance @error('allowance')<small
                                    class="text-danger">{{$message}}</small>@enderror</label>
                                    <select name="" class="form-control text-capitalize" wire:model.defer="allowance">
                                        <option value="">Select Allowance</option>
                                        @foreach(\App\Models\Allowance::where('status', 1)->get() as $ss)
                                            <option value="{{$ss->id}}">{{$ss->allowance_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="">Allowance Type @error('allowance_type')<small
                                    class="text-danger">{{$message}}</small>@enderror</label>
                                    {{-- <input type="text" wire:model="allowance_type" class="form-control" readonly
                                        disabled>--}}
                                    <select name="" id="" wire:model.defer="allowance_type" class="form-control">
                                        <option value="">Select Allowance Type</option>
                                        <option value="1">Percentage of Basics</option>
                                        <option value="2">Fixed Amount</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="">Value @error('value')<small
                                    class="text-danger">{{$message}}</small>@enderror</label>
                                    <input type="text" wire:model="value" class="form-control">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="mt-3 text-right">
                        <button class="save_btn btn mr-md-3" type="submit">Update</button>
                        <button class="close_btn btn mt-2 mt-md-0" wire:click.prevent="close()">Close</button>
                    </div>
                </form>
            </div>
        @endcan

    @endif
    @if($create == true)
        @can('can_save')
            <div>
                <form wire:submit.prevent="store()">
                    <div wire:loading
                        style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                        </div>
                    </div>
                    <fieldset>
                        <legend>
                            <h6>Add Allowance Template</h6>
                        </legend>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Salary Structure @error('salary_structure_name') <small
                                    class="text-danger">{{$message}}</small> @enderror</label>
                                    <select name="" class="form-control text-capitalize"
                                        wire:model.live="salary_structure_name">
                                        <option value="">Select Salary Structure</option>
                                        @foreach(\App\Models\SalaryStructure::all() as $ss)
                                            <option value="{{$ss->id}}">{{$ss->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Allowance @error('allowance_name') <small
                                    class="text-danger">{{$message}}</small> @enderror</label>
                                    <select name="" class="form-control text-capitalize" wire:model.defer="allowance_name">
                                        <option value="">Select Allowance</option>
                                        @foreach(\App\Models\Allowance::where('status', 1)->get() as $ss)
                                            <option value="{{$ss->id}}">{{$ss->allowance_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="col-12 col-lg-6">

                                @php
                                    $salObj = \App\Models\SalaryStructure::find($this->salary_structure_name);
                                    if (!is_null($salary_structure_name)) {
                                        $salObjs = \App\Models\SalaryStructureTemplate::where('salary_structure_id', $this->salary_structure_name)->select('grade_level')->distinct()->orderBy('grade_level')->get();

                                    }
                                @endphp
                                <div class="form-group">
                                    <label>Grade Level From @error('grade_level_from')<small
                                    class="text-danger">{{$message}}</small>@enderror</label>
                                    <select class="form-control @error('grade_level_from') is-invalid @enderror"
                                        name="grade_level_from" wire:model.blur="grade_level_from" type="number">
                                        <option value="">Select Grade Level</option>
                                        @if($this->salary_structure_name != '')
                                            @foreach($salObjs as $obj)
                                                <option value="{{$obj->grade_level}}">Grade {{$obj->grade_level}}</option>

                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                @php
                                    $salObj = \App\Models\SalaryStructure::find($this->salary_structure_name);
                                    if (!is_null($salary_structure_name)) {
                                        $salObjs = \App\Models\SalaryStructureTemplate::where('salary_structure_id', $this->salary_structure_name)->select('grade_level')->distinct()->orderBy('grade_level')->get();

                                    }
                                @endphp
                                <div class="form-group">
                                    <label>Grade Level To @error('grade_level_to')<small
                                    class="text-danger d-block form-text">{{$message}}</small>@enderror</label>
                                    <select class="form-control @error('grade_level_to') is-invalid @enderror"
                                        name="grade_level_to" wire:model.defer="grade_level_to" type="number">
                                        <option value="">Select Grade Level</option>
                                        @if($this->salary_structure_name != '')
                                            @foreach($salObjs as $obj)
                                                <option value="{{$obj->grade_level}}">Grade {{$obj->grade_level}}</option>

                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="">Allowance Type @error('allowance_type')<small
                                    class="text-danger d-block form-text">{{$message}}</small>@enderror</label>
                                    <select name="" id="" wire:model.defer="allowance_type" class="form-control">
                                        <option value="">Select Allowance Type</option>
                                        <option value="1">Percentage of Basics</option>
                                        <option value="2">Fixed Amount</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="">Amount @error('amount')<small
                                    class="text-danger d-block form-text">{{$message}}</small>@enderror</label>
                                    <input name="" id="" wire:model.defer="amount" class="form-control">

                                </div>
                            </div>

                        </div>

                        {{-- Note: per-step allowance matrices (e.g. CONHESS/CONMESS Call Duty by step)
                             are imported via a dedicated tool, not through this simple grade-range form. --}}
                    </fieldset>
                    <div class="mt-3 text-center">
                        <button class="btn save_btn ">Submit</button>
                        <button class="close_btn btn mt-2 mt-md-0 mr-3" wire:click.prevent="close()">Close</button>
                    </div>

                </form>
            </div>
        @endcan

    @endif
    @section('title')
        Salary Allowance Template
    @endsection
    @section('page_title')
        Payroll Settings / Allowance Template
    @endsection
</div>