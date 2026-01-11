<table class="table table-stripped table-bordered">
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
            <td>{{$loop->iteration}}</td>
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

</table>
