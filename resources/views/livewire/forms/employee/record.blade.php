@if($record==true)
<div class="row">
    <div class="col">
        <div>
            <input type="text" class="form-control-sm" wire:model.live="search" placeholder="Search for Employee..">

            <label for="">Filter By:</label>
            <select name="" id="" class="form-control-sm" wire:model.live="filter_type">
                <option value="">Employment Type</option>
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


            <label for="">Show</label>
            <select name="" id="" class="form-control-sm" wire:model.live="perpage">
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="250">250</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
            </select>
            <button class="btn create btn-sm float-right" wire:click.prevent="create_emp()"><i class="fa fa-plus"></i> Add Employee </button>

            {{--                                                                    <form wire:submit="import" style="position: absolute;right: 25px;margin-top: -77px">--}}

                {{--                                                                        <input id="upload{{ $iteration }}"   type="file" class="  @error('importFile') is-invalid @enderror" wire:model.live="importFile">--}}
                {{--                                                                        <div class="input-group-append">--}}
                    {{--                                                                                        <button type="submit" class="input-group-text btn btn-sm btn-info " id="">Import Excell<i class="fa fa-file-excel-o"></i></button>--}}
                    {{--                                                                                    </div>--}}
                {{--                                                                                    @error('importFile')--}}
                {{--                                                                                    <div id="validationServer03Feedback" class="is-invalid text-danger">{{$message}}</div>--}}
                {{--                                                                                    @enderror--}}

                {{--                                                                            @if($importing && !$importFinished)--}}
                {{--                                                                                <div wire:poll="updateImportProgress" class="text-danger">Importing...please wait.</div>--}}
                {{--                                                                            @endif--}}
                {{--                                                                            @if($importFinished)--}}
                {{--                                                                                <em class="text-success font-weight-bold">Finished Importing</em>--}}
                {{--                                                                            @endif--}}


                {{--                                                                    </form>--}}
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-stripped">
{{--                <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">--}}
{{--                    <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">--}}
{{--                        <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>--}}
{{--                    </div>--}}
{{--                </div>--}}
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

                    <td>
                        <button class="btn btn-sm btn-info" wire:click.prevent="view_emp({{$employee->id}})">View <i class="fa fa-eye"></i></button>
                        <button class="btn btn-sm btn-danger" wire:click.prevent="deleteId({{$employee->id}})">Delete <i class="fa fa-trash-o"></i></button>
                    </td>
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
</div>
@endif
