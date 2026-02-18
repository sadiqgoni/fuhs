<div>
    <style>
        svg {
            display: none;
        }
    </style>
    @if($record == true)
        <div class="text-right">
            <label for="" class=" float-left">Filter By</label>
            <select wire:model.live="filter_structure" class="form-control-sm float-left mr-2">
                <option value="">Select Salary Structure</option>
                @foreach($salaryStructures as $ss)
                    <option value="{{$ss->id}}">{{$ss->name}}</option>
                @endforeach
            </select>

            <select wire:model.live="filter_allowance" class="form-control-sm float-left">
                <option value="">Select Allowance</option>
                @foreach($allowances as $al)
                    <option value="{{$al->id}}">{{$al->allowance_name}}</option>
                @endforeach
            </select>

            <button class="btn mt-2 create" wire:click.prevent="create_mode()">Add</button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm mt-2" style="font-size: 13px">
                <thead>
                    <tr style="text-transform: uppercase">
                        <th>S/N</th>
                        <th>Salary Structure</th>
                        <th>Allowance</th>
                        <th>Grade Level</th>
                        @for($i = 1; $i <= $max_steps; $i++)
                            <th>Step {{$i}}</th>
                        @endfor
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gradeLevels as $gl)
                        <tr>
                            <td>{{ ($gradeLevels->currentpage() - 1) * $gradeLevels->perPage() + $loop->iteration }}</td>
                            <td>{{ \App\Models\SalaryStructure::find($filter_structure)?->name }}</td>
                            <td>{{ \App\Models\Allowance::find($filter_allowance)?->allowance_name }}</td>
                            <td>Grade {{ $gl->grade_level }}</td>
                            @for($i = 1; $i <= $max_steps; $i++)
                                @php
                                    $val = 0;
                                    if (isset($stepDataValues[$gl->grade_level])) {
                                        $stepRow = $stepDataValues[$gl->grade_level]->firstWhere('step', $i);
                                        $val = $stepRow ? $stepRow->value : 0;
                                    }
                                @endphp
                                <td>{{ number_format($val, 2) }}</td>
                            @endfor
                            <td>
                                <button class="btn btn-sm btn-info"
                                    wire:click.prevent="edit_grade({{$gl->grade_level}})">Edit</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $max_steps + 5 }}" class="text-center">No records found. Select filters or add new
                                data.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="{{ $max_steps + 5 }}">{{$gradeLevels->links()}}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    @if($edit == true)
        <div class="row">
            <div class="col">
                <form wire:submit.prevent="update_grade">
                    <fieldset>
                        <legend>
                            <h6 class="">Update Step Allowance (Grade {{ $edit_grade_level }})</h6>
                        </legend>
                        <div class="form-group input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Salary Structure</span></div>
                            <input class="form-control"
                                value="{{ \App\Models\SalaryStructure::find($filter_structure)?->name }}" readonly disabled
                                type="text">
                        </div>
                        <div class="form-group input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Allowance</span></div>
                            <input class="form-control"
                                value="{{ \App\Models\Allowance::find($filter_allowance)?->allowance_name }}" readonly
                                disabled type="text">
                        </div>

                    </fieldset>

                    <fieldset class="mt-4">
                        <legend>
                            <h6>Steps Values</h6>
                        </legend>
                        <div class="row">
                            @for($i = 1; $i <= $max_steps; $i++)
                                <div class="col-12 col-md-4 col-lg-3">
                                    <div class="input-group form-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Step {{$i}}</span></div>
                                        <input wire:model="steps_data.{{$i}}" class="form-control" type="number" step="0.01">
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </fieldset>
                    <div class="mt-3">
                        <button class="btn save_btn" type="submit">Save</button>
                        <button class="close_btn mt-2 mt-md-0 btn" wire:click.prevent="close()">Close</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($create == true)
        <div>
            <form wire:submit.prevent="import()">
                <div wire:loading
                    style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                    <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                        <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                    </div>
                </div>
                <fieldset>
                    <legend>
                        <h6>Import Step Matrix</h6>
                    </legend>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="">Salary Structure @error('filter_structure') <small
                                class="text-danger">{{$message}}</small> @enderror</label>
                                <select name="" class="form-control-sm text-capitalize" wire:model.blur="filter_structure">
                                    <option value="">Select Salary Structure</option>
                                    @foreach($salaryStructures as $ss)
                                        <option value="{{$ss->id}}">{{$ss->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="">Allowance @error('filter_allowance') <small
                                class="text-danger">{{$message}}</small> @enderror</label>
                                <select name="" class="form-control-sm text-capitalize" wire:model.blur="filter_allowance">
                                    <option value="">Select Allowance</option>
                                    @foreach($allowances as $al)
                                        <option value="{{$al->id}}">{{$al->allowance_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <label for="">Choose Matrix CSV/Excel File</label>
                    <input type="file" class="form-control-sm" wire:model="importFile" accept=".csv, .xlsx, .xls, .txt">
                    @error('importFile') <div class="text-danger">{{$message}}</div> @enderror

                    <small class="text-muted d-block mt-2">
                        Format: The file must have the Grade Level in the first column, followed by Step 1, Step 2, Step 3,
                        etc.
                    </small>
                </fieldset>
                <div class="mt-3">
                    <button class="btn save_btn">Upload & Import</button>
                    <button class="close_btn mt-2 mt-md-0 btn" wire:click.prevent="close()">Close</button>
                </div>
            </form>
        </div>
    @endif

    @section('title')
        Step Allowance Template
    @endsection
    @section('page_title')
        Payroll Settings / Step Allowance Template
    @endsection
</div>