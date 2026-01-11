<div>
    {{-- Stop trying to control. --}}
    <style>
        svg{
            display: none;
        }
    </style>
    <div>
        <form action="{{route('loan.deduction.report')}}" method="post" id="myForm">
            @csrf
            <label for="">Filter Deduction</label>
            <select name="deduction" id="" wire:model.live="deduction" class="form-control-sm">
                <option value="">Select Deduction</option>
                @foreach(\App\Models\Deduction::where('status',1)->get() as $deduction)
                    <option value="{{$deduction->id}}">{{$deduction->deduction_name}}</option>
                @endforeach
            </select>

            <label for="">Show</label>
            <select name="" id="" wire:model.live="perpage" class="form-control-sm">
                <option value=""></option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="250">250</option>
                <option value="500">500</option>
            </select>
            <label for="">Date from</label>
            <input type="date" class="form-control-sm" wire:model.live="date_from" name="date_from">
            <label for="">Date to</label>
            <input type="date" class="form-control-sm" wire:model.live="date_to" name="date_to">
            @can('can_export')
                <button class="btn my-1 my-md-0 export float-right " wire:click.prevent="export">Export</button>

            @endcan
            @can('can_report')
                <button class="btn my-1 my-md-0 view float-right mr-md-3" type="submit">View Report</button>

            @endcan
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-stripped table-bordered">
            <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                    <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                </div>
            </div>
            <thead>
            <tr>
                <th>S/N</th>
                <th>START <br> MONTH/YEAR</th>
                <th>STAFF NO.</th>
                <th>PAYROLL NO.</th>
                <th>DEDUCTION</th>
                <th>NO. OF <br/> INSTALMENT</th>
                <th>AMOUNT PAID</th>
                <th>PAY <br> MONTH/YEAR</th>
                <th>COUNTDOWN</th>
            </tr>
            </thead>
            <tbody>
            @forelse($deductions as $deduction)
                @php
                    $ded=\App\Models\LoanDeductionCountdown::find($deduction->employee_id);
                    $emp=\App\Models\EmployeeProfile::find($ded->employee_id);
                    $deduct=\App\Models\Deduction::find($ded->deduction_id);
                @endphp
                <tr>
                    <td>{{($deductions->currentpage() -1 ) * $deductions->perpage() + $loop->iteration}}</td>
                    <td>{{\Illuminate\Support\Carbon::parse($deduction->start_month)->format('F,Y')}}</td>
                    <td>{{$emp? $emp->staff_number : ''}}</td>
                    <td>{{$emp? $emp->payroll_number : ''}}</td>
                    <td>{{$deduct->deduction_name}}</td>
                    <td>{{$deduction->no_of_installment}}</td>
                    <td>{{number_format($deduction->amount_paid,2)}}</td>
                    <td>{{\Illuminate\Support\Carbon::parse($deduction->pay_month_year)->format('F,Y')}}</td>
                    <td>{{$deduction->ded_countdown}}</td>
                </tr>
            @empty
                no record
            @endforelse
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4">{{$deductions->render()}}</td>
            </tr>
            </tfoot>
        </table>

    </div>
    @include('form_spin')
    @section('title')
        Loan Deduction Countdown History
    @endsection
    @section('page_title')
        Payroll Report / Loan Deduction History
    @endsection
</div>
