<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        tr td,tr th{
            border: 1px solid black;
        }
        *{
            font-size: 12px;
        }
        #footer { position: fixed; right: 0px; bottom: 10px; text-align: center;border-top: 1px solid black;}
        #footer .page:after { content: counter(page, decimal); }
        @page { margin: 20px 30px 40px 50px; }
    </style>
</head>
<body>
<img src="{{public_path('storage/'.app_settings()->logo)}}" alt="" style="width: 35px;position: absolute;left: 70px">
<h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</h3>
<h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>
<p style="margin: 10px 1px 1px 1px;text-align: center;text-transform: capitalize">Loan Deduction Report for the month of {{\Illuminate\Support\Carbon::parse($month)->format('F Y')}}</p>
<table class="table table-sm table-bordered" style="font-size: 12px;border-collapse: collapse;margin-top: 20px">
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
<div id="footer">
    <p class="page">Page </p>
</div>
</body>
</html>
