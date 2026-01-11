<div>
    {{-- Do your work, then step back. --}}
    <style>
        svg{
            display: none;
        }
    </style>
    <div class="row mt-3">
        <div class="col table-responsive">
            {{--            <h6 class="text-center text-dark">KEEP RECORD OF ANNUAL INCREMENT HISTORY</h6>--}}
            <div>
                <form action="{{route('annual.increment.report')}}" method="post" id="myForm" target="_blank">
                    @csrf
                    <label for="">Status</label>
                    <select name="status" wire:model.live="status" class="form-control-sm">
                        <option value="">Select Status</option>
                        <option value="1">Successful</option>
                        <option value="0">Unsuccessful</option>
                    </select>
                    <label for="">Date from</label>
                    <input type="date" class="form-control-sm" wire:model.live="date_from">
                    <label for="">Date to</label>
                    <input type="date" class="form-control-sm" wire:model.live="date_to">
                    @can('can_export')
                        <button class="btn my-1 my-md-0 export float-right " wire:click.prevent="export">Export</button>

                    @endcan
                    @can('can_report')
                        <button class="btn my-1 my-md-0 view float-right mr-md-3" type="submit">View Report</button>

                    @endcan
                    {{--                <button class="btn my-1 my-md-0 export float-right " wire:click.prevent="export">Export</button>--}}
                    {{--                <button class="btn my-1 my-md-0 view float-right mr-md-3" type="submit">View</button>--}}
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-sm">
                    <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                        </div>
                    </div>
                    <thead>
                    <tr>
                        <th>S/N</th>
                        <th>MONTH/YEAR</th>
                        <th>STAFF NO </th>
                        <th>PAYROLL NO </th>
                        <th>NAME </th>
                        <th>CURRENT
                            SALARY </th>
                        <th>INCREMENT
                            SALARY </th>
                        <th>STATUS</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($histories as $history)
                        @php
                            $employee=\App\Models\EmployeeProfile::find($history->employee_id);
                            $salary=\App\Models\SalaryUpdate::where('employee_id',$history->employee_id)->first();
                        @endphp
                        <tr>
                            <th>{{($histories->currentPage() - 1) * $histories->perPage() + $loop->index+1}}</th>
                            <td>{{$history->increment_month}} {{$history->increment_year}}</td>
                            <td>{{$employee?$employee->staff_number:''}}</td>
                            <td>{{$employee?$employee->payroll_number:''}}</td>
                            <td>{{$employee?$employee->full_name:''}}</td>
                            <td>{{$history->grade_level}}/{{$history->old_grade_step}}</td>
                            <td>{{$history->grade_level}}/{{$history->new_grade_step}}</td>
                            <td>{{success_status($history->status)}}</td>
                        </tr>
                    @empty
                        no record
                    @endforelse
                    </tbody>
                    <tr>
                        <td colspan="6">{{$histories->links()}}</td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
{{--    @include('form_spin')--}}

@section('title')
        Annual Increment History
    @endsection
    @section('page_title')
       Payroll Report / Annual Increment History
    @endsection
</div>
