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
<img src="{{public_path('storage/'.app_settings()->logo)}}" alt="" style="width: 35px;position: absolute;left: 120px">
<h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</h3>
<h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>
<p style="margin: 10px 1px 1px 1px;text-align: center;text-transform: capitalize"> Contract Termination List </p>
<table style="border-collapse: collapse;width: 100%" class="mt-3 table table-bordered">
    <thead>
    <tr>
        <th>S/N</th>
        <th>STAFF NO</th>
        <th>IP NO</th>
        <th>FULL NAME</th>
        <th>DEPARTMENT</th>
        <th>DFA</th>
        <th>CONTRACT TERMINATION</th>
        <th>STATUS</th>
    </tr>
    </thead>
    <tbody>
    @php
        $counter=1
    @endphp
    @foreach($employees as $employee)

            <tr>
                <th>{{ $counter}}</th>
                <td>{{$employee->staff_number}}</td>
                <td>{{$employee->payroll_number}}</td>
                <td>{{$employee->full_name}}</td>
                <td>{{dept($employee->department)}}</td>
                <td>{{ \Illuminate\Support\Carbon::parse($employee->date_of_first_appointment)->format('d-F-Y')}}</td>
                <td>{{\Illuminate\Support\Carbon::parse($employee->contract_termination_date)->format('d-F-Y')}}</td>
                <td>
                    @if(\Illuminate\Support\Carbon::now()->diffInDays($employee->contract_termination_date) == 0)
                        <em style="background: red;color:white;padding: 2px">Terminated</em>
                    @else
                        <em style="background: yellow;color:black;padding: 2px">
                            {{\Illuminate\Support\Carbon::now()->diffInMonths($employee->contract_termination_date)}}
                            Months Remaining
                        </em>
                    @endif
                </td>
            </tr>

@php
    $counter++
@endphp
    @endforeach
    </tbody>
</table>

</body>
</html>
