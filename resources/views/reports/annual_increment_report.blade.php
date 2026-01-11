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
<p style="margin: 10px 1px 1px 1px;text-align: center;text-transform: capitalize">Annual Increment Report for the month of {{\Illuminate\Support\Carbon::parse($month)->format('F Y')}}</p>
<table class="table table-striped table-bordered table-sm"  style="border-collapse: collapse">
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
            <th>{{$loop->iteration}}</th>
            <td>{{$history->increment_month}} {{$history->increment_year}}</td>
            <td>{{$employee? $employee->staff_number:''}}</td>
            <td>{{$employee? $employee->payroll_number:''}}</td>
            <td style="text-transform: capitalize">{{$employee?strtolower($employee->full_name):''}}</td>
            <td>{{number_format($history->current_salary,2)}}</td>
            <td>{{number_format($history->new_salary,2)}}</td>
            <td>{{success_status($history->status)}}</td>
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
